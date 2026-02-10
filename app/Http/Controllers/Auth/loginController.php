<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class loginController extends Controller
{
     public function showLoginForm()
    {
        return view('auth.clientlogin');
    }

    public function login(Request $request)
    {
         $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if(Auth::attempt($validated)) {
            $request->session()->regenerate();
            return redirect()->route('clientdashboard');
        }

        throw ValidationException::withMessages([
            'email' => 'Sorry, je email of wachtwoord is incorrect. Probeer het opnieuw.'
        ]);
    }
}
