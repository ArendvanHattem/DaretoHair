<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientSignupController extends Controller
{
     public function showSignupForm()
    {
        return view('auth.clientsignup');
    }

    public function signup(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:users',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ],
        [
            'email.unique' => 'Er bestaat al een account met dit e-mailadres.',

        ]);

        $user = User::create($validated);

        Auth::login($user);

        return redirect()->route('clientlogin') ->with('success', 'Account created successfully. Please log in.');
    }
}
