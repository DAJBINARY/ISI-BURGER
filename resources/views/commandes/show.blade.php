<!-- resources/views/commandes/show.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="card p-4">
        <h1 class="text-2xl font-bold mb-4">Détails de la Commande #{{ $commande->id }}</h1>
        <p><strong>Client:</strong> {{ $commande->user->name }}</p>
        <p><strong>Status:</strong> {{ $commande->status }}</p>
        <p><strong>Montant Total:</strong> {{ number_format($commande->montant_total, 2) }} €</p>
        <p><strong>Date de commande:</strong> {{ $commande->created_at->format('d/m/Y H:i') }}</p>
        <hr class="my-4">
        <h2 class="text-xl font-semibold mb-3">Burgers commandés :</h2>
        <ul class="list-group">
            @foreach($commande->burgers as $burger)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $burger->nom }}
                    <span class="badge bg-primary rounded-pill">Quantité: {{ $burger->pivot->quantity }}</span>
                </li>
            @endforeach
        </ul>
        <a href="{{ route('commandes.index') }}" class="btn btn-secondary mt-4">Retour à la liste</a>
    </div>
@endsection
