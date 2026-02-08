<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;

use Illuminate\Http\Request;

class loginController extends Controller
{
     public function showLoginForm()
    {
        return view('auth.clientlogin');
    }

    public function login(Request $request)
    {
    }
}
