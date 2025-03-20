@extends('layouts.app')

@section('content')
    <div class="container mx-auto">
        <!-- Affichage des messages de succès et d'erreur -->
        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-4 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <h1 class="text-3xl font-bold text-center mb-6">Mon Panier</h1>

        @if(session('panier') && count(session('panier')) > 0)
            <div class="bg-white shadow-md rounded-lg p-6">
                <table class="min-w-full table-auto">
                    <thead>
                    <tr class="border-b">
                        <th class="py-2 px-4 text-left">Produit</th>
                        <th class="py-2 px-4 text-left">Prix Unitaire</th>
                        <th class="py-2 px-4 text-left">Quantité</th>
                        <th class="py-2 px-4 text-left">Total</th>
                        <th class="py-2 px-4 text-left">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach(session('panier') as $id => $item)
                        <tr class="border-b">
                            <td class="py-2 px-4">{{ $item['nom'] }}</td>
                            <td class="py-2 px-4">{{ number_format($item['prix'], 2) }} FCFA</td>
                            <td class="py-2 px-4">
                                {{ isset($item['quantite']) ? $item['quantite'] : 0 }}
                            </td>
                            <td class="py-2 px-4">{{ number_format($item['prix'] * (isset($item['quantite']) ? $item['quantite'] : 1), 2) }} FCFA</td>
                            <td class="py-2 px-4">
                                <form action="{{ route('client.panier.supprimer', $id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <!-- Total du panier -->
                <div class="mt-6 flex justify-between items-center">
                    <span class="text-xl font-semibold">Total : </span>
                    <span class="text-xl font-semibold">
                        {{ number_format(array_sum(array_map(function ($item) {
                            return $item['prix'] * (isset($item['quantite']) ? $item['quantite'] : 1);
                        }, session('panier'))), 2) }} FCFA
                    </span>
                </div>

                <!-- Formulaire pour passer la commande -->
                <div class="mt-6 text-right">
                    <form action="{{ route('client.passerCommande') }}" method="POST">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Passer Commande
                        </button>
                    </form>
                </div>
            </div>
        @else
            <div class="bg-white shadow-md rounded-lg p-6 text-center">
                <p class="text-xl font-semibold text-gray-500">Votre panier est vide.</p>
                <a href="{{ route('client.catalogue') }}" class="mt-4 inline-block px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                    Retourner au Catalogue
                </a>
            </div>
        @endif
    </div>
@endsection
