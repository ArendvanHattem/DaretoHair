<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index() {
        // fetch all of the teamleden and return it to the view
        $teamleden = User::where('role', 'medewerker')->get();

        return view('team', compact('teamleden'));

    }
}
