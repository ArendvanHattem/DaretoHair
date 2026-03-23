<x-layout>
    <x-slot name="header">
        <h1 class="display-4 fw-bold">Wachtwoord Resetten</h1>
        <p class="lead">Reset je wachtwoord</p>
    </x-slot>

    <div class="container">
        <div class="auth-wrap mx-auto">
        <form method="POST" action="{{ route('passwordresetform') }}" class="mx-auto auth-panel">
            @csrf

        <div class="mb-4">
                    <label class="form-label fw-bold fs-4 mb-2">Reset Token</label>
                    <div class="input-group thick-input">
                      <span class="input-group-text">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                          <rect x="3" y="11" width="18" height="11" rx="2"></rect>
                          <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                      </span>
                      <input type="text" name="token" class="form-control" placeholder="Reset token" required>
                    </div>
                  </div>

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

         <div class="mb-4">
                <label class="form-label fw-bold fs-4 mb-2">Nieuw wachtwoord</label>
                <div class="input-group thick-input">
                  <span class="input-group-text">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                      <rect x="3" y="11" width="18" height="11" rx="2"></rect>
                      <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                  </span>
                  <input type="password" name="password" class="form-control" placeholder="Nieuw wachtwoord" required>
                </div>
              </div>
        
        <div class="mb-4">
                <label class="form-label fw-bold fs-4 mb-2">Bevestig nieuw wachtwoord</label>
                <div class="input-group thick-input">
                  <span class="input-group-text">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                      <rect x="3" y="11" width="18" height="11" rx="2"></rect>
                      <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                  </span>
                  <input type="password" name="password_confirmation" class="form-control" placeholder="Bevestig nieuw wachtwoord" required>
                </div>
              </div>
               <button type="submit" class="btn btn-auth fw-bold w-100">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                    <rect x="3" y="11" width="18" height="11" rx="2"></rect>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
                 Reset wachtwoord
            </button>
               @if ($errors->any())
                <div class="alert alert-danger mt-4">
                    <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li style="list-style: none;">{!! $error !!}</li>
                    @endforeach
                    </ul>
                </div>  
                @endif
         </div>

</x-layout>