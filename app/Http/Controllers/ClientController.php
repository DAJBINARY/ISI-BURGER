<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\Burger;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    // Afficher le catalogue des burgers
    public function catalogue(Request $request)
    {
        $query = Burger::query();

        // Filtres
        if ($request->has('prix') && is_numeric($request->prix)) {
            $query->where('prix', '<=', $request->prix);
        }
        if ($request->has('libelle')) {
            $query->where('nom', 'like', '%' . $request->libelle . '%');
        }

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

        // Récupérer la quantité envoyée par le formulaire, avec une valeur par défaut de 1 si aucune quantité n'est fournie
        $quantite = $request->input('quantity', 1);

        // Vérifier que la quantité demandée est valide
        if ($quantite < 1 || $quantite > $burger->stock) {
            return redirect()->route('client.catalogue')->with('error', 'Quantité invalide.');
        }

        // Récupérer le panier de la session
        $panier = session()->get('panier', []);

        // Si le burger est déjà dans le panier, incrémenter la quantité
        if (isset($panier[$id])) {
            $panier[$id]['quantite'] += $quantite; // Ajoute la quantité au panier
        } else {
            // Sinon, ajouter le burger avec la quantité choisie
            $panier[$id] = [
                'nom' => $burger->nom,
                'prix' => $burger->prix,
                'quantite' => $quantite, // Utiliser la quantité passée par le formulaire
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

        // Afficher la vue du panier avec le panier récupéré
        return view('client.panier', compact('panier'));
    }

    // Supprimer un burger du panier
    public function supprimerDuPanier($id)
    {
        // Récupérer le panier de la session
        $panier = session()->get('panier', []);

        // Vérifier si l'élément existe dans le panier
        if (isset($panier[$id])) {
            // Supprimer l'élément du panier
            unset($panier[$id]);

            // Sauvegarder le panier mis à jour dans la session
            session()->put('panier', $panier);
            return redirect()->route('client.panier')->with('success', 'Burger supprimé du panier.');
        }

        return redirect()->route('client.panier')->with('error', 'Burger non trouvé dans le panier.');
    }

    // Passer une commande
    public function passerCommande(Request $request)
    {
        // Validation des données du panier
        $request->validate([
            'burgers' => 'required|array',
            'burgers.*.id' => 'required|exists:burgers,id',
            'burgers.*.quantity' => 'required|integer|min:1',
        ]);

        // Calcul du montant total
        $montantTotal = 0;
        $burgers = [];
        foreach ($request->burgers as $burgerData) {
            $burger = Burger::find($burgerData['id']);  // Recherche du burger dans la base de données
            if ($burger) {
                $montantTotal += $burger->prix * $burgerData['quantity'];  // Calcul du montant total
                $burgers[] = ['id' => $burger->id, 'quantity' => $burgerData['quantity']];  // Ajouter à l'array de burgers
            }
        }

        // Récupérer l'user_id depuis la session (si disponible)
        $userId = session()->get('user_id');  // Si vous stockez l'ID de l'utilisateur dans la session

        // Si aucun utilisateur n'est trouvé dans la session, vous pouvez définir un utilisateur par défaut
        if (!$userId) {
            $userId = null;  // Ou, si vous avez un utilisateur "anonyme", vous pouvez lui attribuer un ID par défaut
        }

        // Créer la commande avec l'user_id de la session (ou un utilisateur par défaut)
        $commande = Commande::create([
            'user_id'       => $userId,
            'status'        => 'En attente',
            'montant_total' => $montantTotal,
            'is_paid'       => false,
            'payment_date'  => null,
        ]);


        // Ajouter les burgers à la commande
        foreach ($burgers as $burger) {
            $commande->burgers()->attach($burger['id'], ['quantity' => $burger['quantity']]);
        }

        // Ajouter la commande à la session
        $commandes = session()->get('commandes', []);
        $commandes[] = $commande;
        session()->put('commandes', $commandes);
        dd('Commande créée');
        // Rediriger vers la page des commandes avec un message de succès
        return redirect()->route('client.commandes')->with('success', 'Commande passée avec succès.');
    }



    // Voir les commandes du client
    public function mesCommandes()
    {
        // Récupérer les commandes de la session ou un tableau vide si aucune commande n'est présente
        $commandes = session()->get('commandes', []);

        // Passer les commandes à la vue
        return view('client.commandes', compact('commandes'));
    }
    public function showBurger($id)
    {
        // Trouver le burger par son ID
        $burger = Burger::find($id);

        // Si le burger n'existe pas, rediriger vers la page de catalogue avec un message d'erreur
        if (!$burger) {
            return redirect()->route('client.catalogue')->with('error', 'Burger non trouvé.');
        }

        // Passer le burger à la vue
        return view('client.burger', compact('burger'));
    }
}
