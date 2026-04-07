<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use  App\Models\User;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    public function index() {
        $klanten = User::where('role', 'klant')->get();
        return view('admin.klanten.klanten', compact('klanten'));
    }

    public function create() {
        return view('admin.klanten.create'); 
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'email' => 'required|email|unique:users',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:users,phone',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'email.unique' => 'Er bestaat al een account met dit e-mailadres.',
        ]);

        $validated['role'] = 'klant';
        $validated['password'] = Hash::make($request->password);

        User::create($validated);

        return redirect()->route('admin.klanten.index')->with('success', 'Klant aangemaakt');
    }

    public function edit($id) {
        $klant = User::findOrFail($id);
        return view('admin.klanten.edit', compact('klant'));
    }

    public function update(Request $request, $id) {
        $klant = User::findOrFail($id);

        $validated = $request->validate([
            'email' => 'required|email|unique:users,email,'.$id,
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
        ]);

        $klant->update($validated);

        return redirect()->route('admin.klanten.index')->with('success', 'Klant bijgewerkt');
    }

    public function destroy($id) {
        $klant = User::findOrFail($id);
        $klant->delete();

        return redirect()->route('admin.klanten.index')->with('success', 'Klant verwijderd');
    }
}