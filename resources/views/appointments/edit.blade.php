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
                
                <div class="mb-3">
                    <label for="medewerker_id" class="form-label">Kapper</label>
                    <select class="form-control" id="medewerker_id" name="medewerker_id" required>
                        <option value="">Selecteer een kapper</option>
                        @foreach($medewerkers as $medewerker)
                            <option value="{{ $medewerker->id }}" {{ ($appointment->medewerker_id ?? '') == $medewerker->id ? 'selected' : '' }}>
                                {{ $medewerker->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Service: Dropdown for clients, text input for admin -->
                <div class="mb-3">
                    <label for="service" class="form-label">Behandeling</label>
                    @if($isAdmin)
                        <input type="text" class="form-control" id="service" name="service" 
                               value="{{ old('service', $appointment->service) }}" required>
                    @else
                        <select class="form-control" id="service" name="service" required>
                            <option value="">Selecteer een behandeling</option>
                            @foreach($treatments as $treatment)
                                <option value="{{ $treatment->service }}" {{ $appointment->service == $treatment->service ? 'selected' : '' }}>
                                    {{ $treatment->service }} ({{ $treatment->duration }} min)
                                </option>
                            @endforeach
                        </select>
                    @endif
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="appointment_date" class="form-label">Datum</label>
                        <input type="date" class="form-control" id="appointment_date" name="appointment_date" 
                               value="{{ old('appointment_date', $appointment->appointment_date->format('Y-m-d')) }}" 
                               min="{{ now()->format('Y-m-d') }}" required>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="appointment_time" class="form-label">Tijd</label>
                        <select class="form-control @error('appointment_time') is-invalid @enderror" id="appointment_time" name="appointment_time" required>
                            <!-- Will be populated by JavaScript -->
                        </select>
                        @error('appointment_time')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <!-- Duration: Auto-calculated from service, hidden for clients -->
                <input type="hidden" name="duration" id="duration" value="{{ $appointment->duration }}">
                
                <!-- Status: Only admin can see/change -->
                @if($isAdmin)
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="pending" {{ $appointment->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ $appointment->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="cancelled" {{ $appointment->status == 'cancelled' ? 'selected' : '' }}>Denied</option>
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
                    @php
                        $cancelWeek = ($currentWeek && $currentWeek !== 'null') ? $currentWeek : $appointment->appointment_date->startOfWeek()->format('Y-m-d');
                    @endphp
                    <a href="{{ route('clientagenda', ['week' => $cancelWeek]) }}" class="btn btn-secondary">Annuleren</a>
                    <button type="submit" class="btn btn-primary">Opslaan</button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        // Duration mapping from database (passed from controller)
        const durationMap = @json($treatmentDurations);

        // Update duration when service changes (for clients)
        document.getElementById('service')?.addEventListener('change', function() {
            const duration = durationMap[this.value] || 15;
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
            const selectedTreatment = document.getElementById('service')?.value;
            
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
            
            const duration = durationMap[selectedTreatment] || 15;
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

            // Handle Anders special behavior
            const notesField = document.getElementById('notes');
            if (selectedTreatment && selectedTreatment.toLowerCase().includes('anders')) {
                notesField.required = true;
                notesField.placeholder = "Gewenste tijdsduur en behandeling (verplicht)";
                const label = notesField.closest('.mb-3').querySelector('label');
                if (label) label.innerHTML = 'Notities <span class="text-danger">*</span>';
            } else {
                notesField.required = false;
                notesField.placeholder = "Gewenste tijdsduur (optioneel), speciale wensen, etc.";
                const label = notesField.closest('.mb-3').querySelector('label');
                if (label) label.innerHTML = 'Notities (optioneel)';
            }
        }

        // Date validation functions (same as create form)
        function getFirstBookableDateEdit() {
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            
            let checkDate = new Date(today);
            let maxChecks = 14;
            
            for (let i = 0; i < maxChecks; i++) {
                const dayName = checkDate.toLocaleDateString('en-US', { weekday: 'long' });
                const hours = openingHours[dayName];
                
                if (hours && hours[0] !== 'closed') {
                    const closingHour = parseInt(hours[1].split(':')[0]);
                    const cutoffTime = new Date(checkDate);
                    cutoffTime.setHours(closingHour - 12, 0, 0, 0);
                    
                    if (checkDate.toDateString() === today.toDateString()) {
                        if (new Date() < cutoffTime) {
                            return checkDate;
                        }
                    } else {
                        return checkDate;
                    }
                }
                checkDate.setDate(checkDate.getDate() + 1);
            }
            return null;
        }

        function checkDateBookableEdit() {
            const isAdmin = {{ auth()->user()->role === 'medewerker' ? 'true' : 'false' }};
            if (isAdmin) {
                updateTimeOptions();
                return;
            }
            const dateInput = document.getElementById('appointment_date');
            const selectedDate = new Date(dateInput.value);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            
            const dayName = selectedDate.toLocaleDateString('en-US', { weekday: 'long' });
            const hours = openingHours[dayName];
            
            let errorMessage = '';
            
            // Past date error (temporary)
            if (selectedDate < today) {
                errorMessage = '⚠️ Deze datum ligt in het verleden.';
                
                const dateField = document.getElementById('appointment_date');
                const existingError = document.getElementById('dateBookableErrorEdit');
                if (existingError) existingError.remove();
                
                const errorDiv = document.createElement('div');
                errorDiv.id = 'dateBookableErrorEdit';
                errorDiv.className = 'alert alert-warning mt-2';
                errorDiv.innerHTML = errorMessage;
                dateField.parentNode.appendChild(errorDiv);
                
                setTimeout(() => {
                    if (errorDiv) errorDiv.remove();
                }, 3000);
                
                updateTimeOptions();
                return false;
            }
            
            // Remove any existing error
            const existingError = document.getElementById('dateBookableErrorEdit');
            if (existingError) existingError.remove();
            
            // Sunday or closed day error (stays until date changes)
            if (!hours || hours[0] === 'closed') {
                errorMessage = '⚠️ De salon is gesloten op ' + selectedDate.toLocaleDateString('nl-NL', { weekday: 'long' }) + '.';
            } 
            // Today past cutoff error (stays until date changes)
            else if (selectedDate.toDateString() === today.toDateString()) {
                const closingHour = parseInt(hours[1].split(':')[0]);
                const cutoffTime = new Date(selectedDate);
                cutoffTime.setHours(closingHour - 12, 0, 0, 0);
                
                if (new Date() >= cutoffTime) {
                    errorMessage = '⚠️ Het is te laat om vandaag nog een afspraak te wijzigen. Kies een andere datum.';
                }
            }
            
            if (errorMessage) {
                const dateField = document.getElementById('appointment_date');
                const errorDiv = document.createElement('div');
                errorDiv.id = 'dateBookableErrorEdit';
                errorDiv.className = 'alert alert-warning mt-2';
                errorDiv.innerHTML = errorMessage;
                dateField.parentNode.appendChild(errorDiv);
                // Stays until user changes date
                updateTimeOptions();
                return false;
            }
            
            updateTimeOptions();
            return true;
        }

        // Event listeners
        const dateInputEdit = document.getElementById('appointment_date');
        if (dateInputEdit) {
            dateInputEdit.addEventListener('change', function() {
                checkDateBookableEdit();
            });
        }
        
        document.getElementById('service')?.addEventListener('change', updateTimeOptions);

        // Run on page load
        if (dateInputEdit && dateInputEdit.value) {
            checkDateBookableEdit();
            updateTimeOptions();
        }
    </script>
</x-layout>