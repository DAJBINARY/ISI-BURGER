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

        // Mettre à jour le statut de la commande
        $commande->update(['status' => $request->status]);

        // Envoyer un e-mail avec la facture en PDF si la commande est prête
        if ($request->status === 'Prête') {
            $pdf = Pdf::loadView('commandes.facture', compact('commande'));
            Mail::to($commande->user->email)->send(new CommandePreteMail($commande, $pdf));
        }

        // Déclencher le paiement si le statut est "Payée"
        if ($request->status === 'Payée') {
            // Vérifier si la commande est prête pour le paiement
            if ($commande->status !== 'Prête') {
                return back()->with('error', 'La commande n\'est pas prête pour le paiement.');
            }

            // Vérifier si la commande a déjà été payée
            if ($commande->is_paid) {
                return back()->with('error', 'La commande a déjà été payée.');
            }

            // Enregistrer le paiement
            Payment::create([
                'commande_id' => $commande->id,
                'amount' => $commande->montant_total,
                'payment_method' => 'cash', // Vous pouvez remplacer par une méthode dynamique si nécessaire
            ]);

            // Mettre à jour le statut de la commande
            $commande->update([
                'is_paid' => true,
                'payment_date' => now(),
            ]);
        }

        return redirect()->route('gestionnaire.commandes.index')->with('success', 'Statut de la commande mis à jour.');
    }
    public function statistics()
    {
        // Commandes en cours de la journée
        $commandesEnCours = Commande::whereDate('created_at', today())
            ->where('status', 'en cours')
            ->count();

        // Commandes validées de la journée
        $commandesValidees = Commande::whereDate('created_at', today())
            ->where('status', 'validée')
            ->count();

        // Recettes journalières (total des paiements reçus)
        $recettesJournalieres = Payment::whereHas('commande', function ($query) {
            $query->whereDate('created_at', today())
                ->where('status', 'validée');
        })
            ->sum('amount');

        // Nombre de commandes par mois (adapté pour PostgreSQL)
        $commandesParMois = Commande::selectRaw('EXTRACT(MONTH FROM created_at) as month, COUNT(*) as count')
            ->groupBy('month')
            ->get();

        // Nombre de burgers par nom
        $burgersParnom = Burger::selectRaw('nom, COUNT(*) as count')
            ->groupBy('nom')
            ->get();

        // Transformer les données pour Chart.js
        $chartData = [
            'commandesEnCours' => $commandesEnCours,
            'commandesValidees' => $commandesValidees,
            'recettesJournalieres' => $recettesJournalieres,
            'commandesParMois' => [
                'labels' => $commandesParMois->pluck('month')->map(function ($month) {
                    return date('F', mktime(0, 0, 0, $month, 10)); // Convertir le numéro du mois en nom (ex: 1 -> "January")
                })->toArray(),
                'counts' => $commandesParMois->pluck('count')->toArray(),
            ],
            'burgersParnom' => [
                'labels' => $burgersParnom->pluck('nom')->toArray(),
                'counts' => $burgersParnom->pluck('count')->toArray(),
            ],
        ];

        return view('gestionnaire.stat', compact('chartData'));
    }
}
