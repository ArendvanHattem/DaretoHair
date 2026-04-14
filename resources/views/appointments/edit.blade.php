<x-layout>
    <x-slot name="header">
        <h1 class="display-4 fw-bold">Afspraak bewerken</h1>
    </x-slot>
    
    <div class="card">
        <div style="background-color: #cda4bb;" class="card-header">
            <h5 class="mb-0">Wijzig afspraak gegevens</h5>
        </div>
        
        <div class="card-body">
            <form method="POST" action="{{ route('appointments.update', $appointment->id) }}">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label class="form-label">Klant</label>
                    <input type="text" class="form-control" value="{{ $appointment->user->name ?? 'Onbekend' }}" disabled>
                </div>
                
                <!-- Service: Dropdown for clients, text input for admin -->
                <div class="mb-3">
                    <label for="service" class="form-label">Behandeling</label>
                    @if(auth()->user()->role === 'admin')
                        <input type="text" class="form-control" id="service" name="service" 
                               value="{{ old('service', $appointment->service) }}" required>
                    @else
                        <select class="form-control" id="service" name="service" required>
                            <option value="Knippen" {{ $appointment->service == 'Knippen' ? 'selected' : '' }}>Knippen (30 min)</option>
                            <option value="Knippen + Föhnen" {{ $appointment->service == 'Knippen + Föhnen' ? 'selected' : '' }}>Knippen + Föhnen (45 min)</option>
                            <option value="Kleuring" {{ $appointment->service == 'Kleuring' ? 'selected' : '' }}>Kleuring (60 min)</option>
                            <option value="Kleuring + Knippen" {{ $appointment->service == 'Kleuring + Knippen' ? 'selected' : '' }}>Kleuring + Knippen (90 min)</option>
                            <option value="Highlights" {{ $appointment->service == 'Highlights' ? 'selected' : '' }}>Highlights (75 min)</option>
                            <option value="Permanent" {{ $appointment->service == 'Permanent' ? 'selected' : '' }}>Permanent (90 min)</option>
                            <option value="Baard trimmen" {{ $appointment->service == 'Baard trimmen' ? 'selected' : '' }}>Baard trimmen (15 min)</option>
                            <option value="Wassen + Föhnen" {{ $appointment->service == 'Wassen + Föhnen' ? 'selected' : '' }}>Wassen + Föhnen (20 min)</option>
                            <option value="Anders" {{ $appointment->service == 'Anders' ? 'selected' : '' }}>Anders (15 min)</option>
                        </select>
                    @endif
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="appointment_date" class="form-label">Datum</label>
                        <input type="date" class="form-control" id="appointment_date" name="appointment_date" 
                               value="{{ old('appointment_date', $appointment->appointment_date->format('Y-m-d')) }}" 
                               min="{{ now()->addDay()->format('Y-m-d') }}" required>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="appointment_time" class="form-label">Tijd</label>
                        <select class="form-control" id="appointment_time" name="appointment_time" required>
                            <!-- Will be populated by JavaScript -->
                        </select>
                    </div>
                </div>
                
                <!-- Duration: Auto-calculated from service, hidden for clients -->
                <input type="hidden" name="duration" id="duration" value="{{ $appointment->duration }}">
                
                <!-- Status: Only admin can see/change -->
                @if(auth()->user()->role === 'admin')
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="pending" {{ $appointment->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ $appointment->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="cancelled" {{ $appointment->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                @else
                    <input type="hidden" name="status" value="{{ $appointment->status }}">
                @endif
                
                <div class="mb-3">
                    <label for="notes" class="form-label">Notities</label>
                    <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes', $appointment->notes) }}</textarea>
                </div>
                
                <div class="d-flex justify-content-between">
                    <a href="{{ route('clientagenda', ['week' => $currentWeek ?? $appointment->appointment_date->startOfWeek()->format('Y-m-d')]) }}" class="btn btn-secondary">Annuleren</a>
                    <button type="submit" class="btn btn-primary">Opslaan</button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
    // Duration mapping for clients
    const durationMap = {
        'Knippen': 30,
        'Knippen + Föhnen': 45,
        'Kleuring': 60,
        'Kleuring + Knippen': 90,
        'Highlights': 75,
        'Permanent': 90,
        'Baard trimmen': 15,
        'Wassen + Föhnen': 20,
        'Anders': 15
    };

    // Update duration when service changes (for clients)
    document.getElementById('service')?.addEventListener('change', function() {
        const duration = durationMap[this.value] || 30;
        document.getElementById('duration').value = duration;
    });

    // Time slot generation (same as create form)
    const openingHours = {
        'Monday': ['09:00', '18:00'],
        'Tuesday': ['09:00', '18:00'],
        'Wednesday': ['09:00', '18:00'],
        'Thursday': ['09:00', '18:00'],
        'Friday': ['09:00', '18:00'],
        'Saturday': ['09:00', '16:00'],
        'Sunday': ['closed', 'closed']
    };

    function updateTimeOptions() {
        const dateInput = document.getElementById('appointment_date');
        const timeSelect = document.getElementById('appointment_time');
        const service = document.getElementById('service')?.value;
        
        if (!dateInput.value) return;
        
        const selectedDate = new Date(dateInput.value);
        const dayName = selectedDate.toLocaleDateString('nl-NL', { weekday: 'long' });
        
        const dayMap = {
            'maandag': 'Monday',
            'dinsdag': 'Tuesday',
            'woensdag': 'Wednesday',
            'donderdag': 'Thursday',
            'vrijdag': 'Friday',
            'zaterdag': 'Saturday',
            'zondag': 'Sunday'
        };
        
        const hours = openingHours[dayMap[dayName]];
        
        if (!hours || hours[0] === 'closed') {
            timeSelect.innerHTML = '<option value="">Gesloten</option>';
            return;
        }
        
        const duration = durationMap[service] || 30;
        const startHour = parseInt(hours[0].split(':')[0]);
        const startMinute = parseInt(hours[0].split(':')[1]);
        const endHour = parseInt(hours[1].split(':')[0]);
        const endMinute = parseInt(hours[1].split(':')[1]);
        
        let endTotalMinutes = (endHour * 60 + endMinute) - duration;
        
        let options = '';
        let currentHour = startHour;
        let currentMinute = startMinute;
        
        while ((currentHour * 60 + currentMinute) <= endTotalMinutes) {
            const timeString = `${currentHour.toString().padStart(2, '0')}:${currentMinute.toString().padStart(2, '0')}`;
            const selected = timeString === '{{ $appointment->appointment_date->format('H:i') }}' ? 'selected' : '';
            options += `<option value="${timeString}" ${selected}>${timeString}</option>`;
            
            currentMinute += 5;
            if (currentMinute >= 60) {
                currentMinute = 0;
                currentHour++;
            }
        }
        
        timeSelect.innerHTML = options || '<option value="">Geen tijden</option>';
    }

    document.getElementById('appointment_date')?.addEventListener('change', updateTimeOptions);
    document.getElementById('service')?.addEventListener('change', updateTimeOptions);

    if (document.getElementById('appointment_date').value) {
        updateTimeOptions();
    }
    </script>
</x-layout>