<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\pricelist;

class HomeController extends Controller
{
     public function index()
    {
        // select pricelist where category is knippen & stylen and return 2 in random order
        $knippen = pricelist::where('category', 'knippen & stylen')
        ->inRandomOrder()
        ->take(2)
        ->get();

        // select pricelist where category is kleuren and return 2 in random order
        $kleuren = pricelist::where('category', 'kleuren')
            ->inRandomOrder()
            ->take(2)
            ->get();

        // Merge + shuffle so they’re mixed
        $services = $knippen->merge($kleuren)->shuffle();

        return view('welcome', compact('services'));
    }
}
