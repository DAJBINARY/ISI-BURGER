<?php
namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\Burger;
use Illuminate\Http\Request;

class CommandeController extends Controller
{
    public function index()
    {
        $commandes = Commande::paginate(10); // Récupérer toutes les commandes
        return view('commandes.index', compact('commandes'));
    }

    public function show(Commande $commande)
    {
        return view('commandes.show', compact('commande'));
    }

    public function updateStatus(Request $request, Commande $commande)
    {
        $request->validate([
            'status' => 'required|in:En attente,En préparation,Prête,Payée',
        ]);

        $commande->update(['status' => $request->status]);
        return redirect()->route('commandes.index')->with('success', 'Statut mis à jour.');
    }
}

