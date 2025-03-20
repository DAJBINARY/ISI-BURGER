@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4">
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

        <h1 class="text-3xl font-extrabold text-gray-800 mb-6">Gestion des Commandes</h1>
        <div class="overflow-x-auto shadow-lg rounded-lg">
            <table class="min-w-full bg-white">
                <thead>
                <tr>
                    <th class="py-3 px-5 bg-gray-200 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">ID</th>
                    <th class="py-3 px-5 bg-gray-200 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">Client</th>
                    <th class="py-3 px-5 bg-gray-200 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">Montant</th>
                    <th class="py-3 px-5 bg-gray-200 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                    <th class="py-3 px-5 bg-gray-200 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">Date</th>
                    <th class="py-3 px-5 bg-gray-200 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                @foreach($commandes as $commande)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="py-4 px-5 text-sm text-gray-700">{{ $commande->id }}</td>
                        <td class="py-4 px-5 text-sm text-gray-700">{{ $commande->user->name }}</td>
                        <td class="py-4 px-5 text-sm text-gray-700">{{ number_format($commande->montant_total, 2) }} Fcfa</td>
                        <td class="py-4 px-5 text-sm text-gray-700">{{ $commande->status }}</td>
                        <td class="py-4 px-5 text-sm text-gray-700">{{ $commande->created_at->format('d/m/Y') }}</td>
                        <td class="py-4 px-5 text-sm text-gray-700 space-x-2">
                            <a href="{{ route('gestionnaire.commandes.show', $commande) }}" class="inline-block px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white text-xs font-medium rounded transition-colors">
                                Voir
                            </a>
                            <form action="{{ route('gestionnaire.commandes.annuler', $commande) }}" method="POST" class="inline-block">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white text-xs font-medium rounded transition-colors">
                                    Annuler
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
