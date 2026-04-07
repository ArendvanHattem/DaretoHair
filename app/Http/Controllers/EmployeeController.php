<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Role;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    public function index() {
        $employees = User::role('medewerker')->get();
        return view('admin.medewerkers.medewerkers', compact('employees'));
    }

    // TOON het formulier
    public function create() {
        return view('admin.medewerkers.create');
    }

    public function store(Request $request) {
    // 1. Validatie
    $validated = $request->validate([
        'email' => 'required|email|unique:users',
        'name' => 'required|string|max:255',
        'phone' => 'required|string|max:20',
        'password' => 'required|string|min:8|confirmed',
        'specialiteit' => 'nullable|string',
    ]);

    // 2. Gebruiker aanmaken
    $employee = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'phone' => $validated['phone'],
        'password' => Hash::make($request->password),
        'specialiteit' => $validated['specialiteit'] ?? null,
    ]);

    // 3. De rol 'klant' overschrijven naar 'medewerker'
    // syncRoles verwijdert 'klant' (die uit de booted methode komt) en zet 'medewerker' neer
    $employee->syncRoles(['medewerker']);

    return redirect()->route('admin.medewerkers.index')->with('success', 'Medewerker succesvol aangemaakt.');
}

    public function edit($id) {
        $employee = User::findOrFail($id);
        return view('admin.medewerkers.edit', compact('employee'));
    }

    public function update(Request $request, $id) {
        $employee = User::findOrFail($id);

        $validated = $request->validate([
            'email' => 'required|email|unique:users,email,'.$id,
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'specialiteit' => 'nullable|string',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $employee->update($validated);

        return redirect()->route('admin.medewerkers.index')->with('success', 'Medewerker bijgewerkt.');
    }

    public function destroy($id) {
        $employee = User::findOrFail($id);
        $employee->delete();

        return redirect()->route('admin.medewerkers.index')->with('success', 'Medewerker verwijderd.');
    }
}