<?php

namespace App\Http\Controllers;

use App\Models\Burger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BurgerController extends Controller
{
    public function index(Request $request)
    {
        $query = Burger::query();

        // Filtre par prix
        if ($request->has('prix') && is_numeric($request->prix)) {
            $query->where('prix', '<=', $request->prix);
        }
        // Filtre par libellé (nom) insensible à la casse
        if ($request->has('libelle')) {
            $libelle = strtolower($request->libelle);
            $query->whereRaw('LOWER(nom) LIKE ?', ['%' . $libelle . '%']);
        }
        $burgers = $query->get();
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'stock' => 'required|integer',
        ]);

        $imagePath = $request->hasFile('image') ? $request->file('image')->store('burgers', 'public') : null;

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
            if ($burger->image) {
                Storage::disk('public')->delete($burger->image);
            }
            // Stocker la nouvelle image
            $imagePath = $request->file('image')->store('burgers', 'public');
            $burger->image = $imagePath;
        }

        $burger->update([
            'nom' => $request->nom,
            'prix' => $request->prix,
            'description' => $request->description,
            'image' => $burger->image,
            'stock' => $request->stock,
        ]);
        return redirect()->route('burgers.index')->with('success', 'Burger mis à jour avec succès.');
    }

    public function destroy(Burger $burger)
    {
        if ($burger->stock > 0) {
            return redirect()->route('burgers.index')->with('error', 'Impossible de supprimer un burger ayant du stock.');
        }

        if ($burger->image) {
            Storage::disk('public')->delete($burger->image);
        }

        $burger->delete();

        return redirect()->route('burgers.index')->with('success', 'Burger supprimé avec succès.');
    }

}
