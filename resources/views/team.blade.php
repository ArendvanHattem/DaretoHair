<x-layout>
    <x-slot name="header">
        <h1 class="display-4 fw-bold">Het Team</h1>
        <p class="lead">Maak kennis met de gepassioneerde professionals achter Dare To Hair.</p>
    </x-slot>

    <div class="container py-5">
        <div class="row g-4">
            @forelse ($teamleden as $lid)
            <div class="col-md-4">
                <div class="card overflow-hidden">
                    <!-- Image: fixed height -->
                    <img src="{{$lid->photo}}" style="height:300px; object-fit:cover;" alt="{{$lid->naam}}">

                    <!-- Content: fills remaining space -->
                    <div class="flex-1 p-4 text-center flex flex-col justify-center" style="height: 150px;">
                        <h2 class="card-title fs-4">{{$lid->naam}}</h2>
                        <p class="card-text text-gray-500">{{$lid->speciality}}</p>
                    </div>
                </div>
            </div>

            @empty
            <div class="row">
             <p class="text-center">Helaas, geen teamleden gevonden! :(</p>
            </div>

            @endforelse
        </div>
    </div>
</x-layout>
