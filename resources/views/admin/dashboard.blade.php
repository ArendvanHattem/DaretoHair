<x-layout>
    <x-slot name="header">
        <h1 class="display-4 fw-bold">Welkom, {{ Auth::user()->name }}</h1>
        <p class="lead">Beheer hier alle admin functies</p>
    </x-slot>

    <br>

    <div class="row justify-content-center gap-4">

        <!-- Card 1 -->
        <div class="col-12 col-md-6 col-lg-3 d-flex">
            <div class="card text-center w-100 shadow-lg">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title fs-4 mb-3 fw-bolder">Medewerkers beheren</h5>
                    <p class="card-text">Hier kunt u al uw medewerkers beheren</p>
                    <a href="{{ route('admin.medewerkers.index') }}" class="btn btn-primary fw-bold mt-3">Beheren</a>
                </div>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="col-12 col-md-6 col-lg-3 d-flex">
            <div class="card text-center w-100 shadow-lg">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title fs-4 mb-3 fw-bolder">Prijzen beheren</h5>
                    <p class="card-text">Hier kunt u al uw prijzen beheren</p>
                    <br>
                    <a href="{{ route('admin.prijzen.index') }}" class="btn btn-primary fw-bold mt-3">Beheren</a>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-3 d-flex">
            <div class="card text-center w-100 shadow-lg">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title fs-4 mb-3 fw-bolder">Klanten beheren</h5>
                    <p class="card-text">Hier kunt u al uw klanten beheren</p>
                    <br>
                    <a href="{{ route('admin.klanten.index') }}" class="btn btn-primary fw-bold mt-3">Beheren</a>
                </div>
            </div>
        </div>

</x-layout>