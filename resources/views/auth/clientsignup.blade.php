<x-loginlayout>
  <x-slot name="header">
    <h1 class="display-4 fw-bold">Welkom Terug</h1>
    <p class="lead">Log in in je account of maak een nieuwe</p>
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
      <h1 class="fw-bold display-6 mb-2">Klant Registratie</h1>
      <p class="fs-5 mb-4">Maak een nieuw account aan om je afspraak te boeken</p>

  <form method="POST" action="{{ route('clientsignup') }}">
    @csrf

    <div class="mb-4">
      <label class="form-label fw-bold fs-4 mb-2">Email</label>
      <div class="input-group thick-input">
        <span class="input-group-text">
          <!-- envelope icon -->
          <svg width="32" height="32" viewBox="0 0 24 24" fill="none"
               stroke="currentColor" stroke-width="1.6">
            <path d="M4 4h16v16H4z"></path>
            <path d="m4 6 8 6 8-6"></path>
          </svg>
        </span>
        <input type="email" name="email" class="form-control" placeholder="your@email.com">
      </div>
    </div>

    <!-- Naam -->
    <div class="mb-4">
      <label class="form-label fw-bold fs-4 mb-2">Naam</label>
      <div class="input-group thick-input">
        <span class="input-group-text">
          <!-- user icon -->
          <svg width="32" height="32" viewBox="0 0 24 24" fill="none"
               stroke="currentColor" stroke-width="1.6">
            <circle cx="12" cy="8" r="4"></circle>
            <path d="M4 21a8 8 0 0 1 16 0"></path>
          </svg>
        </span>
        <input type="text" name="name" class="form-control" placeholder="Je volledige naam">
      </div>
    </div>

    <div class="mb-4">
      <label class="form-label fw-bold fs-4 mb-2">Telefoonnummer</label>
      <div class="input-group thick-input">
        <span class="input-group-text">
          <!-- phone icon -->
          <svg width="32" height="32" viewBox="0 0 24 24" fill="none"
               stroke="currentColor" stroke-width="1.6">
            <path d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.8 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384"></path>
          </svg>
        </span>
        <input type="text" name="phone" class="form-control" placeholder="06-123456789">
      </div>
    </div>

    <!-- Wachtwoord -->
    <div class="mb-4">
      <label class="form-label fw-bold fs-4 mb-2">Wachtwoord</label>
      <div class="input-group thick-input">
        <span class="input-group-text">
          <!-- lock icon -->
          <svg width="32" height="32" viewBox="0 0 24 24" fill="none"
               stroke="currentColor" stroke-width="1.6">
            <rect x="5" y="11" width="14" height="10" rx="2"></rect>
            <path d="M8 11V8a4 4 0 0 1 8 0v3"></path>
          </svg>
        </span>
        <input type="password" name="password" class="form-control" placeholder="Kies een wachtwoord">
      </div>
    </div>

    <!-- Bevestig wachtwoord -->
    <div class="mb-5">
      <label class="form-label fw-bold fs-4 mb-2">Bevestig wachtwoord</label>
      <div class="input-group thick-input">
        <span class="input-group-text">
          <!-- lock icon -->
          <svg width="32" height="32" viewBox="0 0 24 24" fill="none"
               stroke="currentColor" stroke-width="1.6">
            <rect x="5" y="11" width="14" height="10" rx="2"></rect>
            <path d="M8 11V8a4 4 0 0 1 8 0v3"></path>
          </svg>
        </span>
        <input type="password" name="password_confirmation"
               class="form-control" placeholder="Herhaal wachtwoord">
      </div>
    </div>

    <!-- Button -->
    <button type="submit"
      class="btn btn-auth w-100 d-flex align-items-center justify-content-center gap-3">
      
      <!-- user-plus icon -->
      <svg width="34" height="34" viewBox="0 0 24 24" fill="none"
           stroke="white" stroke-width="1.8">
        <circle cx="12" cy="8" r="4"></circle>
        <path d="M4 21a8 8 0 0 1 16 0"></path>
        <path d="M19 8v6"></path>
        <path d="M22 11h-6"></path>
      </svg>

      Registreren als Klant
    </button>
  </form>
</div>

    </div>
  </div>
</x-loginlayout>
