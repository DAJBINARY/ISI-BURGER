@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden mt-10 p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Image du burger -->
            <div class="flex justify-center">
                @if($burger->image)
                    <img src="{{ asset('storage/' . $burger->image) }}"
                         alt="{{ $burger->nom }}"
                         class="w-full h-96 object-cover rounded-lg shadow-lg hover:scale-105 transition-transform duration-300">
                @else
                    <div class="w-full h-96 flex items-center justify-center bg-gray-200 text-gray-500 rounded-lg">
                        Image non disponible
                    </div>
                @endif
            </div>

            <!-- Détails du burger -->
            <div class="flex flex-col justify-between">
                <div>
                    <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white">{{ $burger->nom }}</h1>
                    <p class="mt-3 text-lg text-gray-600 dark:text-gray-300">{{ $burger->description }}</p>
                    <p class="mt-4 text-2xl font-semibold text-green-500">{{ number_format($burger->prix, 2) }} Fcfa</p>
                    <p class="mt-2 text-lg {{ $burger->stock > 0 ? 'text-green-600' : 'text-red-600' }}">
                        Stock: {{ $burger->stock > 0 ? $burger->stock . ' disponibles' : 'Rupture de stock' }}
                    </p>
                </div>

                <!-- Formulaire de commande -->
                <form action="{{ route('client.ajouterAuPanier', $burger->id) }}" method="POST" class="mt-6">
                    @csrf
                    <div class="mb-3">
                        <label for="quantity" class="text-lg font-semibold">Quantité</label>
                        <input type="number" name="quantity" id="quantity" class="w-24 text-center border-2 border-gray-300 rounded-lg py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-green-500" value="1" min="1" max="{{ $burger->stock }}">
                    </div>
                    <button type="submit" class="w-full py-3 mt-4 bg-yellow-500 text-gray-900 font-semibold rounded-lg shadow-lg transform transition-all duration-300 hover:bg-yellow-600 focus:outline-none focus:ring-4 focus:ring-yellow-300 hover:scale-105">
                        Ajouter au Panier 🛒
                    </button>
                </form>

                <!-- Bouton retour -->
                <a href="{{ route('client.catalogue') }}" class="mt-4 block w-full text-center py-3 bg-gray-800 text-white font-semibold rounded-lg shadow-lg transform transition-all duration-300 hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-300 hover:scale-105">
                    Retour au catalogue
                </a>
            </div>
        </div>
    </div>
@endsection
