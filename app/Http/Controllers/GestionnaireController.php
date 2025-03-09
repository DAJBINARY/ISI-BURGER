<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use App\Mail\CommandePreteMail;
use Illuminate\Support\Facades\DB;

class GestionnaireController extends Controller
{
    // Lister toutes les commandes
    public function index()
    {
        $commandes = Commande::all();
        return view('gestionnaire.commandes.index', compact('commandes'));
    }

    // Voir les détails d'une commande
    public function show(Commande $commande)
    {
        return view('gestionnaire.commandes.show', compact('commande'));
    }

    // Annuler une commande
    public function annuler(Commande $commande)
    {
        $commande->update(['status' => 'Annulée']);
        return redirect()->route('gestionnaire.commandes.index')->with('success', 'Commande annulée avec succès.');
    }

    // Modifier le statut d'une commande
    public function updateStatus(Request $request, Commande $commande)
    {
        $request->validate([
            'status' => 'required|in:En attente,En préparation,Prête,Payée',
        ]);

        $commande->update(['status' => $request->status]);

        // Envoyer un e-mail avec la facture en PDF si la commande est prête
        if ($request->status === 'Prête') {
            $pdf = Pdf::loadView('commandes.facture', compact('commande'));
            Mail::to($commande->user->email)->send(new CommandePreteMail($commande, $pdf));
        }

        return redirect()->route('gestionnaire.commandes.index')->with('success', 'Statut de la commande mis à jour.');
    }
    public function statistics()
    {
        // Récupérer les statistiques des commandes par statut
        $stats = DB::table('commandes')
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        // Transformer les données pour Chart.js
        $chartData = [
            'labels' => $stats->pluck('status')->toArray(),
            'counts' => $stats->pluck('count')->toArray(),
        ];

        return view('gestionnaire.stat', compact('chartData'));
    }
}
