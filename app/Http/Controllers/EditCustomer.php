<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use  App\Models\User;

class EditCustomer extends Controller
{
    public function index() {

    if (!auth()->check()) {
            return redirect()->route('clientsignup');
        }

        $klanten = User::where('role', 'klant')->get();

        return view('admin.customers.klanten', compact('klanten'));
        
    }

    public function edit($id) {

    if (!auth()->check()) {
            return redirect()->route('clientsignup');
        }

        $klant = User::findOrFail($id);
        
        return view('admin.customers.edit_klant', compact('klant'));
    }

    public function update(Request $request, $id) {

    if (!auth()->check()) {
            return redirect()->route('clientsignup');
        }

    $employee = User::findOrFail($id);

    $validated = $request->validate([
                'email' => 'required|email|',
                'name' => 'required|string|max:255',
                'phone' => 'required|string|max:20|',
            ]);

    $employee->update($validated);
    
 
        return redirect('customers');
    }

    public function delete($id) {

        if (!auth()->check()) {
                return redirect()->route('clientsignup');
            }

        $klant = User::findOrFail($id);
        $klant->delete();

        return redirect('customers');
        
    }

    public function create(Request $request) {
        
        if (!auth()->check()) {
            return redirect()->route('clientsignup');
        }

        $validated = $request->validate([
            'email' => 'required|email|unique:users',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:users,phone',
            'password' => 'required|string|min:8|confirmed',
        ],
        [
            'email.unique' => 'Er bestaat al een account met dit e-mailadres.',
        ]);

        $validated['role'] = \App\Models\User::ROLE_CUSTOMER;


    User::create($validated);

        return redirect('customers');
        
    }
}
