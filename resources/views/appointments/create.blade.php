<x-layout>
    <x-slot name="header">
        <h1 class="display-4 fw-bold">Nieuwe afspraak</h1>
    </x-slot>
    
    <div class="card">
        <div style="background-color: #cda4bb;" class="card-header">
            <h5 class="mb-0">Afspraak gegevens</h5>
        </div>
        
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            
            <form method="POST" action="{{ route('appointments.store') }}">
                @csrf
                
                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                
                <div class="mb-3">
                    <label for="service" class="form-label">Behandeling</label>
                    <select class="form-control @error('service') is-invalid @enderror" id="service" name="service" required>
                        <option value="">Selecteer een behandeling</option>
                        <option value="Knippen" {{ old('service') == 'Knippen' ? 'selected' : '' }}>Knippen (standaard 30 min)</option>
                        <option value="Knippen + Föhnen" {{ old('service') == 'Knippen + Föhnen' ? 'selected' : '' }}>Knippen + Föhnen (standaard 45 min)</option>
                        <option value="Kleuring" {{ old('service') == 'Kleuring' ? 'selected' : '' }}>Kleuring (standaard 60 min)</option>
                        <option value="Kleuring + Knippen" {{ old('service') == 'Kleuring + Knippen' ? 'selected' : '' }}>Kleuring + Knippen (standaard 90 min)</option>
                        <option value="Highlights" {{ old('service') == 'Highlights' ? 'selected' : '' }}>Highlights (standaard 75 min)</option>
                        <option value="Permanent" {{ old('service') == 'Permanent' ? 'selected' : '' }}>Permanent (standaard 90 min)</option>
                        <option value="Baard trimmen" {{ old('service') == 'Baard trimmen' ? 'selected' : '' }}>Baard trimmen (standaard 15 min)</option>
                        <option value="Wassen + Föhnen" {{ old('service') == 'Wassen + Föhnen' ? 'selected' : '' }}>Wassen + Föhnen (standaard 20 min)</option>
                        <option value="Anders" {{ old('service') == 'Anders' ? 'selected' : '' }}>Anders (laat gewenste duur weten in notities, standaard 15 min)</option>
                    </select>
                    @error('service')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="appointment_date" class="form-label">Datum</label>
                       <input type="date" class="form-control @error('appointment_date') is-invalid @enderror" 
                            id="appointment_date" name="appointment_date" 
                            value="{{ old('appointment_date', $selectedDate) }}" 
                            min="{{ now()->format('Y-m-d') }}" required>
                        @error('appointment_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="appointment_time" class="form-label">Tijd</label>
                        <select class="form-control @error('appointment_time') is-invalid @enderror" 
                                id="appointment_time" name="appointment_time" required>
                            <option value="">Selecteer tijd</option>
                            @php
                                // This will be populated by JavaScript based on selected date
                            @endphp
                        </select>
                        @error('appointment_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="notes" class="form-label">Notities (optioneel)</label>
                    <textarea class="form-control @error('notes') is-invalid @enderror" 
                            id="notes" name="notes" rows="3" 
                            placeholder="Bijvoorbeeld: Gewenste tijdsduur, speciale wensen, etc.">{{ old('notes') }}</textarea>
                    @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="d-flex justify-content-between">
                    <a href="{{ route('clientagenda') }}" class="btn btn-secondary">Annuleren</a>
                    <button type="submit" class="btn btn-success">Afspraak aanvragen</button>
                </div>
            </form>
        </div>
    </div>
    <script>
    // Opening hours based on day of week
    const openingHours = @json($openingHours);

    // Treatment durations in minutes
    const treatmentDurations = {
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

    function generateTimeSlots(selectedDate, selectedTreatment = null) {
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
            return [];
        }
        
        const startHour = parseInt(hours[0].split(':')[0]);
        const startMinute = parseInt(hours[0].split(':')[1]);
        const endHour = parseInt(hours[1].split(':')[0]);
        const endMinute = parseInt(hours[1].split(':')[1]);
        
        // If treatment selected, calculate last possible start time
        let endTotalMinutes = (endHour * 60 + endMinute);
        
        if (selectedTreatment && treatmentDurations[selectedTreatment]) {
            endTotalMinutes = endTotalMinutes - treatmentDurations[selectedTreatment];
        }
        
        let slots = [];
        let currentHour = startHour;
        let currentMinute = startMinute;
        
        while ((currentHour * 60 + currentMinute) <= endTotalMinutes) {
            const timeString = `${currentHour.toString().padStart(2, '0')}:${currentMinute.toString().padStart(2, '0')}`;
            slots.push(timeString);
            
            currentMinute += 5;
            if (currentMinute >= 60) {
                currentMinute = 0;
                currentHour++;
            }
        }
        
        return slots;
    }

    function updateTimeOptions() {
        const dateInput = document.getElementById('appointment_date');
        const treatmentSelect = document.getElementById('service');
        const timeSelect = document.getElementById('appointment_time');
        const notesField = document.getElementById('notes');
        
        if (!dateInput.value) {
            timeSelect.innerHTML = '<option value="">Selecteer eerst een datum</option>';
            return;
        }
        
        const selectedDate = new Date(dateInput.value);
        const selectedTreatment = treatmentSelect.value;
        
        const slots = generateTimeSlots(selectedDate, selectedTreatment);
        
        if (slots.length === 0) {
            timeSelect.innerHTML = '<option value="">Geen beschikbare tijden voor deze behandeling</option>';
            return;
        }
        
        let options = '';
        slots.forEach(slot => {
            options += `<option value="${slot}">${slot}</option>`;
        });
        
        timeSelect.innerHTML = options;
        
        // Show/hide notes requirement for "Anders"
        if (selectedTreatment === 'Anders') {
            notesField.required = true;
            notesField.placeholder = "Gewenste tijdsduur en behandeling (verplicht)";
            notesField.closest('.mb-3').querySelector('label').innerHTML = 'Notities <span class="text-danger">*</span>';
        } else {
            notesField.required = false;
            notesField.placeholder = "Gewenste tijdsduur (optioneel), speciale wensen, etc.";
            notesField.closest('.mb-3').querySelector('label').innerHTML = 'Notities (optioneel)';
        }
    }

    function updateTreatmentOptions() {
        const dateInput = document.getElementById('appointment_date');
        const treatmentSelect = document.getElementById('service');
        const timeSelect = document.getElementById('appointment_time');
        
        if (!dateInput.value || !treatmentSelect.value) return;
        
        const selectedDate = new Date(dateInput.value);
        const selectedTreatment = treatmentSelect.value;
        
        const slots = generateTimeSlots(selectedDate, selectedTreatment);
        
        // Just clear time if not available, no alert
        if (slots.length === 0 && selectedTreatment) {
            treatmentSelect.value = '';
            timeSelect.innerHTML = '<option value="">Selecteer eerst een behandeling</option>';
        }
        
        updateTimeOptions();
    }

    // Event listeners
    document.getElementById('appointment_date').addEventListener('change', updateTimeOptions);
    document.getElementById('service').addEventListener('change', function() {
        updateTimeOptions();
        // Also check if time is still valid with new treatment
        const timeSelect = document.getElementById('appointment_time');
        if (timeSelect.value && timeSelect.options.length > 0) {
            // Check if selected time is still available
            let timeExists = false;
            for (let i = 0; i < timeSelect.options.length; i++) {
                if (timeSelect.options[i].value === timeSelect.value) {
                    timeExists = true;
                    break;
                }
            }
            if (!timeExists) {
                timeSelect.value = '';
                alert('De geselecteerde tijd past niet bij deze behandeling. Kies een nieuwe tijd.');
            }
        }
    });

    // Run on page load if date is preselected
    if (document.getElementById('appointment_date').value) {
        updateTimeOptions();
    }
    </script>
</x-layout>