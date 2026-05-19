<x-layout>
    <x-slot name="header">
        <h1 class="display-4 fw-bold">Prijslijst</h1>
        <p class="lead">Bekijk onze actuele prijzen voor alle diensten.</p>
    </x-slot>

    @foreach($prijzenPerCategorie as $categorie => $prijzen)
        <h2 class="mb-4 text-center fw-bold fs-1 mt-5">{{ $categorie }}</h2>
        <br>
        <div class="row justify-content-center">
            @foreach($prijzen as $price)
                <div class="col-12 col-md-4 mb-3 d-flex">
                    <div class="card price-card text-center">
                        <div class="card-body">
                            <h5 class="card-title fs-3">{{ $price->service }}</h5>
                            <p class="card-text fw-bold fs-1">€{{ number_format($price->amount, 2) }}</p>
                            <p class="card-text">{{ $price->description }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <br>
    @endforeach
</x-layout>