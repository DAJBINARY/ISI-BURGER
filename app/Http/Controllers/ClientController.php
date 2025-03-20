<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\Burger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ClientController extends Controller
{
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
        $burgers = $query->get();
        return view('client.catalogue', compact('burgers'));
    }

    public function ajouterAuPanier(Request $request, $id)
    {
        $burger = Burger::find($id);
        // Vérifier si le burger existe
        if (!$burger) {
            return redirect()->route('client.catalogue')->with('error', 'Burger introuvable.');
        }
        $quantite = $request->input('quantity', 1);

        // Vérifier que la quantité demandée est valide
        if ($quantite < 1 || $quantite > $burger->stock) {
            return redirect()->route('client.catalogue')->with('error', 'Quantité invalide.');
        }

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
        session()->put('panier', $panier);

        return redirect()->route('client.catalogue')->with('success', 'Burger ajouté au panier.');
    }

    public function afficherPanier()
    {
        $panier = session()->get('panier', []);
        return view('client.panier', compact('panier'));
    }

    public function supprimerDuPanier($id)
    {
        $panier = session()->get('panier', []);

        // Vérifier si l'élément existe dans le panier
        if (isset($panier[$id])) {
            unset($panier[$id]);
            session()->put('panier', $panier);
            return redirect()->route('client.panier')->with('success', 'Burger supprimé du panier.');
        }

        return redirect()->route('client.panier')->with('error', 'Burger non trouvé dans le panier.');
    }

    public function passerCommande(Request $request)
    {
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

                    $burger->stock -= $quantite;
                    $burger->save();
                }

                $userId = session()->get('user_id', null);
                if (is_null($userId)) {
                    $anonymousUser = \App\Models\User::create([
                        'name'     => "Client " . uniqid(),
                        'email'    => "client" . uniqid() . "@isi-burger.com",
                        'password' => bcrypt(Str::random(10)),
                        'role'     => 'client',
                        'is_client'=> true,
                    ]);
                    $userId = $anonymousUser->id;
                    session()->put('user_id', $userId);
                }

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

                $commandes = session()->get('commandes', []);
                $commandes[] = $commande;
                session()->put('commandes', $commandes);
            });
        } catch (\Exception $e) {
            \Log::error("Erreur lors du passage de commande : " . $e->getMessage());
            return redirect()->route('client.panier')
                ->with('error', $e->getMessage());
        }
        session()->forget('panier');

        return redirect()->route('client.commandes')
            ->with('success', 'Commande passée avec succès.');
    }

    public function mesCommandes()
    {
        $commandes = Commande::where('user_id', session()->get('user_id'))->latest()->get();
        return view('client.commandes', compact('commandes'));
    }

    public function showBurger($id)
    {
        $burger = Burger::find($id);
        if (!$burger) {
            return redirect()->route('client.catalogue')->with('error', 'Burger non trouvé.');
        }
        return view('client.burger', compact('burger'));
    }
}
