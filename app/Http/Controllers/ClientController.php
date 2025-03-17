<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\Burger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ClientController extends Controller
{
    // Afficher le catalogue des burgers
    public function catalogue(Request $request)
    {
        $query = Burger::query();

        // Filtre par prix
        if ($request->has('prix') && is_numeric($request->prix)) {
            $query->where('prix', '<=', $request->prix);
        }

        // Filtre par libellé (nom) insensible à la casse
        if ($request->has('libelle')) {
            $libelle = strtolower($request->libelle); // Convertir en minuscules
            $query->whereRaw('LOWER(nom) LIKE ?', ['%' . $libelle . '%']);
        }

        // Récupérer les burgers filtrés
        $burgers = $query->get();
        return view('client.catalogue', compact('burgers'));
    }

    // Ajouter un burger au panier
    public function ajouterAuPanier(Request $request, $id)
    {
        // Récupérer le burger avec l'ID
        $burger = Burger::find($id);

        // Vérifier si le burger existe
        if (!$burger) {
            return redirect()->route('client.catalogue')->with('error', 'Burger introuvable.');
        }

        // Récupérer la quantité envoyée par le formulaire, avec une valeur par défaut de 1
        $quantite = $request->input('quantity', 1);

        // Vérifier que la quantité demandée est valide
        if ($quantite < 1 || $quantite > $burger->stock) {
            return redirect()->route('client.catalogue')->with('error', 'Quantité invalide.');
        }

        // Récupérer le panier de la session
        $panier = session()->get('panier', []);

        // Si le burger est déjà dans le panier, incrémenter la quantité
        if (isset($panier[$id])) {
            $panier[$id]['quantite'] += $quantite;
        } else {
            // Sinon, ajouter le burger avec la quantité choisie
            $panier[$id] = [
                'nom'      => $burger->nom,
                'prix'     => $burger->prix,
                'quantite' => $quantite,
            ];
        }

        // Sauvegarder le panier mis à jour dans la session
        session()->put('panier', $panier);

        return redirect()->route('client.catalogue')->with('success', 'Burger ajouté au panier.');
    }

    // Afficher le panier
    public function afficherPanier()
    {
        // Récupérer le panier de la session
        $panier = session()->get('panier', []);
        return view('client.panier', compact('panier'));
    }

    // Supprimer un burger du panier
    public function supprimerDuPanier($id)
    {
        // Récupérer le panier de la session
        $panier = session()->get('panier', []);

        // Vérifier si l'élément existe dans le panier
        if (isset($panier[$id])) {
            unset($panier[$id]);
            session()->put('panier', $panier);
            return redirect()->route('client.panier')->with('success', 'Burger supprimé du panier.');
        }

        return redirect()->route('client.panier')->with('error', 'Burger non trouvé dans le panier.');
    }

    // Passer une commande
    public function passerCommande(Request $request)
    {
        // Récupérer le panier depuis la session
        $panier = session()->get('panier', []);
        if (empty($panier)) {
            return redirect()->route('client.panier')
                ->with('error', 'Votre panier est vide.');
        }

        $montantTotal = 0;
        $burgersCommande = [];

        try {
            // Utiliser une transaction pour garantir l'intégrité
            DB::transaction(function () use ($panier, &$montantTotal, &$burgersCommande) {
                foreach ($panier as $id => $item) {
                    // Verrouiller le burger pour éviter la concurrence sur le stock
                    $burger = \App\Models\Burger::lockForUpdate()->find($id);
                    if (!$burger) {
                        throw new \Exception("Le produit $id n'existe pas.");
                    }
                    $quantite = isset($item['quantite']) ? (int)$item['quantite'] : 1;
                    if ($burger->stock < $quantite) {
                        throw new \Exception("Le burger {$burger->nom} n'a pas suffisamment de stock.");
                    }
                    $montantTotal += $burger->prix * $quantite;
                    $burgersCommande[] = ['burger' => $burger, 'quantity' => $quantite];

                    // Mettre à jour le stock
                    $burger->stock -= $quantite;
                    $burger->save();
                }

                // Récupérer l'ID utilisateur depuis la session (null pour commande anonyme)
                $userId = session()->get('user_id', null);

                // Si aucun utilisateur n'est trouvé, créer automatiquement un utilisateur invité
                if (is_null($userId)) {
                    $anonymousUser = \App\Models\User::create([
                        'name'     => "Client " . uniqid(),
                        'email'    => "client" . uniqid() . "@example.com",
                        'password' => bcrypt(Str::random(10)),
                        'role'     => 'client',
                        'is_client'=> true,
                    ]);
                    $userId = $anonymousUser->id;
                    session()->put('user_id', $userId);
                }

                // Créer la commande dans la base de données
                $commande = \App\Models\Commande::create([
                    'user_id'       => $userId,
                    'status'        => 'En attente',
                    'montant_total' => $montantTotal,
                    'is_paid'       => false,
                    'payment_date'  => null,
                ]);

                // Attacher les burgers à la commande via la table pivot
                foreach ($burgersCommande as $burgerData) {
                    $commande->burgers()->attach($burgerData['burger']->id, [
                        'quantity' => $burgerData['quantity']
                    ]);
                }

                // En option, enregistrer la commande dans la session pour affichage ultérieur
                $commandes = session()->get('commandes', []);
                $commandes[] = $commande;
                session()->put('commandes', $commandes);
            });
        } catch (\Exception $e) {
            \Log::error("Erreur lors du passage de commande : " . $e->getMessage());
            return redirect()->route('client.panier')
                ->with('error', $e->getMessage());
        }

        // Vider le panier après commande réussie
        session()->forget('panier');

        return redirect()->route('client.commandes')
            ->with('success', 'Commande passée avec succès.');
    }



    // Voir les commandes du client
    public function mesCommandes()
    {
        $commandes = Commande::where('user_id', session()->get('user_id'))->latest()->get();

        return view('client.commandes', compact('commandes'));
    }


    // Afficher un burger (détail)
    public function showBurger($id)
    {
        $burger = Burger::find($id);
        if (!$burger) {
            return redirect()->route('client.catalogue')->with('error', 'Burger non trouvé.');
        }
        return view('client.burger', compact('burger'));
    }
}
