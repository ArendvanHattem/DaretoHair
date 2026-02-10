<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        // Get week parameter or use current week (starting Monday)
        $weekStart = $request->input('week', now()->startOfWeek()->format('Y-m-d'));
        $weekStart = Carbon::parse($weekStart);
        
        // Generate 7 days for the week
        $days = collect();
        for ($i = 0; $i < 7; $i++) {
            $days->push($weekStart->copy()->addDays($i));
        }
        
        // Get appointments for this week
        $appointments = Appointment::with('user')
            ->whereBetween('appointment_date', [
                $weekStart,
                $weekStart->copy()->addWeek()
            ])
            ->orderBy('appointment_date')
            ->get();
        
        // Group appointments by day
        $appointmentsByDay = $appointments->groupBy(function($appointment) {
            return \Carbon\Carbon::parse($appointment->appointment_date)->format('Y-m-d');
        });
        
        // Previous and next week links
        $previousWeek = $weekStart->copy()->subWeek()->format('Y-m-d');
        $nextWeek = $weekStart->copy()->addWeek()->format('Y-m-d');
        
        return view('appointments.index', compact('days', 'appointmentsByDay', 'weekStart', 'previousWeek', 'nextWeek'));
    }
}