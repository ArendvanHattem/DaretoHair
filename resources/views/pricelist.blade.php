<x-layout>
       <x-slot name="header">
        <h1 class="display-4 fw-bold">Prijslijst</h1>
        <p class="lead">Bekijk onze actuele prijzen voor alle diensten.</p>
    </x-slot>

    <x-slot>
        <h2 class="mb-4 text-center fw-bold fs-1">Knippen & Stylen</h2>
        <br>
        <div class="row justify-content-center">
    @foreach($knippen_stylen as $price)
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
<h2 class="mb-4 text-center fw-bold fs-1">Kleuren</h2>
<br>
        <div class="row justify-content-center">
    @foreach($kleuren as $price)
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
    </x-slot>
</x-layout>