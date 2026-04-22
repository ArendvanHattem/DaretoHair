<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AccountgegevensController extends Controller
{
         public function show()
    {
        return view('accountgegevensBekijken');
    }

     public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ]);

        $user = Auth::user();
        $user->update($request->only('name', 'email'));

        return back()->with('success', 'Gegevens bijgewerkt!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Huidig wachtwoord klopt niet']);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Wachtwoord gewijzigd!');
    }

    public function destroy(Request $request)
{
    $request->validate([
        'password' => 'required',
    ]);

    $user = Auth::user();

    // Check password
    if (!Hash::check($request->password, $user->password)) {
        return back()->withErrors(['password' => 'Wachtwoord klopt niet']);
    }

    // Logout BEFORE deleting
    Auth::logout();

    $user->delete();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/login')->with('success', 'Account succesvol verwijderd');
}
}
