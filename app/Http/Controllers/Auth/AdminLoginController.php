<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class AdminLoginController extends Controller
{
     public function showLoginForm()
    {
        return view('auth.adminlogin');
    }

    public function login(Request $request)
    {
    
    }
}
