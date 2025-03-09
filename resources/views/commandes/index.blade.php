<!-- resources/views/commandes/index.blade.php -->
@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Liste des Commandes</h1>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>ID</th>
            <th>Client</th>
            <th>Montant Total</th>
            <th>Status</th>
            <th>Date</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($commandes as $commande)
            <tr>
                <td>{{ $commande->id }}</td>
                <td>{{ $commande->user->name }}</td>
                <td>{{ number_format($commande->montant_total, 2) }} €</td>
                <td>{{ $commande->status }}</td>
                <td>{{ $commande->created_at->format('d/m/Y') }}</td>
                <td>
                    <a href="{{ route('commandes.show', $commande) }}" class="btn btn-sm btn-outline-primary">Voir</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
