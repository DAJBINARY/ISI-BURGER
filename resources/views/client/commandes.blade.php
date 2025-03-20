@extends('layouts.app')

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

@section('content')
    <div class="text-center py-10">
        <h1 class="text-4xl font-bold mb-4">Mes Commandes</h1>

        <!-- Tableau des commandes -->
        <div class="overflow-x-auto shadow-lg rounded-lg border border-gray-200">
            <table class="min-w-full table-auto">
                <thead class="bg-blue-600 text-white">
                <tr>
                    <th class="py-3 px-6 text-left">ID</th>
                    <th class="py-3 px-6 text-left">Statut</th>
                    <th class="py-3 px-6 text-left">Montant Total</th>
                    <th class="py-3 px-6 text-left">Date</th>
                    <th class="py-3 px-6 text-left">Actions</th>
                </tr>
                </thead>
                <tbody class="bg-white">
                @foreach($commandes as $commande)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="py-3 px-6 text-left text-gray-800">{{ $commande->id }}</td>
                        <td class="py-3 px-6 text-left">
                            <span class="inline-block px-2 py-1 text-sm font-semibold text-{{ $commande->status == 'completed' ? 'green' : 'yellow' }}-800 bg-{{ $commande->status == 'completed' ? 'green' : 'yellow' }}-200 rounded-full">
                                {{ ucfirst($commande->status) }}
                            </span>
                        </td>
                        <td class="py-3 px-6 text-left text-gray-800">{{ number_format($commande->montant_total, 2) }} Fcfa</td>
                        <td class="py-3 px-6 text-left text-gray-800">{{ $commande->created_at->format('d/m/Y') }}</td>
                        <td class="py-3 px-6 text-left">
                            <!-- Remplacement du bouton "Voir" par "Annulé" -->
                            <a href="{{ route('client.commandes', $commande) }}" class="text-red-600 hover:text-red-800 font-semibold transition-all duration-300">
                                Annulé
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
