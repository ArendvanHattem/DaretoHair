<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($validated)) {

            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->hasRole('medewerker')) {
                return redirect()->route('dashboard');
            }

            return redirect()->route('home');
        }

        throw ValidationException::withMessages([
            'email' => 'Sorry, je email of wachtwoord is incorrect. Probeer het opnieuw.',
        ]);
    }
}
