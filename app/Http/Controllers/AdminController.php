<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function statistics()
    {
        // Logic pour afficher les statistiques
        return view('admin.statistics');
    }
}
