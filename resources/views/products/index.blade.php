@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">Liste des Produits</h1>
        <a href="{{ route('products.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">Ajouter un Produit</a>
        <table class="min-w-full bg-white">
            <thead>
            <tr>
                <th class="py-2 px-4 border">Nom</th>
                <th class="py-2 px-4 border">Prix</th>
                <th class="py-2 px-4 border">Stock</th>
                <th class="py-2 px-4 border">Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($products as $product)
                <tr>
                    <td class="py-2 px-4 border">{{ $product->name }}</td>
                    <td class="py-2 px-4 border">{{ $product->price }} €</td>
                    <td class="py-2 px-4 border">{{ $product->stock }}</td>
                    <td class="py-2 px-4 border">
                        <a href="{{ route('products.show', $product) }}" class="bg-green-500 text-white px-2 py-1 rounded">Voir</a>
                        <a href="{{ route('products.edit', $product) }}" class="bg-yellow-500 text-white px-2 py-1 rounded">Modifier</a>
                        <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
