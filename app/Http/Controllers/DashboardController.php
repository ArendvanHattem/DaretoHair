<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pricelist;

class DashboardController extends Controller
{
    public function index()
    {
        $knippen = Pricelist::where('category', 'knippen & stylen')
            ->inRandomOrder()
            ->take(2)
            ->get();

        $kleuren = Pricelist::where('category', 'kleuren')
            ->inRandomOrder()
            ->take(2)
            ->get();

        // Merge + shuffle so they’re mixed
        $services = $knippen->merge($kleuren)->shuffle();

        if (!auth()->check()) {
            return redirect()->route('register');
        }

        return view('admin.dashboard', compact('services'));
    }
}
