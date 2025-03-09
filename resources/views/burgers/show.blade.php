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

                <!-- Boutons -->
                <div class="mt-6 flex space-x-4">
                    <a href="{{ route('burgers.index') }}" class="btn btn-secondary px-6 py-3 rounded-lg shadow">
                        Retour au catalogue
                    </a>
                    @if($burger->stock > 0)
                        <button class="btn btn-primary px-6 py-3 rounded-lg shadow-lg hover:bg-blue-700">
                            Ajouter au panier 🛒
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
