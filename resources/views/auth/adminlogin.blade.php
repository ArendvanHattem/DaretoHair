<x-layout>
  <x-slot name="header">
    <h1 class="display-4 fw-bold">Welkom Terug</h1>
    <p class="lead">Log in of maak een nieuw account aan</p>
  </x-slot>

  <div class="container">
    <div class="auth-wrap mx-auto">

     <ul class="nav nav-pills justify-content-center gap-2 auth-tabs mb-4">

  <li class="nav-item">
    <a class="nav-link {{ request()->routeIs('clientlogin') ? 'active' : '' }}"
       href="{{ route('clientlogin') }}">
      Inloggen Klant
    </a>
  </li>

  <li class="nav-item">
    <a class="nav-link {{ request()->routeIs('adminlogin') ? 'active' : '' }}"
       href="{{ route('adminlogin') }}">
      Inloggen Admin
    </a>
  </li>

  <li class="nav-item">
    <a class="nav-link {{ request()->routeIs('clientsignup') ? 'active' : '' }}"
       href="{{ route('clientsignup') }}">
      Registreren
    </a>
  </li>

</ul>

      <!-- Client panel -->
      <div class="auth-panel">
        <h1 class="fw-bold display-6 mb-2">Admin inloggen</h1>
        <p class="fs-5 mb-4">Log in om je afspraken te beheren en nieuwe te boeken</p>

        <form method="POST" action="{{ route('adminlogin') }}"> 
          @csrf
          <div class="mb-4">
            <label class="form-label fw-bold fs-4 mb-2">Email</label>
            <div class="input-group thick-input">
              <span class="input-group-text">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                  <path d="M4 4h16v16H4z"></path>
                  <path d="m4 6 8 6 8-6"></path>
                </svg>
              </span>
              <input type="email" name="email" class="form-control" placeholder="your@email.com" value="{{old('email')}}" required>
            </div>
          </div>

          <div class="mb-5">
            <label class="form-label fw-bold fs-4 mb-2">Wachtwoord</label>
            <div class="input-group thick-input">
              <span class="input-group-text">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                  <rect x="5" y="11" width="14" height="10" rx="2"></rect>
                  <path d="M8 11V8a4 4 0 0 1 8 0v3"></path>
                </svg>
              </span>
              <input type="password" class="form-control" placeholder="Enter your password">
            </div>
          </div>

          <button type="submit" class="btn btn-auth w-100 d-flex align-items-center justify-content-center gap-3">
            <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8">
              <path d="M20 21a8 8 0 0 0-16 0"></path>
              <circle cx="12" cy="8" r="4"></circle>
            </svg>
            Inloggen als Admin
          </button>
        </form>
      </div>

    </div>
  </div>
</x-layout>
