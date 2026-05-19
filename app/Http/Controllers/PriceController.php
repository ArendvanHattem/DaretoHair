<?php

namespace App\Http\Controllers;

use App\Models\Pricelist;
use App\Models\User;
use Illuminate\Http\Request;

class PriceController extends Controller
{
    public function publicIndex()
    {
        // Haal alle categorieën op uit de database (uniek)
        $categorieen = Pricelist::select('category')->distinct()->get();
        
        // Maak een array met alle prijzen per categorie
        $prijzenPerCategorie = [];
        foreach ($categorieen as $categorie) {
            $prijzenPerCategorie[$categorie->category] = Pricelist::where('category', $categorie->category)->get();
        }
        
        return view('prijzen', compact('prijzenPerCategorie'));
    }


    public function index()
    {
        // fetch all of the prices from the Pricelist
        $prices = Pricelist::all();

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
        $price = Pricelist::create([
            'service' => $validated['service'],
            'description' => $validated['beschrijving'],
            'amount' => $validated['prijs'],
            'category' => $validated['categorie'],
        ]);

        return redirect()->route('admin.prijzen.index')->with('success', 'Prijs succesvol aangemaakt.');
    }


    public function edit($id)
    {
        $price = Pricelist::findOrFail($id);
        return view('admin.prijzen.edit', compact('price'));
    }

    public function update(Request $request, $id)
    {
        $price = Pricelist::findOrFail($id);

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
        $price = Pricelist::findOrFail($id);
        $price->delete();

        return redirect()->route('admin.prijzen.index')->with('success', 'Prijs verwijderd.');
    }
}
