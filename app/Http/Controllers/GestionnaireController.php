<?php

namespace App\Http\Controllers;
use App\Models\Burger;
use App\Models\Commande;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use App\Mail\CommandePreteMail;
use Illuminate\Support\Facades\DB;
use App\Models\Payment;

class GestionnaireController extends Controller
{
    public function index()
    {
        $commandes = Commande::all();
        return view('gestionnaire.commandes.index', compact('commandes'));
    }

    public function show(Commande $commande)
    {
        return view('gestionnaire.commandes.show', compact('commande'));
    }

    public function annuler(Commande $commande)
    {
        if ($commande->status !== 'En attente') {
            return back()->with('error', 'Seules les commandes en attente peuvent être annulées.');
        }
        $commande->update(['status' => 'Annulée']);

        return redirect()->route('gestionnaire.commandes.index')->with('success', 'Commande annulée avec succès.');
    }


    public function updateStatus(Request $request, Commande $commande)
    {
        $request->validate([
            'status' => 'required|in:En attente,En préparation,Prête,Payée',
        ]);

        // Vérifier si la commande a déjà été payée
        if ($commande->status === 'Payée') {
            return back()->with('error', 'La commande a déjà été payée. Aucune mise à jour n’est possible.');
        }

        // Vérifier si la commande est "Payée" alors qu'elle n'est pas encore "Prête"
        if ($request->status === 'Payée' && $commande->status !== 'Prête') {
            return back()->with('error', 'La commande doit être "Prête" avant d’être payée.');
        }

        // Si la commande est marquée "Payée", l'empêcher d'être mise à jour à nouveau
        if ($request->status === 'Payée' && $commande->is_paid) {
            return back()->with('error', 'La commande a déjà été payée.');
        }

        // Mise à jour du statut
        $commande->update(['status' => $request->status]);

        // Vérifier si la commande passe à "Prête" et envoyer l'email
        if ($request->status === 'Prête') {
            $pdf = Pdf::loadView('commandes.facture', compact('commande'));
            Mail::to($commande->user->email)->send(new CommandePreteMail($commande, $pdf));
        }

        // Mise à jour du paiement si le statut est "Payée"
        if ($request->status === 'Payée') {
            $commande->update([
                'is_paid' => true,
                'payment_date' => now(),
            ]);
        }

        return redirect()->route('gestionnaire.commandes.index')->with('success', 'Statut de la commande mis à jour.');
    }



    public function statistics()
    {
        $commandesEnCours = Commande::whereDate('created_at', today())
            ->where('status', 'En attente')
            ->count();

        $commandesValidees = Commande::whereDate('created_at', today())
            ->where('status', 'Payée')
            ->count();

        $recettesJournalieres = Commande::whereDate('created_at', today())
            ->where('status', 'payée')
            ->sum('montant_total');

        // Nombre de commandes par mois (adapté pour PostgreSQL)
        $commandesParMois = Commande::selectRaw('EXTRACT(MONTH FROM created_at) as month, COUNT(*) as count')
            ->groupBy('month')
            ->get();

        $burgersParnom = Burger::selectRaw('nom, SUM(stock) as stock')
            ->groupBy('nom')
            ->get();


        $chartData = [
            'commandesEnCours' => $commandesEnCours,
            'commandesValidees' => $commandesValidees,
            'recettesJournalieres' => $recettesJournalieres,
            'commandesParMois' => [
                'labels' => $commandesParMois->pluck('month')->map(function ($month) {
                    return date('F', mktime(0, 0, 0, $month, 30)); // Convertir le numéro du mois en nom.
                })->toArray(),
                'counts' => $commandesParMois->pluck('count')->toArray(),
            ],
            'burgersParnom' => [
                'labels' => $burgersParnom->pluck('nom')->toArray(),
                'counts' => $burgersParnom->pluck('stock')->toArray(),
            ],
        ];

        return view('gestionnaire.stat', compact('chartData'));
    }

}
