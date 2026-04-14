<x-layout>
    <x-slot name="header">
        <h1 class="display-4 fw-bold">Behandeling toevoegen</h1>
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
            <h2>Behandeling toevoegen</h2>
            <div class="form-wrapper">
                <form method="POST" action="{{ route('admin.prijzen.store') }}">
                @csrf

            <div class="form-row">
                <label for="service" class="form-label fw-bold fs-4 mb-2">Naam Service</label>
                <input type="text" id="service" name="service" class="form-control" placeholder="De service...">
            </div>

            <div class="form-row">
                <label for="beschrijving" class="form-label fw-bold fs-4 mb-2">Beschrijving</label>
                <input type="text" id="beschrijving" name="beschrijving" class="form-control" placeholder="Beschrijf de service...">
            </div>
            
            <div class="form-row">
                <label for="prijs" class="form-label fw-bold fs-4 mb-2">Prijs</label>
                <input type="number" id="prijs" name="prijs" class="form-control" placeholder="€25...">
            </div>

            <div class="form-row">
                <label for="categorie" class="form-label fw-bold fs-4 mb-2">Categorie</label>
                <input type="text" name="categorie" class="form-control" placeholder="Knippen...">
            </div>

            <button type="submit">Behandeling aanmaken</button>
                </form>
            </div>
        </div>

        
    </x-slot>


</x-layout>