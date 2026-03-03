<?php

namespace App\Http\Controllers;

use App\Models\pricelist;
use Illuminate\Http\Request;

class PriceController extends Controller
{
    public function index()
    {

    // fetch all of the prices from the pricelist
        $prices = pricelist::all();

        // save pricelists based on categories in variables and return to view
        $knippen_stylen = pricelist::where('category', 'knippen & stylen')->get();
        $kleuren = pricelist::where('category', 'kleuren')->get();

        return view('pricelist', compact('knippen_stylen', 'kleuren'));
    }
}