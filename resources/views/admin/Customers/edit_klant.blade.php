<x-layout>
    <x-slot name="header">
        <h1 class="display-4 fw-bold">Klant bewerken</h1>
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
            <h2>{{ $klant->name}} bewerken</h2>
            <div class="form-wrapper">
                <form method="POST" action="{{ route('update_customer', $klant->id) }}">
                @csrf
                @method('PATCH')


            <div class="form-row">
                <label for="name" class="form-label fw-bold fs-4 mb-2">Naam klant</label>
                <input type="text" id="naam" name="name"  class="form-control" value="{{ $klant->name }}">
            </div>

            <div class="form-row">
                <label for="phone" class="form-label fw-bold fs-4 mb-2">Telefoonnummer</label>
                <input type="text" id="phone" name="phone" class="form-control" value="{{ $klant->phone }}">
            </div>
            
            <div class="form-row">
                <label for="email" class="form-label fw-bold fs-4 mb-2">Email</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ $klant->email }}">
            </div>


            <button type="submit">Klant opslaan</button>
                </form>
            </div>
        </div>
    </x-slot>


</x-layout>