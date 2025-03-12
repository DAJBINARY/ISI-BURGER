<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BurgerController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\GestionnaireController;
use App\Http\Controllers\PaymentController;

/*
|--------------------------------------------------------------------------
| Routes Web de l'application ISI BURGER
|--------------------------------------------------------------------------
|
| Ici on définit les routes pour le front (catalogue, commandes client) et
| l'administration (gestion des burgers, commandes, statistiques).
|
*/

// Page d'accueil
Route::get('/', function () {
    return view('layouts.home');
})->name('home');

// Dashboard utilisateur (auth et vérifié) dans notre cas le Gestionnaire
Route::get('/dashboard', function () {
    return redirect()->route('burgers.index');
})->middleware(['auth', 'verified'])->name('dashboard');

// Routes accessibles aux utilisateurs authentifiés
Route::middleware('auth')->group(function () {
    // Gestion du profil utilisateur
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

// Gestion des commandes (pour annuler ou modifier le statut)
    Route::get('/gestionnaire/commandes', [GestionnaireController::class, 'index'])->name('gestionnaire.commandes.index');
    Route::get('/gestionnaire/commandes/{commande}', [GestionnaireController::class, 'show'])->name('gestionnaire.commandes.show');
    Route::put('/gestionnaire/commandes/{commande}/annuler', [GestionnaireController::class, 'annuler'])->name('gestionnaire.commandes.annuler');
    Route::post('/gestionnaire/commandes/{commande}/update-status', [GestionnaireController::class, 'updateStatus'])->name('gestionnaire.commandes.updateStatus');
// Gestion des burgers (CRUD)
    Route::resource('burgers', BurgerController::class);
    Route::get('/gestionnaire/statistiques', [GestionnaireController::class, 'statistics'])->name('gestionnaire.stat');
    Route::get('/admin/statistiques', [GestionnaireController::class, 'statistics'])->name('admin.statistics');

});
// Routes pour les clients
Route::get('/catalogue', [ClientController::class, 'catalogue'])->name('client.catalogue');
Route::get('/burger/{burger}', [ClientController::class, 'showBurger'])->name('client.burger');
Route::post('/commande/passer', [ClientController::class, 'passerCommande'])->name('client.passerCommande');
Route::get('/mes-commandes', [ClientController::class, 'mesCommandes'])->name('client.commandes');
Route::delete('/supprimer-commande/{id}', [ClientController::class, 'supprimerCommande'])->name('client.supprimerCommande');

// Routes pour le panier
Route::get('/panier', [ClientController::class, 'afficherPanier'])->name('client.panier');
Route::post('/ajouter-au-panier/{id}', [ClientController::class, 'ajouterAuPanier'])->name('client.ajouterAuPanier');
Route::delete('/panier/supprimer/{id}', [ClientController::class, 'supprimerDuPanier'])->name('client.panier.supprimer');

require __DIR__.'/auth.php';
