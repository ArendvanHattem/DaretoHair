<x-layout>
     <x-slot name="header">
        <h1 class="display-4 fw-bold">Welkom, {{ Auth::user()->name }}</h1>
        <p class="lead">Proffesionele zorg voor jouw haar, met passie en vakmanschap.</p>
        <a href="/clientmakeappointment" class="btn btn-primary fw-bold">Maak nieuwe afspraak</a>
        <a href="/pricelist" class="btn btn-primary fw-bold">Bekijk Prijzen</a>
    </x-slot>
    <br>
    <h1 class="display-5 fw-bold text-center mb-5">Onze Behandelingen</h1>
    <div class="row justify-content-center">
  @foreach ($services as $service)
    <div class="col-12 col-md-6 col-lg-3 mb-3 d-flex">
      <div class="card price-card text-center w-100">
        <div class="card-body">
          <h5 class="card-title fs-4">{{ $service->service }}</h5>
          <p class="card-text fw-bold fs-2">€{{ number_format($service->amount, 2) }}</p>
          <p class="card-text fs-6">{{ $service->description }}</p>
        </div>
      </div>
    </div>
  @endforeach
</div>

<div class="text-center mt-3">
  <a href="/pricelist" class="btn btn-auth">
    Zie de prijslijst voor alle behandelingen
  </a>
</div>
</x-layout>