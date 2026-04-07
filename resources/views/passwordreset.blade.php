<x-layout>
<x-slot name="header">
    <h1 class="display-4 fw-bold">Wachtwoord Resetten</h1>
    <p class="lead">Voer je email in om je reset token te ontvangen</p>
</x-slot>

<div>
    <div class="container">
        <div class="auth-wrap mx-auto">
    <form method="POST" action="{{ route('passwordreset') }}" class="mx-auto auth-panel">
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

          @if(session('token'))
            <div class="alert alert-success mt-4">
                <strong>Reset token (5 minuten geldig):</strong> 
                <br>
                {{ session('token') }}
                <br>
                <a href="{{ route('passwordresetform') }}" class="text-decoration-none fw-bold" style="color: #8d0000ca;">
                    Klik hier om je wachtwoord te resetten
            </div>
            @endif

    @if ($errors->any())
      <div class="alert alert-danger mt-4">
        <ul class="mb-0">
          @foreach ($errors->all() as $error)
            <li style="list-style: none;">{!! $error !!}</li>
          @endforeach
        </ul>
      </div>  
    @endif

            <button type="submit" class="btn btn-auth fw-bold w-100">
                <!-- lock icon -->
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                    <rect x="3" y="11" width="18" height="11" rx="2"></rect>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
                 Reset Wachtwoord
            </button>
    </form>
</div>
    </div>
</div>

</x-layout>