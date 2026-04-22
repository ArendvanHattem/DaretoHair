<x-layout>
   <x-slot name="header">
        <h1 class="display-4 fw-bold">Mijn account</h1>
        <p class="lead">bekijk en wijzig hier je accountgegevens</p>
    </x-slot>

<x-slot>

    {{-- Errors --}}
    @if ($errors->any())
        <div class="alert alert-danger mt-4">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li style="list-style: none;">{{ $error }}</li>
                @endforeach
            </ul>
        </div>  
    @endif

    {{-- Success message --}}
    @if(session('success'))
        <div class="alert alert-success mt-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="edit-werknemer-wrapper">
        <h2>Accountgegevens aanpassen</h2>

        <div class="form-wrapper">
            <form method="POST" action="{{ route('account.update') }}">
                @csrf

                <div class="form-row">
                    <label class="form-label fw-bold fs-4 mb-2">Naam</label>
                    <input type="text" name="name" class="form-control"
                           value="{{ Auth::user()->name }}" required>
                </div>

                <div class="form-row">
                    <label class="form-label fw-bold fs-4 mb-2">Email</label>
                    <input type="email" name="email" class="form-control"
                           value="{{ Auth::user()->email }}" required>
                </div>

                <button type="submit">Gegevens opslaan</button>
            </form>
        </div>
    </div>


    {{-- PASSWORD SECTION --}}
    <div class="edit-werknemer-wrapper mt-5">
        <h2>Wachtwoord wijzigen</h2>

        <div class="form-wrapper">
            <form method="POST" action="{{ route('account.password') }}">
                @csrf

                <div class="form-row">
                    <label class="form-label fw-bold fs-4 mb-2">Huidig wachtwoord</label>
                    <input type="password" name="current_password" class="form-control" required>
                </div>

                <div class="form-row">
                    <label class="form-label fw-bold fs-4 mb-2">Nieuw wachtwoord</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <div class="form-row">
                    <label class="form-label fw-bold fs-4 mb-2">Herhaal wachtwoord</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>

                <button type="submit">Wachtwoord wijzigen</button>
            </form>
        </div>
    </div>

    <div class="mt-4">
    <div class="edit-werknemer-wrapper h-100 border border-danger">
        <h2 class="text-danger">Account verwijderen</h2>

    <div class="form-wrapper">
        <form method="POST" action="{{ route('account.delete') }}">
            @csrf
            @method('DELETE')

            <div class="form-row">
                <label class="form-label fw-bold fs-4 mb-2 text-danger">
                    Bevestig met wachtwoord
                </label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-danger mt-3"
                    onclick="return confirm('Weet je zeker dat je je account wilt verwijderen? Dit kan niet ongedaan worden gemaakt.')">
                Account verwijderen
            </button>
        </form>
    </div>
</div>
</div>


</x-slot>

</x-layout>
