<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use  App\Models\User;

class EditEmployee extends Controller
{
    public function index() {

    if (!auth()->check()) {
            return redirect()->route('clientsignup');
        }

        $employees = User::where('role', 'medewerker')->get();

        return view('admin.employees', compact('employees'));
        
    }

    public function edit($id) {

    if (!auth()->check()) {
            return redirect()->route('clientsignup');
        }

        $employee = User::findOrFail($id);
        
        return view('admin.edit_employees', compact('employee'));
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
                'password' => 'nullable|string|min:8|confirmed',
                'specialiteit' => 'string',
            ]);

    if (empty($validated['password'])) {
        unset($validated['password']);
    }

    $employee->update($validated);
    
 
        return redirect('employees');
    }

    public function delete($id) {

        if (!auth()->check()) {
                return redirect()->route('clientsignup');
            }

        $employee = User::findOrFail($id);
        $employee->delete();

        return redirect('employees');
        
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
            'specialiteit' => 'string',
        ],
        [
            'email.unique' => 'Er bestaat al een account met dit e-mailadres.',
            'phone.unique' => 'Er bestaat al een account met dit telefoonnummer.',
        ]);

        $validated['role'] = \App\Models\User::ROLE_EMPLOYEE;


    User::create($validated);

        return redirect('employees');
        
    }
}
