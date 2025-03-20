@extends('layouts.app')

@section('content')
    <div class="bg-gradient-to-r from-blue-500 to-green-500 min-h-screen flex items-center justify-center">
        <div class="text-center text-white p-10 md:p-16 rounded-lg shadow-2xl bg-opacity-90 backdrop-blur-lg">
            <!-- Titre animé -->
            <h1 class="text-6xl font-extrabold mb-6 animate__animated animate__fadeIn animate__delay-1s">
                Bienvenue chez ISI BURGER!
            </h1>
            <!-- Sous-titre animé -->
            <p class="text-2xl mb-8 animate__animated animate__fadeIn animate__delay-2s">
                Découvre notre catalogue de burgers savoureux, avec des offres irrésistibles!
            </p>
            <!-- Burger animé -->
            <div class="burger relative inline-block animate__animated animate__fadeIn animate__delay-3s">
                <div class="top-bun w-40 h-8 bg-brown-500 rounded-full mx-auto mb-2"></div>
                <div class="lettuce w-40 h-4 bg-green-500 rounded-full mx-auto mb-2"></div>
                <div class="cheese w-40 h-5 bg-yellow-400 rounded-full mx-auto mb-2"></div>
                <div class="patty w-40 h-6 bg-brown-700 rounded-full mx-auto mb-2"></div>
                <div class="bottom-bun w-40 h-8 bg-brown-500 rounded-full mx-auto"></div>
            </div>
            <!-- Boutons animés -->
            <div class="flex justify-center gap-6 animate__animated animate__fadeIn animate__delay-4s">
                @auth
                    <a href="{{ route('burgers.index') }}"
                       class="inline-block px-10 py-4 bg-yellow-500 text-gray-900 font-semibold rounded-lg shadow-lg transform hover:scale-110 transition-all duration-500 hover:bg-yellow-600 focus:outline-none focus:ring-4 focus:ring-yellow-300">
                        Voir les burgers
                    </a>
                @else
                    <a href="{{ route('client.catalogue') }}"
                       class="inline-block px-10 py-4 bg-yellow-500 text-gray-900 font-semibold rounded-lg shadow-lg transform hover:scale-110 transition-all duration-500 hover:bg-yellow-600 focus:outline-none focus:ring-4 focus:ring-yellow-300">
                        Voir le catalogue
                    </a>
                @endauth
            </div>
        </div>
    </div>
@endsection
