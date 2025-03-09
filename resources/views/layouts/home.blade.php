@extends('layouts.app')

@section('content')
    <div class="bg-gradient-to-r from-blue-500 to-green-500 min-h-screen flex items-center justify-center">
        <div class="text-center text-white p-8 rounded-lg shadow-lg">
            <!-- Titre -->
            <h1 class="text-5xl font-extrabold mb-6 animate__animated animate__fadeIn animate__delay-1s">
                Bienvenue chez ISI BURGER!
            </h1>
            <!-- Sous-titre -->
            <p class="text-xl mb-6 animate__animated animate__fadeIn animate__delay-1s">
                Découvre notre catalogue de burgers savoureux.
            </p>
            <!-- Bouton pour voir le catalogue -->
            <a href="{{ route('client.catalogue') }}"
               class="inline-block px-8 py-3 bg-yellow-400 text-gray-800 font-semibold rounded-lg shadow-md transform hover:scale-105 transition-all duration-300 hover:bg-yellow-500 focus:outline-none focus:ring-4 focus:ring-yellow-300 animate__animated animate__fadeIn animate__delay-2s">
                Voir le catalogue
            </a>
        </div>
    </div>
@endsection
