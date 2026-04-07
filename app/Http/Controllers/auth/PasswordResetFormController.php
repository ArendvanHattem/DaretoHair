<?php

namespace App\Http\Controllers\auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

use Illuminate\Http\Request;

class PasswordResetFormController extends Controller
{
    public function index()
    {
        return view('passwordresetform');
    }

    public function handle(Request $request)
    {
            $request->validate([
                'token' => 'required',
                'email' => 'required|email|exists:users,email',
                'password' => 'required|string|min:8|confirmed',
            ]);

            $record = DB::table('password_reset_tokens')->where('email', $request->email)->first();

            if (!$record || !Hash::check($request->token, $record->token)) {
            
            $link = '<a href="' . route('passwordreset') . '" 
            class="text-decoration-none" 
            style="color: #8d0000ca;">
            vraag een nieuw token aan
         </a>';

            return back()->withErrors([
                'token' => "Ongeldig token, $link",
        ]);
            }

            if (!$record || now()->diffInMinutes($record->created_at) > 5) {
                return back()->withErrors(['token' => 'Ongeldig of verlopen token.']);
            }

            $user = User::where('email', $record->email)->first();

            if ($user) {
                $user->password = bcrypt($request->password);
                $user->save();
            }

        return redirect()->route('login')->with('success', 'Wachtwoord succesvol gereset. Je kunt nu inloggen met je nieuwe wachtwoord.');
    }
}
