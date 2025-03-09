@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg mt-10">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">Modifier le Burger ✏️</h1>

        <form action="{{ route('burgers.update', $burger) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="nom" class="block text-lg font-semibold">Nom du burger</label>
                <input type="text" name="nom" id="nom" value="{{ $burger->nom }}" class="form-input w-full rounded-md shadow-sm p-2" required>
            </div>

            <div class="mb-4">
                <label for="prix" class="block text-lg font-semibold">Prix (Fcfa)</label>
                <input type="number" step="0.01" name="prix" id="prix" value="{{ $burger->prix }}" class="form-input w-full rounded-md shadow-sm p-2" required>
            </div>

            <div class="mb-4">
                <label for="description" class="block text-lg font-semibold">Description</label>
                <textarea name="description" id="description" rows="4" class="form-input w-full rounded-md shadow-sm p-2" required>{{ $burger->description }}</textarea>
            </div>

            <div class="mb-4">
                <label for="image" class="block text-lg font-semibold">Image (laisser vide pour conserver l'actuelle)</label>
                <input type="file" name="image" id="image" class="form-input w-full rounded-md shadow-sm p-2" onchange="previewImage(event)">
                @if($burger->image)
                    <img id="imagePreview" src="{{ asset('storage/' . $burger->image) }}" class="mt-3 w-40 rounded-lg shadow-md">
                @else
                    <img id="imagePreview" class="hidden mt-3 w-40 rounded-lg shadow-md">
                @endif
            </div>

            <div class="mb-4">
                <label for="stock" class="block text-lg font-semibold">Stock</label>
                <input type="number" name="stock" id="stock" value="{{ $burger->stock }}" class="form-input w-full rounded-md shadow-sm p-2" required>
            </div>

            <button type="submit" class="btn btn-primary px-6 py-3 rounded-lg shadow-lg hover:bg-blue-700">
                Mettre à jour 🔄
            </button>
        </form>
    </div>

    <script>
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function(){
                var output = document.getElementById('imagePreview');
                output.src = reader.result;
                output.classList.remove('hidden');
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
@endsection
