<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Hairdresser;

class ClientAgendaController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('clientsignup');
        }

        $isAdmin = auth()->user()->role === 'admin';
        $selectedHairdresserId = $request->input('hairdresser_id');

        // Get all active hairdressers for the filter dropdown
        $hairdressers = Hairdresser::where('is_active', true)->get();

        // Auto-select first hairdresser if none selected and hairdressers exist
        if (!$selectedHairdresserId && $hairdressers->count() > 0) {
            $selectedHairdresserId = $hairdressers->first()->id;
        }

        $weekStart = $request->input('week', now()->startOfWeek()->format('Y-m-d'));
        $weekStart = Carbon::parse($weekStart);
        
        $days = collect();
        for ($i = 0; $i < 7; $i++) {
            $days->push($weekStart->copy()->addDays($i));
        }
        
        // Filter appointments by hairdresser if selected
        $appointmentsQuery = Appointment::with($isAdmin ? 'user' : [])
            ->whereBetween('appointment_date', [
                $weekStart,
                $weekStart->copy()->addWeek()
            ]);
        
        if ($selectedHairdresserId) {
            $appointmentsQuery->where('hairdresser_id', $selectedHairdresserId);
        }
        
        $appointments = $appointmentsQuery->orderBy('appointment_date')->get();
        
        $appointmentsByDay = $appointments->groupBy(function($appointment) {
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
            'hairdressers',
            'selectedHairdresserId'
        ));
    }

    public function create(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('clientsignup');
        }
        
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
        
        $hairdressers = Hairdresser::where('is_active', true)->get();
        
        return view('appointments.create', compact('selectedDate', 'selectedTime', 'openingHours', 'hairdressers'));
    }

    public function store(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('clientsignup');
        }
        
        $appointmentDateTime = Carbon::parse($request->appointment_date . ' ' . $request->appointment_time);
        
        // Duration mapping
        $durationMap = [
            'Knippen' => 30,
            'Knippen + Föhnen' => 45,
            'Kleuring' => 60,
            'Kleuring + Knippen' => 90,
            'Highlights' => 75,
            'Permanent' => 90,
            'Baard trimmen' => 15,
            'Wassen + Föhnen' => 20,
            'Anders' => 15,
        ];
        
        $duration = $durationMap[$request->service] ?? 15;
        $appointmentEnd = $appointmentDateTime->copy()->addMinutes($duration);
        
        // 12-hour rule for appointments
        if (now()->diffInHours($appointmentDateTime) < 12) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['appointment_time' => 'Afspraken moeten minimaal 12 uur van tevoren worden geboekt.']);
        }
        
        // Check for overlapping appointments with same hairdresser
        $existingAppointment = Appointment::where('hairdresser_id', $request->hairdresser_id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where(function($query) use ($appointmentDateTime, $appointmentEnd) {
                $query->where('appointment_date', '<', $appointmentEnd)
                    ->whereRaw('DATE_ADD(appointment_date, INTERVAL duration MINUTE) > ?', [$appointmentDateTime]);
            })
            ->exists();
        
        if ($existingAppointment) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['appointment_time' => 'Deze kapper is al bezet op deze tijd. Kies een andere tijd of kapper.']);
        }
        
        $validated = $request->validate([
            'service' => 'required|string|max:255',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'notes' => $request->service === 'Anders' ? 'required|string|min:5' : 'nullable|string',
        ]);
        
        Appointment::create([
            'user_id' => auth()->user()->id,
            'hairdresser_id' => $request->hairdresser_id,
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
        $currentWeek = request()->input('week', $appointment->appointment_date->startOfWeek()->format('Y-m-d'));
        
        // Prevent "null" string from being passed
        if ($currentWeek === 'null') {
            $currentWeek = $appointment->appointment_date->startOfWeek()->format('Y-m-d');
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
        
        // Get hairdressers for the dropdown
        $hairdressers = Hairdresser::where('is_active', true)->get();
        
        // Admin can always edit
        if ($user->role === 'admin') {
            return view('appointments.edit', compact('appointment', 'currentWeek', 'openingHours', 'hairdressers'));
        }
        
        // Client restrictions
        if ($appointment->user_id !== $user->id) {
            return redirect()->route('clientagenda')->with('error', 'Je kunt alleen je eigen afspraken bewerken.');
        }
        
        if ($appointment->status !== 'pending') {
            return redirect()->route('clientagenda')->with('error', 'Deze afspraak is al bevestigd of geannuleerd en kan niet meer worden gewijzigd.');
        }
        
        if (now()->diffInHours($appointment->appointment_date) < 12) {
            return redirect()->route('clientagenda')->with('error', 'Afspraken kunnen alleen 12 uur van tevoren worden gewijzigd. Neem contact op met de salon.');
        }
        
        return view('appointments.edit', compact('appointment', 'currentWeek', 'openingHours', 'hairdressers'));
    }

    public function update(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);
        $user = auth()->user();
        
        $appointmentDateTime = Carbon::parse($request->appointment_date . ' ' . $request->appointment_time);
        
        // Get duration from request or use existing
        $duration = (int) $request->duration;
        $appointmentEnd = $appointmentDateTime->copy()->addMinutes($duration);
        
        // Admin can always update
        if ($user->role !== 'admin') {
            // Client restrictions
            if ($appointment->user_id !== $user->id) {
                return redirect()->route('clientagenda')->with('error', 'Je kunt alleen je eigen afspraken bewerken.');
            }
            
            if ($appointment->status !== 'pending') {
                return redirect()->route('clientagenda')->with('error', 'Deze afspraak is al bevestigd of geannuleerd.');
            }

            // 12-hour rule for appointments
            if (now()->diffInHours($appointmentDateTime) < 12) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['appointment_time' => 'Afspraken kunnen alleen 12 uur van tevoren worden gewijzigd.']);
            }
        }
        
        // Check for overlapping appointments with same hairdresser (excluding current appointment)
        $existingAppointment = Appointment::where('hairdresser_id', $request->hairdresser_id)
            ->where('id', '!=', $id)
            ->whereIn('status', ['pending', 'confirmed'])  
            ->where(function($query) use ($appointmentDateTime, $appointmentEnd) {
                $query->where('appointment_date', '<', $appointmentEnd)
                    ->whereRaw('DATE_ADD(appointment_date, INTERVAL duration MINUTE) > ?', [$appointmentDateTime]);
            })
            ->exists();

        if ($existingAppointment) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['appointment_time' => 'Deze kapper is al bezet op deze tijd. Kies een andere tijd of kapper.']);
        }
        
        $validated = $request->validate([
            'service' => 'required|string|max:255',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'duration' => 'required|integer|min:5|max:240',
            'notes' => 'nullable|string',
            'status' => 'required|in:pending,confirmed,cancelled'
        ]);
        
        $roundedDuration = max(5, ceil($validated['duration'] / 5) * 5);
        
        $appointment->update([
            'hairdresser_id' => $request->hairdresser_id,  // Add this line
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
        
        // Admin can always delete
        if ($user->role !== 'admin') {
            // Client restrictions
            if ($appointment->user_id !== $user->id) {
                return redirect()->route('clientagenda')->with('error', 'Je kunt alleen je eigen afspraken verwijderen.');
            }
            
            if ($appointment->status !== 'pending') {
                return redirect()->route('clientagenda')->with('error', 'Deze afspraak is al bevestigd of geannuleerd.');
            }
            
            if (now()->diffInHours($appointment->appointment_date) < 12) {
                return redirect()->route('clientagenda')->with('error', 'Afspraken kunnen alleen 12 uur van tevoren worden geannuleerd. Neem contact op met de salon.');
            }
        }
        
        $appointment->delete();
        
        $currentWeek = $request->input('week', now()->startOfWeek()->format('Y-m-d'));
        
        return redirect()->route('clientagenda', ['week' => $currentWeek])
            ->with('success', 'Afspraak succesvol verwijderd!');
    }
}