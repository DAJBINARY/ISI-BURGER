@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto mt-6 p-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">🛒 Détails de la Commande #{{ $commande->id }}</h1>

        <div class="mb-4">
            <p class="text-lg"><strong class="text-gray-700 dark:text-gray-300">👤 Client :</strong> {{ $commande->user->name }}</p>
            <p class="text-lg"><strong class="text-gray-700 dark:text-gray-300">📌 Statut :</strong>
                <span class="px-3 py-1 rounded-full text-white
                    {{ $commande->status == 'En attente' ? 'bg-yellow-500' : '' }}
                    {{ $commande->status == 'En préparation' ? 'bg-blue-500' : '' }}
                    {{ $commande->status == 'Prête' ? 'bg-green-500' : '' }}
                    {{ $commande->status == 'Payée' ? 'bg-gray-500' : '' }}">
                    {{ $commande->status }}
                </span>
            </p>
            <p class="text-lg"><strong class="text-gray-700 dark:text-gray-300">💰 Montant Total :</strong>
                <span class="font-semibold text-blue-600">{{ number_format($commande->montant_total, 2) }} Fcfa</span>
            </p>
            <p class="text-lg"><strong class="text-gray-700 dark:text-gray-300">📅 Date :</strong> {{ $commande->created_at->format('d/m/Y H:i') }}</p>
        </div>

        <hr class="my-4 border-gray-300 dark:border-gray-600">

        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">🍔 Burgers commandés :</h2>
        <ul class="bg-gray-100 dark:bg-gray-700 rounded-lg shadow p-4">
            @foreach($commande->burgers as $burger)
                <li class="flex justify-between items-center py-2 border-b last:border-b-0 border-gray-300 dark:border-gray-600">
                    <span class="text-gray-900 dark:text-white font-medium">{{ $burger->nom }}</span>
                    <span class="bg-blue-600 text-white px-3 py-1 rounded-full">Quantité: {{ $burger->pivot->quantity }}</span>
                </li>
            @endforeach
        </ul>

        <div class="mt-6">
            <form action="{{ route('gestionnaire.commandes.updateStatus', $commande) }}" method="POST" class="flex items-center space-x-3">
                @csrf
                <select name="status" class="w-1/2 p-2 border rounded-lg focus:ring focus:ring-blue-300 dark:bg-gray-800 dark:text-white" required>
                    <option value="En attente" {{ $commande->status == 'En attente' ? 'selected' : '' }}>En attente</option>
                    <option value="En préparation" {{ $commande->status == 'En préparation' ? 'selected' : '' }}>En préparation</option>
                    <option value="Prête" {{ $commande->status == 'Prête' ? 'selected' : '' }}>Prête</option>
                    <option value="Payée" {{ $commande->status == 'Payée' ? 'selected' : '' }}>Payée</option>
                </select>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow">✅ Mettre à jour</button>
            </form>
        </div>

        <a href="{{ route('gestionnaire.commandes.index') }}" class="mt-6 inline-block bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg shadow">
            ⬅ Retour
        </a>
    </div>
@endsection
