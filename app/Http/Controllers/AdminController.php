<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $ordersToday = Order::whereDate('created_at', today())->count();
        $revenueToday = Order::whereDate('created_at', today())->sum('total_amount');
        $products = Product::all();

        return view('admin.dashboard', compact('ordersToday', 'revenueToday', 'products'));
    }
}
