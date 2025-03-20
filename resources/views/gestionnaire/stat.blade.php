@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-6">Statistiques des Commandes</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-4">Commandes en cours de la journée</h2>
                <canvas id="commandesEnCoursChart"></canvas>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-4">Commandes validées de la journée</h2>
                <canvas id="commandesValideesChart"></canvas>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-4">Recettes journalières</h2>
                <canvas id="recettesJournalieresChart"></canvas>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-4">Nombre de commandes par mois</h2>
                <canvas id="commandesParMoisChart"></canvas>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-4">Nombre de burgers par catégorie</h2>
                <canvas id="burgersParnomChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Passage des données du contrôleur à JavaScript -->
    <script>
        window.chartData = @json($chartData);
    </script>
@endsection
