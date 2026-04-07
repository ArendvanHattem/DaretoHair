<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClientMakeAppointmentController extends Controller
{
        public function index()
        {

        if (!auth()->check()) {
            return redirect()->route('clientsignup');
        }
        
            return view('clientmakeappointment');
        }
}
ø