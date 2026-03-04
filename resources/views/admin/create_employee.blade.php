<x-layout>
    <x-slot name="header">
        <h1 class="display-4 fw-bold">Werknemer toevoegen</h1>
    </x-slot>

    <x-slot>
        <div class="edit-werknemer-wrapper">
            <h2>Werknemer toevoegen</h2>
            <div class="form-wrapper">
                <form method="POST" action="{{ route('create_employee') }}">
                @csrf

            <div class="form-row">
                <label for="name" class="form-label fw-bold fs-4 mb-2">Naam werknemer</label>
                <input type="text" id="naam" name="name" class="form-control">
            </div>

            <div class="form-row">
                <label for="specialiteit" class="form-label fw-bold fs-4 mb-2">Specialiteit</label>
                <input type="text" id="specialiteit" name="specialiteit" class="form-control">
            </div>
            
            <div class="form-row">
                <label for="email" class="form-label fw-bold fs-4 mb-2">Email</label>
                <input type="email" id="email" name="email" class="form-control">
            </div>

            <div class="form-row">
                <label for="phone" class="form-label fw-bold fs-4 mb-2">Telefoonnummer</label>
                <input type="phone" id="phone" name="phone" class="form-control">
            </div>


            <button type="submit">Werknemer aanmaken</button>
                </form>
            </div>
        </div>
    </x-slot>


</x-layout>