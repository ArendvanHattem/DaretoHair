<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>{{ config('app.name', 'Laravel') }}</title>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom CSS -->
  <link href="{{ asset('style.css') }}" rel="stylesheet">
</head>

<body>

<nav class="navbar navbar-expand-lg border-bottom border-dark">
  <div class="container-fluid d-flex align-items-center">

    @hasrole('medewerker')
        {{-- Ingelogd als medewerker: naar dashboard --}}
        <a class="navbar-brand me-3" href="/dashboard">
          <img src="{{ asset('images/logo.png') }}" alt="Logo" width="79.7" height="79.7">
        </a>
    @else
        {{-- Gast of Klant: naar de homepage --}}
        <a class="navbar-brand me-3" href="/">
          <img src="{{ asset('images/logo.png') }}" alt="Logo" width="79.7" height="79.7">
        </a>
    @endhasrole

    <!-- Center: Nav links -->
    <ul class="navbar-nav nav-pills mx-auto d-flex align-items-center gap-2">

      <li class="nav-item">
        <x-nav-link href="{{ route('prijzen.public') }}" :active="request()->routeIs('prijzen.public')">
          Prijslijst
        </x-nav-link>
      </li>

      <li class="nav-item">
        <x-nav-link href="/over-ons" :active="request()->is('over-ons')">
          Over Ons
        </x-nav-link>
      </li>

      @auth
        <li class="nav-item ms-3">
          <a href="/clientmakeappointment" class="btn btn-primary fw-bold">
            Maak afspraak
          </a>
        </li>

        <li class="nav-item">
          <a href="/clientagenda" class="btn btn-primary fw-bold">
            Agenda
          </a>
        </li>
      @endauth

      <li class="nav-item">
        <x-nav-link href="/team" :active="request()->is('team')">
          Het Team
        </x-nav-link>
      </li>

    </ul>

    <!-- Right: Auth area -->
    @auth
      <div class="d-flex align-items-center gap-3">

        <!-- User icon + name -->
        <div class="d-flex align-items-center gap-2 fw-bold">
          <svg width="34" height="34" viewBox="0 0 24 24"
               fill="none" stroke="black" stroke-width="1.8">
            <path d="M20 21a8 8 0 0 0-16 0"></path>
            <circle cx="12" cy="8" r="4"></circle>
          </svg>

          <span>{{ Auth::user()->name }}</span>
        </div>

        <!-- Logout -->
        <form action="{{ route('logout') }}" method="POST" class="m-0">
          @csrf
          <button type="submit" class="btn btn-primary fw-bold">
            Log Out
          </button>
        </form>

      </div>
    @else
      <a href="{{ route('login') }}" class="btn btn-primary fw-bold">
        Log In
      </a>
    @endauth

  </div>
</nav>

<header class="text-black py-5 mb-4 bg-light">
  <div class="container text-center">
    {{ $header }}
  </div>
</header>

<main class="container mb-5">
  {{ $slot }}
</main>

  <x-footer />

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>