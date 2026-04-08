<x-layout>
    <x-slot name="header">
        <h1 class="display-4 fw-bold">Prijs bewerken</h1>
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
            <h2>{{ $price->name}} bewerken</h2>
            <div class="form-wrapper">
                <form method="POST" action="{{ route('admin.prijzen.update', $price->id) }}">
                @csrf
                @method('PATCH')


            <div class="form-row">
                <label for="service" class="form-label fw-bold fs-4 mb-2">Naam service</label>
                <input type="text" id="service" name="service"  class="form-control" value="{{ $price->service }}">
            </div>

            <div class="form-row">
                <label for="beschrijving" class="form-label fw-bold fs-4 mb-2">Beschrijving</label>
                <input type="text" id="beschrijving" name="beschrijving" class="form-control" value="{{ $price->description }}">
            </div>

            <div class="form-row">
                <label for="prijs" class="form-label fw-bold fs-4 mb-2">Prijs</label>
                <input type="number" id="prijs" name="prijs" class="form-control" value="{{ $price->amount }}">
            </div>
            
            <div class="form-row">
                <label for="categorie" class="form-label fw-bold fs-4 mb-2">Categorie</label>
                <input type="text" id="categorie" name="categorie" class="form-control" value="{{ $price->category }}">
            </div>


            <button type="submit">Prijs opslaan</button>
                </form>
            </div>
        </div>
    </x-slot>


</x-layout>