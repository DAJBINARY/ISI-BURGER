@extends('layouts.app')

@section('content')
    <div class="card p-4">
        <h1 class="text-2xl font-bold mb-4">Créer une Commande</h1>
        <form action="{{ route('commandes.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="user_id" class="form-label">ID Client</label>
                <input type="number" name="user_id" id="user_id" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Sélectionner les Burgers</label>
                @foreach($burgers as $burger)
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="burgers[][id]" value="{{ $burger->id }}" id="burger{{ $burger->id }}">
                        <label class="form-check-label" for="burger{{ $burger->id }}">
                            {{ $burger->nom }} - {{ number_format($burger->prix, 2) }} €
                        </label>
                        <input type="number" name="burgers[][quantity]" class="form-control mt-1" placeholder="Quantité" min="1">
                    </div>
                @endforeach
            </div>
            <button type="submit" class="btn btn-primary">Créer la commande</button>
        </form>
    </div>
@endsection
