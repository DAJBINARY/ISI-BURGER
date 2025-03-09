@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8">
        <h1 class="text-4xl font-extrabold text-center mb-8">Notre Catalogue de Burgers</h1>

        <!-- Formulaire de recherche et de filtre -->
        <form action="{{ route('client.catalogue') }}" method="GET" class="flex flex-col md:flex-row gap-4 mb-8">
            <input type="text" name="libelle" placeholder="Rechercher par nom..."
                   class="w-full md:w-1/2 px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            <input type="number" name="prix" placeholder="Prix max"
                   class="w-full md:w-1/4 px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            <button type="submit"
                    class="w-full md:w-auto px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                Filtrer
            </button>
        </form>

        <!-- Grille des burgers -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($burgers as $burger)
                <div class="bg-white rounded-lg overflow-hidden shadow-lg hover:shadow-2xl transition-shadow duration-300">
                    @if($burger->image)
                        <div class="w-full h-56 bg-gray-200">
                            <img src="{{ Storage::url($burger->image) }}" alt="{{ $burger->nom }}"
                                 class="w-full h-full object-cover block">
                        </div>
                    @else
                        <div class="w-full h-56 bg-gray-200 flex items-center justify-center text-gray-500">
                            Image non disponible
                        </div>
                    @endif
                    <div class="p-6">
                        <h2 class="text-2xl font-bold mb-2">{{ $burger->nom }}</h2>
                        <p class="text-gray-600 mb-4">{{ Str::limit($burger->description, 100) }}</p>
                        <div class="flex items-center justify-between">
                            <span class="text-xl font-semibold">{{ number_format($burger->prix, 2) }} Fcfa</span>
                            <a href="{{ route('client.burger', $burger) }}"
                               class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition-colors">
                                Voir
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
