<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())->get();
        return view('orders.index', compact('orders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($product->stock < $request->quantity) {
            return back()->with('error', 'Stock insuffisant.');
        }

        $order = Order::create([
            'user_id' => auth()->id(),
            'status' => 'pending',
            'total_amount' => $product->price * $request->quantity,
        ]);

        $order->products()->attach($product->id, ['quantity' => $request->quantity]);

        $product->decrement('stock', $request->quantity);

        return redirect()->route('orders.index')->with('success', 'Commande passée avec succès.');
    }

    public function show(Order $order)
    {
        return view('orders.show', compact('order'));
    }
}
