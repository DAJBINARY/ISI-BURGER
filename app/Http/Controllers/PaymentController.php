<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function store(Request $request, Order $order)
    {
        if ($order->status !== 'ready') {
            return back()->with('error', 'La commande n\'est pas prête pour le paiement.');
        }

        if ($order->payment) {
            return back()->with('error', 'La commande a déjà été payée.');
        }

        Payment::create([
            'order_id' => $order->id,
            'amount' => $order->total_amount,
            'payment_method' => 'cash',
        ]);

        $order->update(['status' => 'paid']);

        return redirect()->route('orders.index')->with('success', 'Paiement enregistré avec succès.');
    }
}
