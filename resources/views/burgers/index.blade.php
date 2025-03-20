@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto mt-6">
        <h1 class="text-3xl font-bold text-center text-gray-900 dark:text-black mb-6">🍔 Catalogue des Burgers</h1>

        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-4 rounded mb-4 text-center">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 text-red-700 p-4 rounded mb-4 text-center">
                {{ session('error') }}
            </div>
        @endif

        <div class="flex justify-end mb-4">
            <a href="{{ route('burgers.create') }}" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-lg shadow-lg flex items-center space-x-2 transition transform duration-300 hover:scale-105">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m7-7H5" />
                </svg>
                <span>Ajouter un Burger</span>
            </a>
        </div>

        <!-- Barre de recherche et filtre -->
        <form action="{{ route('burgers.index') }}" method="GET" class="flex flex-wrap justify-center gap-4 mb-6">
            <input type="text" name="libelle" placeholder="Rechercher par nom..." class="w-64 p-2 border rounded-lg shadow-sm focus:ring focus:ring-blue-300">
            <input type="number" name="prix" placeholder="Prix max" class="w-32 p-2 border rounded-lg shadow-sm focus:ring focus:ring-blue-300">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg shadow-lg transition duration-300 hover:scale-105">
                Filtrer
            </button>
        </form>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($burgers as $burger)
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg hover:shadow-2xl transition duration-300 max-w-xs mx-auto">
                    @if($burger->image)
                        <img src="{{ Storage::url($burger->image) }}" alt="{{ $burger->nom }}" class="w-full h-48 object-cover rounded-t-lg transition-all duration-500 hover:scale-105">
                    @endif
                    <div class="p-4">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $burger->nom }}</h2>
                        <p class="text-gray-600 dark:text-gray-300 mt-2">{{ Str::limit($burger->description, 100) }}</p>
                        <div class="mt-3 flex justify-between items-center">
                            <span class="text-lg font-bold text-blue-600">{{ number_format($burger->prix, 2) }} Fcfa</span>
                            <div class="flex gap-2">
                                <a href="{{ route('burgers.show', $burger) }}" class="text-blue-600 hover:text-blue-800 font-semibold transition transform duration-300 hover:scale-105">Voir ➝</a>
                                <a href="{{ route('burgers.edit', $burger) }}" class="text-yellow-600 hover:text-yellow-800 font-semibold transition transform duration-300 hover:scale-105">Modifier</a>
                                <form action="{{ route('burgers.destroy', $burger) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce burger ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 font-semibold transition transform duration-300 hover:scale-105">Supprimer</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
