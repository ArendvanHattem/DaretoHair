<?php

namespace App\Http\Controllers;

use App\Models\pricelist;
use App\Models\User;
use Illuminate\Http\Request;

class PriceController extends Controller
{
    public function publicIndex()
    {

        // fetch all of the prices from the pricelist
        $prices = pricelist::all();

        // save pricelists based on categories in variables and return to view
        $knippen_stylen = pricelist::where('category', 'knippen & stylen')->get();
        $kleuren = pricelist::where('category', 'kleuren')->get();

        return view('prijzen', compact('knippen_stylen', 'kleuren'));
    }


    public function index()
    {
        // fetch all of the prices from the pricelist
        $prices = pricelist::all();

        return view('admin.prijzen.index', compact('prices'));
    }

    public function create()
    {
        return view('admin.prijzen.create');
    }

    public function store(Request $request)
    {
        // 1. Validatie
        $validated = $request->validate([
            'service' => 'required|string|max:255',
            'beschrijving' => 'required|string|max:255',
            'prijs' => 'required|numeric|min:0|max:99999.99',
            'categorie' => 'required|string|max:255',
        ]);

        // 2. Gebruiker aanmaken
        $price = pricelist::create([
            'service' => $validated['service'],
            'description' => $validated['beschrijving'],
            'amount' => $validated['prijs'],
            'category' => $validated['categorie'],
        ]);

        return redirect()->route('admin.prijzen.index')->with('success', 'Prijs succesvol aangemaakt.');
    }


    public function edit($id)
    {
        $price = pricelist::findOrFail($id);
        return view('admin.prijzen.edit', compact('price'));
    }

    public function update(Request $request, $id)
    {
        $price = pricelist::findOrFail($id);

        // 1. Validatie
        $validated = $request->validate([
            'service' => 'required|string|max:255',
            'beschrijving' => 'required|string|max:255',
            'prijs' => 'required|numeric|min:0|max:99999.99',
            'categorie' => 'required|string|max:255',
        ]);

        // 2. Prijs updaten
        $price->update([
            'service'     => $validated['service'],
            'description' => $validated['beschrijving'],
            'amount'      => $validated['prijs'],
            'category'    => $validated['categorie'],
        ]);

        return redirect()->route('admin.prijzen.index')->with('success', 'Prijs succesvol aangemaakt.');
    }

    public function destroy($id)
    {
        $price = pricelist::findOrFail($id);
        $price->delete();

        return redirect()->route('admin.prijzen.index')->with('success', 'Prijs verwijderd.');
    }
}
