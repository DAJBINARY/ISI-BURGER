@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden mt-10 p-6">

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

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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

            <div class="flex flex-col justify-between">
                <div>
                    <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white">{{ $burger->nom }}</h1>
                    <p class="mt-3 text-lg text-gray-600 dark:text-gray-300">{{ $burger->description }}</p>
                    <p class="mt-4 text-2xl font-semibold text-green-500">{{ number_format($burger->prix, 2) }} Fcfa</p>
                    <p class="mt-2 text-lg {{ $burger->stock > 0 ? 'text-green-600' : 'text-red-600' }}">
                        Stock: {{ $burger->stock > 0 ? $burger->stock . ' disponibles' : 'Rupture de stock' }}
                    </p>
                </div>

                <div class="mt-6">
                    <a href="{{ route('burgers.index') }}" class="px-6 py-3 text-white bg-gray-800 hover:bg-gray-900 rounded-lg shadow transition duration-300 flex items-center">
                        &#8592; Retour au catalogue
                    </a>
                </div>

            </div>
        </div>
    </div>
@endsection
