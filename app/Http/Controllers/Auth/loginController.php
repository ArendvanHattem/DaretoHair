<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
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

        if(Auth::attempt($validated)) {
            $request->session()->regenerate();
            return redirect()->route('dashboard');
        }

        throw ValidationException::withMessages([
            'email' => 'Sorry, je email of wachtwoord is incorrect. Probeer het opnieuw.'
        ]);
    }
}
