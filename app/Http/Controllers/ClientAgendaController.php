<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClientAgendaController extends Controller
{
        public function index()
        {

        if (!auth()->check()) {
            return redirect()->route('clientsignup');
        }
        
            return view('clientagenda');
        }
}
