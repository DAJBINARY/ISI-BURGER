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
                        <td class="py-3 px-6 text-gray-800">{{ $commande->id }}</td>
                        <td class="py-3 px-6">
                                <span class="inline-block px-2 py-1 text-sm font-semibold text-{{ $commande->status == 'completed' ? 'green' : 'yellow' }}-800 bg-{{ $commande->status == 'completed' ? 'green' : 'yellow' }}-200 rounded-full">
                                    {{ ucfirst($commande->status) }}
                                </span>
                        </td>
                        <td class="py-3 px-6 text-gray-800">{{ number_format($commande->montant_total, 2) }} Fcfa</td>
                        <td class="py-3 px-6 text-gray-800">{{ $commande->created_at->format('d/m/Y') }}</td>
                        <td class="py-3 px-6">
                            <a href="{{ route('commandes.show', $commande) }}" class="text-blue-600 hover:text-blue-800 font-semibold transition-all duration-300">
                                Voir
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="mt-4">

            </div>
        </div>
    </div>
@endsection
