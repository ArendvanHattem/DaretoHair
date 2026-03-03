<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\pricelist;

class HomeController extends Controller
{
     public function index()
    {
        $knippen = pricelist::where('category', 'knippen & stylen')
        ->inRandomOrder()
        ->take(2)
        ->get();

        $kleuren = pricelist::where('category', 'kleuren')
            ->inRandomOrder()
            ->take(2)
            ->get();

        // Merge + shuffle so they’re mixed
        $services = $knippen->merge($kleuren)->shuffle();

        return view('welcome', compact('services'));
    }
}
