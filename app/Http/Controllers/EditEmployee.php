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

      $validated = $request->validate(
        [
            'name' => 'string',
            'specialiteit' => 'string',
            'email' => '|email|unique:users,email,' . $employee->id,
        ],
        [
            'email.unique' => 'Er bestaat al een account met dit e-mailadres.',
        ]
    );

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

            $validated = $request->validate(
        [
            'name' => 'required|string',
            'specialiteit' => 'required|string',
            'email' => '|email|unique:users,email,',
             'phone' => ['required', 'string', 'unique:users,phone', 'regex:/^\+?[0-9]{6,15}$/'], 
        ],
        [
            'email.unique' => 'Er bestaat al een account met dit e-mailadres.',
            'phone.unique' => 'Er bestaat al een account met dit telefoonnummer.',
        ]);

    User::create($validated);

        return redirect('employees');
        
    }
}
