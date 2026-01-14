<?php

namespace App\Http\Controllers;

use App\Models\pricelist;
use Illuminate\Http\Request;

class PriceController extends Controller
{
    public function index()
    {
        $prices = pricelist::all();

        $knippen_stylen = pricelist::where('category', 'knippen & stylen')->get();
        $kleuren = pricelist::where('category', 'kleuren')->get();

        return view('pricelist', compact('knippen_stylen', 'kleuren'));
    }
}