@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg mt-10">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">Créer un nouveau Burger 🍔</h1>

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

        <form action="{{ route('burgers.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <label for="nom" class="block text-lg font-semibold text-white">Nom du burger</label>
                <input type="text" name="nom" id="nom" class="form-input w-full rounded-md shadow-sm p-2" required>
            </div>

            <div class="mb-4">
                <label for="prix" class="block text-lg font-semibold text-white">Prix (Fcfa)</label>
                <input type="number" step="0.01" name="prix" id="prix" class="form-input w-full rounded-md shadow-sm p-2" required>
            </div>

            <div class="mb-4">
                <label for="description" class="block text-lg font-semibold text-white">Description</label>
                <textarea name="description" id="description" rows="4" class="form-input w-full rounded-md shadow-sm p-2" required></textarea>
            </div>

            <div class="mb-4">
                <label for="image" class="block text-lg font-semibold text-white">Image</label>
                <input type="file" name="image" id="image" class="form-input w-full rounded-md shadow-sm p-2" onchange="previewImage(event)">
                <img id="imagePreview" class="hidden mt-3 w-40 rounded-lg shadow-md">
            </div>

            <div class="mb-4">
                <label for="stock" class="block text-lg font-semibold text-white">Stock</label>
                <input type="number" name="stock" id="stock" class="form-input w-full rounded-md shadow-sm p-2" required>
            </div>

            <button type="submit" class="btn btn-primary px-6 py-3 rounded-lg shadow-lg hover:bg-blue-700 text-white">
                Ajouter le burger 🍔
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
