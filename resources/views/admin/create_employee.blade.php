<x-layout>
    <x-slot name="header">
        <h1 class="display-4 fw-bold">Werknemer toevoegen</h1>
    </x-slot>

    <x-slot>
        @if ($errors->any())
            <div class="alert alert-danger mt-4">
                <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li style="list-style: none;">{{ $error }}</li>
                @endforeach
                </ul>
            </div>  
            @endif

        <div class="edit-werknemer-wrapper">
            <h2>Werknemer toevoegen</h2>
            <div class="form-wrapper">
                <form method="POST" action="{{ route('create_employee') }}">
                @csrf

            <div class="form-row">
                <label for="name" class="form-label fw-bold fs-4 mb-2">Naam werknemer</label>
                <input type="text" id="naam" name="name" class="form-control" placeholder="Jouw naam...">
            </div>

            <div class="form-row">
                <label for="specialiteit" class="form-label fw-bold fs-4 mb-2">Specialiteit</label>
                <input type="text" id="specialiteit" name="specialiteit" class="form-control" placeholder="Kleuren...">
            </div>
            
            <div class="form-row">
                <label for="email" class="form-label fw-bold fs-4 mb-2">Email</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="naam@voorbeeld.nl">
            </div>

            <div class="form-row">
                <label for="phone" class="form-label fw-bold fs-4 mb-2">Telefoonnummer</label>
                <input type="phone" id="phone" name="phone" class="form-control" placeholder="06-12345678">
            </div>

            <div class="form-row">
                <label for="password" class="form-label fw-bold fs-4 mb-2">Wachtwoord</label>
                <input type="password" name="password" class="form-control" placeholder="Kies een wachtwoord...">
            </div>

            <div class="form-row">
                <label for="password_confirmation" class="form-label fw-bold fs-4 mb-2">Herhaal wachtwoord</label>
                <input type="password" name="password_confirmation"class="form-control" placeholder="Herhaal wachtwoord">
            </div>

            <button type="submit">Werknemer aanmaken</button>
                </form>
            </div>
        </div>

        
    </x-slot>


</x-layout>