<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
        <link href="{{ asset('style.css') }}" rel="stylesheet">
      </head>
         <body>
            <nav class="navbar navbar-expand-lg border-bottom border-dark">
              <div class="container-fluid d-flex align-items-center">

                <!-- Left: Logo -->
                <a class="navbar-brand me-3" href="/">
                  <img src="{{ asset('images/logo.png') }}" alt="Logo" width="79.7" height="79.7">
                </a>

                <!-- Center: Nav links -->
                <ul class="navbar-nav nav-pills mx-auto d-flex align-items-center">
                  <li class="nav-item">
                    <x-nav-link href="/pricelist" :active="request()->is('pricelist')">Prijslijst</x-nav-link>
                  </li>
                  <li class="nav-item">
                    <x-nav-link href="/about" :active="request()->is('about')">Over Ons</x-nav-link>
                  </li>
                  <li class="nav-item">
                    <x-nav-link href="/team" :active="request()->is('team')">Het Team</x-nav-link>
                  </li>
                </ul>

                <!-- Right: Button -->
                <a href="{{ route('clientlogin') }}">
                  <button class="btn btn-primary m-3 fw-bold">Log In</button>
                </a>
              </div>
            </nav>

              <header class="text-black py-5">
                <div class="container text-center">
                    {{ $header }}
                </div>
            </header>

            <main class="container mb-5">
                {{ $slot }}
            </main>



        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    </body>
</html>

