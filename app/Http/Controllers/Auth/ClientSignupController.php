<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class ClientSignupController extends Controller
{
     public function showSignupForm()
    {
        return view('auth.clientsignup');
    }
}
