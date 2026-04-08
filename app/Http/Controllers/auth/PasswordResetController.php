<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class PasswordResetController extends Controller
{
    public function index()
    {
        return view('passwordreset');
    }


    public function handle(Request $request)
    {
        $request->validate(
            [
                'email' => 'required|email|exists:users,email',
            ],
            [
                'email.exists' => 'Er bestaat geen account met dit emailadres. <br> <a href="' . route('login') . '" class="text-decoration-none fw-bold" style="color: #8d0000ca;">Wil je een account aanmaken?</a>',
            ]
        );

        $token = Str::random(60);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => bcrypt($token),
                'created_at' => now()
            ]
        );

        return back()->with('token', $token);
    }
}
