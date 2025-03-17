@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto mt-6">
        <h1 class="text-3xl font-bold text-center text-gray-900 dark:text-black mb-6">🍔 Catalogue des Burgers</h1>
        <!-- Bouton Ajouter un Burger -->
        <div class="flex justify-end mb-4">
            <a href="{{ route('burgers.create') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg shadow">
                ➕ Ajouter un Burger
            </a>
        </div>
        <!-- Barre de recherche et filtre -->
        <form action="{{ route('burgers.index') }}" method="GET" class="flex flex-wrap justify-center gap-4 mb-6">
            <input type="text" name="libelle" placeholder="Rechercher par nom..." class="w-64 p-2 border rounded-lg shadow-sm focus:ring focus:ring-blue-300">
            <input type="number" name="prix" placeholder="Prix max" class="w-32 p-2 border rounded-lg shadow-sm focus:ring focus:ring-blue-300">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow">Filtrer</button>
        </form>

        <!-- Affichage des burgers -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            @foreach($burgers as $burger)
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg hover:shadow-2xl transition duration-300">
                    @if($burger->image)
                        <img src="{{ Storage::url($burger->image) }}" alt="{{ $burger->nom }}" class="w-full h-48 object-cover rounded-t-lg">
                    @endif
                    <div class="p-4">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $burger->nom }}</h2>
                        <p class="text-gray-600 dark:text-gray-300 mt-2">{{ Str::limit($burger->description, 100) }}</p>
                        <div class="mt-3 flex justify-between items-center">
                            <span class="text-lg font-bold text-blue-600">{{ number_format($burger->prix, 2) }} Fcfa</span>
                            <div class="flex gap-2">
                                <a href="{{ route('burgers.show', $burger) }}" class="text-blue-600 hover:text-blue-800 font-semibold transition">Voir ➝</a>
                                <a href="{{ route('burgers.edit', $burger) }}" class="text-yellow-600 hover:text-yellow-800 font-semibold transition">Modifier</a>
                                <form action="{{ route('burgers.destroy', $burger) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce burger ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 font-semibold transition">Supprimer</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
