<x-layout>
    <x-slot name="header">
        <h1 class="display-4 fw-bold">Agenda</h1>
        <p class="lead">Week van {{ $weekStart->format('d-m-Y') }}</p>
    </x-slot>
    
    <div class="card">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        <div style="background-color: #cda4bb;" class="card-header d-flex justify-content-between align-items-center">
            <div>
                <a href="?week={{ $previousWeek }}" class="btn btn-outline-primary">← Vorige week</a>
            </div>
            <h5 class="mb-0" style="position: absolute; left: 50%; transform: translateX(-50%);">Week {{ $weekStart->format('W') }}</h5>
            <div>
                <a href="{{ route('appointments.create') }}" class="btn btn-success me-2">+ Nieuwe afspraak</a>
                <a href="?week={{ $nextWeek }}" class="btn btn-outline-primary">Volgende week →</a>
            </div>
        </div>
        
        <div class="card-body" style="background-color: #b5838d;">
            <div class="table-responsive">
                @php
                    $openHour = 9;
                    $closeHour = 18;
                @endphp

                <style>
                    .agenda-table {
                        table-layout: fixed;
                        width: 100%;
                        border-collapse: collapse;
                    }
                    .agenda-table th {
                        text-align: center;
                        background-color: #9d5b7e;
                        color: black;
                        font-size: 0.9rem;
                        padding: 8px;
                        border: 1px solid #dee2e6;
                    }
                    .agenda-table td {
                        padding: 0;
                        border: 1px solid #dee2e6;
                    }
                    
                    .time-cell {
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        height: 100%;
                        font-size: 0.85rem;
                        font-weight: bold;
                        background-color: #cda4bb;
                        color: #000;
                    }
                    
                    .appointment-block {
                        background-color: #cfe2ff;
                        border-left: 3px solid #0d6efd;
                        border-radius: 3px;
                        padding: 1px 3px;
                        font-size: 0.6rem;
                        overflow: hidden;
                        box-shadow: 0 1px 2px rgba(0,0,0,0.1);
                        cursor: pointer;
                        transition: all 0.2s;
                        display: flex;
                        flex-direction: column;
                        line-height: 1.1;
                    }
                    .appointment-block:hover {
                        transform: scale(1.02);
                        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
                        z-index: 20;
                    }
                    .appointment-block.confirmed {
                        background-color: #d1e7dd;
                        border-left-color: #198754;
                    }
                    .appointment-block.pending {
                        background-color: #fff3cd;
                        border-left-color: #ffc107;
                    }
                    .appointment-block.cancelled {
                        background-color: #f8d7da;
                        border-left-color: #dc3545;
                        opacity: 0.7;
                    }
                    .appointment-block .client-name {
                        font-weight: bold;
                        font-size: 0.6rem;
                        white-space: nowrap;
                        overflow: hidden;
                        text-overflow: ellipsis;
                    }

                    .appointment-block .service-name {
                        font-size: 0.55rem;
                        white-space: nowrap;
                        overflow: hidden;
                        text-overflow: ellipsis;
                        opacity: 0.9;
                    }
                    
                    .modal-dialog {
                        max-width: 600px;
                        margin: 1.75rem auto;
                    }
                    .modal-content {
                        border-radius: 16px;
                        border: none;
                        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
                    }
                    .modal-header {
                        padding: 1.25rem 1.5rem;
                        background-color: #f8f9fa;
                        border-bottom: 2px solid #dee2e6;
                        border-radius: 16px 16px 0 0;
                    }
                    .modal-header h6 {
                        font-size: 1.25rem;
                        font-weight: 600;
                        margin: 0;
                    }
                    .modal-header .btn-close {
                        transform: scale(1.2);
                    }
                    .modal-body {
                        padding: 1.5rem;
                    }
                    .modal-footer {
                        padding: 1.25rem 1.5rem;
                        border-top: 2px solid #dee2e6;
                    }
                    .modal-footer .btn {
                        padding: 0.5rem 1.25rem;
                        font-size: 1rem;
                    }
                    
                    .detail-row {
                        display: flex;
                        margin-bottom: 1rem;
                        align-items: flex-start;
                    }
                    .detail-label {
                        font-weight: 600;
                        color: #495057;
                        width: 120px;
                        font-size: 1rem;
                    }
                    .detail-value {
                        flex: 1;
                        font-size: 1rem;
                        line-height: 1.5;
                    }
                    .status-badge {
                        display: inline-block;
                        padding: 0.35rem 0.85rem;
                        border-radius: 20px;
                        font-size: 0.9rem;
                        font-weight: 500;
                    }

                    .today-highlight {
                        background-color: #ae005d !important;
                        color: #dee2e6 !important;
                    }
                </style>

                <table class="table agenda-table">
                    <thead>
                        <tr>
                            <th style="width: 100px;">Tijd</th>
                            @foreach($days as $day)
                                <th class="{{ $day->isToday() ? 'today-highlight' : '' }}">
                                    {{ $day->format('l') }}<br>
                                    <small>{{ $day->format('d-m') }}</small>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @for($hour = $openHour; $hour < $closeHour; $hour++)
                            @for($block = 0; $block < 12; $block++)
                                @php
                                    $minutes = $block * 5;
                                @endphp
                                <tr>
                                    @if($minutes === 0)
                                        <td style="height: 48px; padding: 0; border-top: 2px solid #000000;" rowspan="12">
                                            <div class="time-cell" style="height: 48px;">
                                                {{ sprintf('%02d:00', $hour) }} - {{ sprintf('%02d:00', $hour + 1) }}
                                            </div>
                                        </td>
                                    @endif
                                    
                                    @foreach($days as $day)
                                        @php
                                            $dayKey = $day->format('Y-m-d');
                                            $blockStart = $day->copy()->setTime($hour, $minutes, 0);
                                        @endphp
                                        
                                        <td style="height: 4px; position: relative; padding: 0; {{ $minutes === 0 ? 'border-top: 2px solid #000000;' : '' }}">
                                            
                                            @foreach($appointmentsByDay[$dayKey] ?? [] as $appt)
                                                @php
                                                    $apptStart = $appt->appointment_date;
                                                    $apptEnd = $apptStart->copy()->addMinutes($appt->duration);
                                                    
                                                    $roundedMinute = floor($apptStart->minute / 5) * 5;
                                                    $roundedStart = $apptStart->copy()->setTime($apptStart->hour, $roundedMinute, 0);
                                                    $roundedDuration = ceil($appt->duration / 5) * 5;
                                                    $roundedEnd = $roundedStart->copy()->addMinutes($roundedDuration);
                                                @endphp
                                                
                                                @if($roundedStart <= $blockStart && $roundedEnd > $blockStart)
                                                    @php
                                                        $isStartBlock = ($roundedStart->format('H:i') === $blockStart->format('H:i'));
                                                    @endphp
                                                    
                                                    @if($isStartBlock)
                                                        @php
                                                            $blocksNeeded = ceil($roundedDuration / 5);
                                                            $height = $blocksNeeded * 4;
                                                        @endphp
                                                        
                                                        <div class="appointment-block {{ $appt->status }}"
                                                             style="position: absolute; top: 0; left: 2px; right: 2px; height: {{ $height }}px; z-index: 10;"
                                                             data-bs-toggle="modal"
                                                             data-bs-target="#appointmentModal{{ $appt->id }}"
                                                             title="{{ $apptStart->format('H:i') }} - {{ $apptEnd->format('H:i') }}">
                                                            
                                                            <span class="client-name">{{ $appt->user->name ?? 'Klant' }}</span>
                                                            @if($appt->duration >= 30)
                                                                <span class="service-name">{{ $appt->service }}</span>
                                                            @endif
                                                        </div>
                                                        
                                                        @php
                                                            for($i = 1; $i < $blocksNeeded; $i++) {
                                                                $nextBlock = $block + $i;
                                                                $nextHour = $hour;
                                                                $nextMinutes = $minutes + ($i * 5);
                                                                if($nextMinutes >= 60) {
                                                                    $nextHour += floor($nextMinutes / 60);
                                                                    $nextMinutes = $nextMinutes % 60;
                                                                }
                                                                $occupiedKey = $dayKey . '_' . $nextHour . '_' . floor($nextMinutes / 5);
                                                                $GLOBALS['occupied_cells'][$occupiedKey] = true;
                                                            }
                                                        @endphp
                                                    @endif
                                                @endif
                                            @endforeach
                                        </td>
                                    @endforeach
                                </tr>
                            @endfor
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Modals -->
    @foreach($appointmentsByDay as $dayAppointments)
        @foreach($dayAppointments as $appt)
            @php
                $apptStart = $appt->appointment_date;
                $apptEnd = $apptStart->copy()->addMinutes($appt->duration);
            @endphp
            <div class="modal fade" id="appointmentModal{{ $appt->id }}" tabindex="-1" aria-labelledby="modalLabel{{ $appt->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6 class="modal-title" id="modalLabel{{ $appt->id }}">
                                <i class="bi bi-calendar-check me-2"></i>Afspraak details
                            </h6>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Sluiten"></button>
                        </div>
                        <div class="modal-body">
                            <div class="detail-row">
                                <span class="detail-label">Klant:</span>
                                <span class="detail-value">{{ $appt->user->name ?? 'Onbekend' }}</span>
                            </div>
                            
                            <div class="detail-row">
                                <span class="detail-label">Service:</span>
                                <span class="detail-value">{{ $appt->service }}</span>
                            </div>
                            
                            <div class="detail-row">
                                <span class="detail-label">Datum:</span>
                                <span class="detail-value">{{ $apptStart->format('l d-m-Y') }}</span>
                            </div>
                            
                            <div class="detail-row">
                                <span class="detail-label">Tijd:</span>
                                <span class="detail-value">{{ $apptStart->format('H:i') }} - {{ $apptEnd->format('H:i') }} ({{ $appt->duration }} min)</span>
                            </div>
                            
                            <div class="detail-row">
                                <span class="detail-label">Status:</span>
                                <span class="detail-value">
                                    <span class="status-badge" style="background-color: {{ 
                                        $appt->status === 'confirmed' ? '#d1e7dd' : 
                                        ($appt->status === 'pending' ? '#fff3cd' : '#f8d7da') 
                                    }}; color: {{ 
                                        $appt->status === 'confirmed' ? '#0a3622' : 
                                        ($appt->status === 'pending' ? '#856404' : '#58151c') 
                                    }};">
                                        {{ ucfirst($appt->status) }}
                                    </span>
                                </span>
                            </div>
                            
                            @if($appt->notes)
                                <div class="detail-row">
                                    <span class="detail-label">Notities:</span>
                                    <span class="detail-value">{{ $appt->notes }}</span>
                                </div>
                            @endif
                        </div>
                            <div class="modal-footer d-flex justify-content-between">
                                @if($isAdmin || ($appt->user_id == auth()->id() && $appt->status == 'pending' && now()->diffInHours($appt->appointment_date, false) >= 24))
                                    <form method="POST" action="{{ route('appointments.destroy', ['id' => $appt->id, 'week' => $weekStart->format('Y-m-d')]) }}" style="display: inline;" onsubmit="return confirm('Weet je zeker dat je deze afspraak wilt verwijderen?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Verwijderen</button>
                                    </form>
                                @else
                                    <div></div> <!-- Empty div to maintain spacing -->
                                @endif
                                
                                <div>
                                    @if($isAdmin || ($appt->user_id == auth()->id() && $appt->status == 'pending' && now()->diffInHours($appt->appointment_date, false) >= 24))
                                        <a href="{{ route('appointments.edit', $appt->id) }}" class="btn btn-success">Bewerken</a>
                                    @endif
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Sluiten</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endforeach
    
    <script>
    function deleteAppointment(id) {
        if (confirm('Weet je zeker dat je deze afspraak wilt verwijderen?')) {
            fetch('/clientagenda/' + id, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            }).then(response => {
                if (response.ok) {
                    window.location.reload();
                } else {
                    return response.text().then(text => {
                        console.log('Error:', text);
                        alert('Er is iets misgegaan. Probeer opnieuw.');
                    });
                }
            }).catch(error => {
                console.error('Error:', error);
                alert('Er is iets misgegaan. Probeer opnieuw.');
            });
        }
    }

    // Rest of your existing scroll code...
    let scrollPosition = 0;
    window.addEventListener('scroll', function() {
        scrollPosition = window.scrollY;
    });
    window.addEventListener('beforeunload', function() {
        sessionStorage.setItem('agenda_scroll', scrollPosition);
    });
    document.addEventListener('DOMContentLoaded', function() {
        const saved = sessionStorage.getItem('agenda_scroll');
        if (saved) {
            window.scrollTo(0, parseInt(saved));
            sessionStorage.removeItem('agenda_scroll');
        }
    });
    </script>
</x-layout>