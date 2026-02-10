<x-layout>
    <x-slot name="header">
        <h1 class="display-4 fw-bold">Agenda</h1>
        <p class="lead">Week van {{ $weekStart->format('d-m-Y') }}</p>
    </x-slot>
    
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <a href="?week={{ $previousWeek }}" class="btn btn-outline-primary">← Vorige week</a>
            </div>
            <h5 class="mb-0">Week {{ $weekStart->format('W') }}</h5>
            <div>
                <a href="?week={{ $nextWeek }}" class="btn btn-outline-primary">Volgende week →</a>
            </div>
        </div>
        
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 100px;">Tijd</th>
                            @foreach($days as $day)
                                <th class="{{ $day->isToday() ? 'table-primary' : '' }}">
                                    {{ $day->format('l') }}<br>
                                    <small>{{ $day->format('d-m-Y') }}</small>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $timeSlots = [];
                            for ($hour = 9; $hour <= 17; $hour++) {
                                $timeSlots[] = sprintf('%02d:00', $hour);
                                $timeSlots[] = sprintf('%02d:30', $hour);
                            }
                        @endphp
                        
                        @foreach($timeSlots as $time)
                            <tr>
                                <td class="bg-light fw-bold">{{ $time }}</td>
                                @foreach($days as $day)
                                    @php
                                        $dayKey = $day->format('Y-m-d');
                                        $datetime = $day->format('Y-m-d') . ' ' . $time;
                                        $appointmentsAtSlot = $appointmentsByDay[$dayKey] ?? collect();
                                        
                                        // Vind afspraken die op deze tijd starten
                                        $appointment = $appointmentsAtSlot->first(function($app) use ($time) {
                                            $appTime = \Carbon\Carbon::parse($app->appointment_date)->format('H:i');
                                            return $appTime === $time;
                                        });
                                    @endphp
                                    
                                    <td style="height: 60px; vertical-align: middle;" 
                                        class="{{ $appointment ? 'bg-info bg-opacity-25' : '' }}"
                                        rowspan="{{ $appointment ? ceil($appointment->duration / 30) : 1 }}">
                                        
                                        @if($appointment && $appointment->starts_at_slot == $time)
                                            <div class="p-1 border-start border-3 border-primary">
                                                <small class="fw-bold">{{ $appointment->user->name }}</small><br>
                                                <small>{{ $appointment->service }}</small><br>
                                                <span class="badge bg-{{ 
                                                    $appointment->status === 'confirmed' ? 'success' : 
                                                    ($appointment->status === 'pending' ? 'warning' : 'danger') 
                                                }}">
                                                    {{ $appointment->status }}
                                                </span>
                                                <br>
                                                <small>{{ $appointment->duration }} min</small>
                                            </div>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layout>