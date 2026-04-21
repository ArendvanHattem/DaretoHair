<x-layout>
    <x-slot name="header">
        <h1 class="display-4 fw-bold">Het Team</h1>
        <p class="lead">Maak kennis met de gepassioneerde professionals achter Dare To Hair.</p>
    </x-slot>

    <div class="container py-5">
        <div class="row g-4">
            <!-- Displaying the teamleden -->
            @forelse ($teamleden as $lid)
            <div class="col-md-4">
                <div class="card overflow-hidden">
                    <!-- Image -->
                        <img src="{{ $lid->photo ?? asset('images/logo.png') }}" > 
                    <!-- Content -->
                    <div class="flex-1 p-4 text-center flex flex-col justify-center" style="height: 150px;">
                        <h2 class="card-title fs-4">{{$lid->name}}</h2>
                        <p class="card-text text-gray-500">{{$lid->specialiteit}}</p>
                    </div>
                </div>
            </div>

            <!-- Message if there are no teamleden -->
            @empty
            <div class="row">
             <p class="text-center">Helaas, geen teamleden gevonden! :(</p>
            </div>

            @endforelse
        </div>
    </div>
</x-layout>
