<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'ISI BURGER') }}</title>

    <!-- Styles & Scripts via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/alpinejs@3.x.x" defer></script>
</head>
<body class="bg-white text-black">
<!-- Barre de navigation intégrée en Tailwind et Alpine.js avec fond noir -->
<nav x-data="{ open: false }" class="bg-black shadow-sm fixed top-0 w-full z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="text-xl font-bold text-white">ISI BURGER 🍔</a>
            <!-- Menu de bureau -->
            <div class="hidden md:flex space-x-6">
                @guest
                    <a href="{{ route('client.catalogue') }}" class="text-gray-300 hover:text-white">Catalogue</a>
                    <a href="{{ route('client.commandes') }}" class="text-gray-300 hover:text-white">Mes Commandes</a>
                    <a href="{{ route('client.panier') }}" class="relative text-gray-300 hover:text-white">
                        Panier 🛒
                        <span class="absolute -top-2 -right-3 bg-red-600 text-white text-xs rounded-full px-1">
                                {{ count(session('panier', [])) }}
                            </span>
                    </a>
                    <a href="{{ route('login') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Se connecter
                    </a>
                @else
                    <a href="{{ route('burgers.index') }}" class="text-gray-300 hover:text-white">Catalogue</a>
                    <a href="{{ route('gestionnaire.commandes.index') }}" class="text-gray-300 hover:text-white">Gestions commandes</a>
                    <a href="{{ route('admin.statistics') }}" class="text-gray-300 hover:text-white">Voir les statistiques</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Se déconnecter
                        </button>
                    </form>
                @endguest
            </div>
            <!-- Bouton menu mobile -->
            <div class="md:hidden">
                <button @click="open = !open" class="text-gray-300 hover:text-white focus:outline-none">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16" />
                        <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
    <!-- Menu mobile -->
    <div x-show="open" class="md:hidden">
        <div class="px-2 pt-2 pb-3 space-y-1">
            @guest
                <a href="{{ route('client.catalogue') }}" class="block text-gray-300 hover:text-white">
                    Catalogue
                </a>
                <a href="{{ route('client.commandes') }}" class="block text-gray-300 hover:text-white">
                    Mes Commandes
                </a>
                <a href="{{ route('client.panier') }}" class="block relative text-gray-300 hover:text-white">
                    Panier 🛒
                    <span class="absolute -top-2 -right-3 bg-red-600 text-white text-xs rounded-full px-1">
                            {{ count(session('panier', [])) }}
                        </span>
                </a>
                <a href="{{ route('login') }}" class="block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Se connecter
                </a>
            @else
                <a href="{{ route('burgers.index') }}" class="block text-gray-300 hover:text-white">
                    Catalogue
                </a>
                <a href="{{ route('gestionnaire.commandes.index') }}" class="block text-gray-300 hover:text-white">
                    Gestions commandes
                </a>
                <a href="{{ route('gestionnaire.stat') }}" class="block text-gray-300 hover:text-white">
                    Voir les statistiques
                </a>
                <form method="POST" action="{{ route('logout') }}" class="block">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Se déconnecter
                    </button>
                </form>
            @endguest
        </div>
    </div>
</nav>

<!-- Espace pour éviter que le contenu soit masqué par la navbar fixe -->
<div class="mt-20">
    <div class="container mx-auto px-4">
        @yield('content')
    </div>
</div>

@include('layouts.footer')
</body>
</html>
