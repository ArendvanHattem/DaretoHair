<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Pricelist;

class ClientAgendaController extends Controller
{
    public function index(Request $request)
    {
        $isAdmin = auth()->user()->hasRole('medewerker');
        $selectedMedewerkerId = $request->input('medewerker_id');

        $medewerkers = User::role('medewerker')->get();
        if (!$selectedMedewerkerId && $medewerkers->count() > 0) {
            $selectedMedewerkerId = $medewerkers->first()->id;
        }

        $weekStart = $request->input('week', now()->startOfWeek()->format('Y-m-d'));
        $weekStart = Carbon::parse($weekStart);

        $days = collect();
        for ($i = 0; $i < 7; $i++) {
            $days->push($weekStart->copy()->addDays($i));
        }

        $appointmentsQuery = Appointment::with($isAdmin ? 'user' : [])
            ->whereBetween('appointment_date', [
                $weekStart,
                $weekStart->copy()->addWeek()
            ]);

        if ($selectedMedewerkerId) {
            $appointmentsQuery->where('medewerker_id', $selectedMedewerkerId);
        }

        $appointments = $appointmentsQuery->orderBy('appointment_date')->get();

        $appointmentsByDay = $appointments->groupBy(function ($appointment) {
            return Carbon::parse($appointment->appointment_date)->format('Y-m-d');
        });

        $previousWeek = $weekStart->copy()->subWeek()->format('Y-m-d');
        $nextWeek = $weekStart->copy()->addWeek()->format('Y-m-d');

        $openingHours = [
            'Monday' => ['09:00', '18:00'],
            'Tuesday' => ['09:00', '18:00'],
            'Wednesday' => ['09:00', '18:00'],
            'Thursday' => ['09:00', '18:00'],
            'Friday' => ['09:00', '18:00'],
            'Saturday' => ['09:00', '16:00'],
            'Sunday' => ['closed', 'closed'],
        ];

        return view('appointments.index', compact(
            'days',
            'appointmentsByDay',
            'weekStart',
            'previousWeek',
            'nextWeek',
            'isAdmin',
            'openingHours',
            'medewerkers',
            'selectedMedewerkerId'
        ));
    }

    public function create(Request $request)
    {
        $selectedDate = $request->input('date', now()->format('Y-m-d'));
        $selectedTime = $request->input('time', '09:00');

        $openingHours = [
            'Monday' => ['09:00', '18:00'],
            'Tuesday' => ['09:00', '18:00'],
            'Wednesday' => ['09:00', '18:00'],
            'Thursday' => ['09:00', '18:00'],
            'Friday' => ['09:00', '18:00'],
            'Saturday' => ['09:00', '16:00'],
            'Sunday' => ['closed', 'closed'],
        ];

        $medewerkers = User::role('medewerker')->get();
        $treatments = Pricelist::all();
        $treatmentDurations = $treatments->pluck('duration', 'service')->toArray();

        return view('appointments.create', compact('selectedDate', 'selectedTime', 'openingHours', 'medewerkers', 'treatments', 'treatmentDurations'));
    }

    public function store(Request $request)
    {
        $isAdmin = auth()->user()->hasRole('medewerker');
        $appointmentDateTime = Carbon::parse($request->appointment_date . ' ' . $request->appointment_time);

        $treatment = Pricelist::where('service', $request->service)->first();
        $duration = $treatment ? $treatment->duration : 15;
        $appointmentEnd = $appointmentDateTime->copy()->addMinutes($duration);

        if (!$isAdmin) {
            if (now()->diffInHours($appointmentDateTime) < 12) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['appointment_time' => 'Afspraken moeten minimaal 12 uur van tevoren worden geboekt.']);
            }

            $existingAppointment = Appointment::where('medewerker_id', $request->medewerker_id)
                ->whereIn('status', ['pending', 'confirmed'])
                ->where(function ($query) use ($appointmentDateTime, $appointmentEnd) {
                    $query->where('appointment_date', '<', $appointmentEnd)
                        ->whereRaw('DATE_ADD(appointment_date, INTERVAL duration MINUTE) > ?', [$appointmentDateTime]);
                })
                ->exists();

            if ($existingAppointment) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['appointment_time' => 'Deze kapper is al bezet op deze tijd. Kies een andere tijd of kapper.']);
            }
        }

        $validated = $request->validate([
            'service' => 'required|string|max:255',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'notes' => str_contains(strtolower($request->service), 'anders') ? 'required|string|min:5' : 'nullable|string',
        ]);

        Appointment::create([
            'user_id' => auth()->user()->id,
            'medewerker_id' => $request->medewerker_id,
            'service' => $validated['service'],
            'appointment_date' => $appointmentDateTime,
            'duration' => $duration,
            'notes' => $validated['notes'] ?? null,
            'status' => 'pending',
        ]);

        return redirect()->route('clientagenda')
            ->with('success', 'Afspraak aangevraagd! Wacht op bevestiging.');
    }

    public function edit($id)
    {
        $appointment = Appointment::findOrFail($id);
        $user = auth()->user();

        // Gecorrigeerde role check via Spatie
        $isAdmin = $user->hasRole('medewerker');

        // FIX: Gebruik ->copy() om te voorkomen dat de echte datum van de afspraak overschreven wordt
        $appointmentDate = Carbon::parse($appointment->appointment_date);
        $currentWeek = request()->input('week', $appointmentDate->copy()->startOfWeek()->format('Y-m-d'));

        if ($currentWeek === 'null' || !$currentWeek) {
            $currentWeek = $appointmentDate->copy()->startOfWeek()->format('Y-m-d');
        }

        $openingHours = [
            'Monday' => ['09:00', '18:00'],
            'Tuesday' => ['09:00', '18:00'],
            'Wednesday' => ['09:00', '18:00'],
            'Thursday' => ['09:00', '18:00'],
            'Friday' => ['09:00', '18:00'],
            'Saturday' => ['09:00', '16:00'],
            'Sunday' => ['closed', 'closed'],
        ];

        $medewerkers = User::role('medewerker')->get();
        $treatments = Pricelist::all();
        $treatmentDurations = $treatments->pluck('duration', 'service')->toArray();

        // Admin krijgt direct toegang
        if ($isAdmin) {
            return view('appointments.edit', compact('appointment', 'currentWeek', 'openingHours', 'medewerkers', 'isAdmin', 'treatmentDurations', 'treatments'));
        }

        // Restricties voor klanten
        if ($appointment->user_id !== $user->id) {
            return redirect()->route('clientagenda')->with('error', 'Je kunt alleen je eigen afspraken bewerken.');
        }

        if ($appointment->status !== 'pending') {
            return redirect()->route('clientagenda')->with('error', 'Deze afspraak is al bevestigd of geannuleerd en kan niet meer worden gewijzigd.');
        }

        if (now()->diffInHours($appointmentDate) < 12) {
            return redirect()->route('clientagenda')->with('error', 'Afspraken kunnen alleen 12 uur van tevoren worden gewijzigd. Neem contact op met de salon.');
        }

        return view('appointments.edit', compact('appointment', 'currentWeek', 'openingHours', 'medewerkers', 'isAdmin', 'treatmentDurations', 'treatments'));
    }

    public function update(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);
        $user = auth()->user();
        $isAdmin = $user->hasRole('medewerker'); // Gecorrigeerde check

        $appointmentDateTime = Carbon::parse($request->appointment_date . ' ' . $request->appointment_time);
        $duration = (int) $request->duration;
        $appointmentEnd = $appointmentDateTime->copy()->addMinutes($duration);

        // Als geen admin, voer klantrestricties uit
        if (!$isAdmin) {
            if ($appointment->user_id !== $user->id) {
                return redirect()->route('clientagenda')->with('error', 'Je kunt alleen je eigen afspraken bewerken.');
            }

            if ($appointment->status !== 'pending') {
                return redirect()->route('clientagenda')->with('error', 'Deze afspraak is al bevestigd of geannuleerd.');
            }

            if (now()->diffInHours($appointmentDateTime) < 12) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['appointment_time' => 'Afspraken kunnen alleen 12 uur van tevoren worden gewijzigd.']);
            }

            $existingAppointment = Appointment::where('medewerker_id', $request->medewerker_id)
                ->where('id', '!=', $id)
                ->whereIn('status', ['pending', 'confirmed'])
                ->where(function ($query) use ($appointmentDateTime, $appointmentEnd) {
                    $query->where('appointment_date', '<', $appointmentEnd)
                        ->whereRaw('DATE_ADD(appointment_date, INTERVAL duration MINUTE) > ?', [$appointmentDateTime]);
                })
                ->exists();

            if ($existingAppointment) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['appointment_time' => 'Deze kapper is al bezet op deze tijd. Kies een andere tijd of kapper.']);
            }
        }

        $validated = $request->validate([
            'service' => 'required|string|max:255',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'duration' => 'required|integer|min:5|max:240',
            'notes' => str_contains(strtolower($request->service), 'anders') ? 'required|string|min:1' : 'nullable|string',
            'status' => 'required|in:pending,confirmed,cancelled'
        ]);

        $roundedDuration = max(5, ceil($validated['duration'] / 5) * 5);

        $appointment->update([
            'medewerker_id' => $request->medewerker_id,
            'service' => $validated['service'],
            'appointment_date' => $appointmentDateTime,
            'duration' => $roundedDuration,
            'notes' => $validated['notes'],
            'status' => $validated['status'],
        ]);

        $currentWeek = $request->input('week', now()->startOfWeek()->format('Y-m-d'));

        return redirect()->route('clientagenda', ['week' => $currentWeek])
            ->with('success', 'Afspraak bijgewerkt!');
    }

    public function destroy(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);
        $user = auth()->user();

        // Gecorrigeerde check
        if ($user->hasRole('medewerker')) {
            $appointment->delete();
            $currentWeek = $request->input('week', now()->startOfWeek()->format('Y-m-d'));
            return redirect()->route('clientagenda', ['week' => $currentWeek])
                ->with('success', 'Afspraak succesvol verwijderd!');
        }

        if ($appointment->user_id !== $user->id) {
            return redirect()->route('clientagenda')->with('error', 'Je kunt alleen je eigen afspraken verwijderen.');
        }

        if ($appointment->status !== 'pending') {
            return redirect()->route('clientagenda')->with('error', 'Deze afspraak is al bevestigd of geannuleerd.');
        }

        if (now()->diffInHours(Carbon::parse($appointment->appointment_date)) < 12) {
            return redirect()->route('clientagenda')->with('error', 'Afspraken kunnen alleen 12 uur van tevoren worden geannuleerd. Neem contact op met de salon.');
        }

        $appointment->delete();

        $currentWeek = $request->input('week', now()->startOfWeek()->format('Y-m-d'));

        return redirect()->route('clientagenda', ['week' => $currentWeek])
            ->with('success', 'Afspraak succesvol verwijderd!');
    }
}
