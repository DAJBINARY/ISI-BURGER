<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function store(Request $request, Commande $commande)
    {
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
            'payment_method' => 'cash',
        ]);

        // Mettre à jour le statut de la commande
        $commande->update([
            'status' => 'Payée',
            'is_paid' => true,
            'payment_date' => now(),
        ]);

        return redirect()->route('commandes.index')->with('success', 'Paiement enregistré avec succès.');
    }
}
