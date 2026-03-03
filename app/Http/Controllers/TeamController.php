<?php

namespace App\Http\Controllers;

use App\Models\Teamlid;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index() {
        // fetch all of the teamleden and return it to the view
        
        $teamleden = Teamlid::all();

        return view('team', compact('teamleden'));

    }
}
