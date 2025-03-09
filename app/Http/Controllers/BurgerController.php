<?php

namespace App\Http\Controllers;

use App\Models\Burger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BurgerController extends Controller
{
    public function index()
    {
        $burgers = Burger::all();
        return view('burgers.index', compact('burgers'));
    }

    public function create()
    {
        return view('burgers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required',
            'prix' => 'required|numeric',
            'description' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'stock' => 'required|integer',
        ]);

        // Gestion de l'upload de l'image
        $imagePath = $request->hasFile('image') ? $request->file('image')->store('burgers', 'public') : null;

        // Création du burger
        Burger::create([
            'nom' => $request->nom,
            'prix' => $request->prix,
            'description' => $request->description,
            'image' => $imagePath,
            'stock' => $request->stock,
        ]);

        return redirect()->route('burgers.index')->with('success', 'Burger créé avec succès.');
    }

    public function show(Burger $burger)
    {
        return view('burgers.show', compact('burger'));
    }

    public function edit(Burger $burger)
    {
        return view('burgers.edit', compact('burger'));
    }

    public function update(Request $request, Burger $burger)
    {
        $request->validate([
            'nom' => 'required',
            'prix' => 'required|numeric',
            'description' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'stock' => 'required|integer',
        ]);

        // Gestion de la mise à jour de l'image
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si elle existe
            if ($burger->image) {
                Storage::disk('public')->delete($burger->image);
            }

            // Stocker la nouvelle image
            $imagePath = $request->file('image')->store('burgers', 'public');
            $burger->image = $imagePath;
        }

        // Mise à jour des autres champs
        $burger->update([
            'nom' => $request->nom,
            'prix' => $request->prix,
            'description' => $request->description,
            'image' => $burger->image, // Conserver l'image existante si aucune nouvelle n'est envoyée
            'stock' => $request->stock,
        ]);

        return redirect()->route('burgers.index')->with('success', 'Burger mis à jour avec succès.');
    }

    public function destroy(Burger $burger)
    {
        // Supprimer l'image si elle existe
        if ($burger->image) {
            Storage::disk('public')->delete($burger->image);
        }

        // Supprimer le burger
        $burger->delete();

        return redirect()->route('burgers.index')->with('success', 'Burger supprimé avec succès.');
    }
}
