<x-layout>
    <x-slot name="header">
        <div class="py-5 text-center">
            <h1 class="display-1 fw-bold text-danger">403</h1>
            <h2 class="h4">Toegang geweigerd</h2>
        </div>
    </x-slot>

    <div class="container text-center py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <p class="lead mb-4">
                    Oeps! Het lijkt erop dat je niet de juiste rechten hebt om deze pagina te bekijken.
                </p>
                <hr class="my-4">
                
                <div class=" gap-2 d-sm-flex justify-content-sm-center">
                    <a href="{{ url('/') }}" class="btn btn-primary fw-bold" style="display: flex; align-items: center;">
                        Naar Home
                    </a>
                    <button style="border: 2px solid var(--purple); border-radius:var(--bs-border-radius);" onclick="window.history.back()">
                        Vorige pagina
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-layout>