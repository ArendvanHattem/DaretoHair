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
        
        <div style="background-color: #cda4bb;" class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="d-flex gap-2">
                <a href="?week={{ $previousWeek }}&medewerker_id={{ $selectedMedewerkerId ?? '' }}" class="btn btn-outline-primary">← Vorige week</a>
                
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="background-color: #cda4bb; border: none; color: white;">
                        @php
                            $selectedStylist = $medewerkers->firstWhere('id', $selectedMedewerkerId);
                        @endphp
                        {{ $selectedStylist ? $selectedStylist->name : 'Kies stylist' }}
                    </button>
                    <ul style="background-color: #cda4bb;" class="dropdown-menu">
                        @foreach($medewerkers as $medewerker)
                            <li style="background-color: #cda4bb;"><a class="dropdown-item" href="?medewerker_id={{ $medewerker->id }}&week={{ $weekStart->format('Y-m-d') }}">{{ $medewerker->name }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
            
            <h5 class="mb-0" style="position: absolute; left: 50%; transform: translateX(-50%);">Week {{ $weekStart->format('W') }}</h5>
            
            <div class="d-flex gap-2">
                <a href="{{ route('appointments.create') }}" class="btn btn-success me-2">+ Nieuwe afspraak</a>
                <a href="?week={{ $nextWeek }}&medewerker_id={{ $selectedMedewerkerId ?? '' }}" class="btn btn-outline-primary">Volgende week →</a>
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
                    .appointment-block.bevestigd {
                        background-color: #d1e7dd;
                        border-left-color: #198754;
                    }
                    .appointment-block.afwachting {
                        background-color: #fff3cd;
                        border-left-color: #ffc107;
                    }
                    .appointment-block.geannuleerd {
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

                    .dropdown-menu .dropdown-item:hover {
                        background-color: #9d5b7e !important;
                        color: white !important;
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
                                                            
                                                            <span class="medewerker-name" style="font-size: 0.55rem; opacity: 0.7;">{{ $appt->medewerker->name ?? '' }}</span>
                                                            
                                                            @if($isAdmin || $appt->user_id == auth()->id())
                                                                <span class="client-name">{{ $appt->user->name ?? 'Klant' }}</span>
                                                            @endif
                                                            
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
                                <span class="detail-value">
                                    @if($isAdmin || $appt->klant_id == auth()->id())
                                        {{ $appt->klant->name ?? 'Onbekend' }}
                                    @else
                                        Verborgen
                                    @endif
                                </span>
                            </div>

                            <div class="detail-row">
                                <span class="detail-label">Kapper:</span>
                                <span class="detail-value">{{ $appt->medewerker->name ?? 'Nog niet toegewezen' }}</span>
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
                                        $appt->status === 'bevestigd' ? '#d1e7dd' : 
                                        ($appt->status === 'in afwachting' ? '#fff3cd' : '#f8d7da') 
                                    }}; color: {{ 
                                        $appt->status === 'bevestigd' ? '#0a3622' : 
                                        ($appt->status === 'in afwachting' ? '#856404' : '#58151c') 
                                    }};">
                                        {{ $appt->status === 'geannuleerd' ? 'Geannuleerd' : ucfirst($appt->status) }}
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
                            @if($isAdmin || ($appt->user_id == auth()->id() && $appt->status == 'in afwachting'))
                                <form method="POST" action="{{ route('appointments.destroy', ['id' => $appt->id, 'week' => $weekStart->format('Y-m-d')]) }}" 
                                    style="display: inline;" 
                                    onsubmit="return handleDeleteSubmit(event, {{ $appt->id }}, '{{ $appt->appointment_date }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" id="deleteBtn{{ $appt->id }}">Verwijderen</button>
                                </form>
                            @else
                                <div></div>
                            @endif
                            
                            <div>
                                @if($isAdmin || ($appt->user_id == auth()->id() && $appt->status == 'in afwachting'))
                                    <a href="#" class="btn btn-success" onclick="return checkEditTime(event, {{ $appt->id }}, '{{ $appt->appointment_date }}', '{{ $appt->status }}')">Bewerken</a>
                                @endif
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Sluiten</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endforeach
    
    <script>
    function showContactMessage(action) {
        const message = `⚠️ Afspraken binnen 12 uur kunnen niet via de website verwijderd of aangepast worden.\n\nNeem hiervoor contact op met de salon:\n📧 salon@daretohair.nl\n📞 06 12345678\n\nBinnen onze openingstijden wordt u zo snel mogelijk geholpen.`;
        
        const modalHtml = `
            <div id="warningModal" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 10000; display: flex; align-items: center; justify-content: center;">
                <div style="background: white; border-radius: 16px; padding: 24px; max-width: 400px; margin: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.3);">
                    <div style="font-size: 48px; text-align: center; margin-bottom: 16px;">⚠️</div>
                    <p style="margin-bottom: 20px; line-height: 1.5; white-space: pre-line;">${message}</p>
                    <button onclick="closeWarningModal()" style="background: #0d6efd; color: white; border: none; padding: 10px 24px; border-radius: 8px; cursor: pointer; width: 100%; font-size: 16px;">OK</button>
                </div>
            </div>
        `;
        
        document.body.insertAdjacentHTML('beforeend', modalHtml);
        return false;
    }

    function closeWarningModal() {
        const modal = document.getElementById('warningModal');
        if (modal) modal.remove();
    }

    function isWithin12Hours(appointmentDate) {
        const appointmentTime = new Date(appointmentDate);
        const now = new Date();
        const hoursDiff = (appointmentTime - now) / (1000 * 60 * 60);
        return hoursDiff < 12;
    }

    function handleDeleteSubmit(event, appointmentId, appointmentDate) {
        const isAdmin = {{ auth()->user()->hasRole('medewerker') ? 'true' : 'false' }};
        if (isAdmin) {
            return confirm('Weet je zeker dat je deze afspraak wilt verwijderen?');
        }
        
        if (isWithin12Hours(appointmentDate)) {
            event.preventDefault();
            return showContactMessage('verwijderd');
        }
        return confirm('Weet je zeker dat je deze afspraak wilt verwijderen?');
    }

    function checkEditTime(event, appointmentId, appointmentDate, status) {
        event.preventDefault();
        
        // Admin can always edit
            const isAdmin = {{ auth()->user()->hasRole('medewerker') ? 'true' : 'false' }};        if (isAdmin) {
            window.location.href = '/afspraak-maken/' + appointmentId + '/edit?week=' + new URLSearchParams(window.location.search).get('week');
            return false;
        }
        
        if (status !== 'in afwachting') {
            alert('Deze afspraak is al bevestigd of geannuleerd en kan niet meer worden gewijzigd.');
            return false;
        }
        
        if (isWithin12Hours(appointmentDate)) {
            return showContactMessage('verplaatst');
        }
        
        window.location.href = '/afspraak-maken/' + appointmentId + '/edit?week=' + new URLSearchParams(window.location.search).get('week');
        return false;
    }
    function deleteAppointment(id) {
        if (confirm('Weet je zeker dat je deze afspraak wilt verwijderen?')) {
            fetch('/afspraak-maken/' + id, {
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