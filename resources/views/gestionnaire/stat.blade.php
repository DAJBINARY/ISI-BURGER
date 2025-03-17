@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-6">Statistiques des Commandes</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Commandes en cours de la journée -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-4">Commandes en cours de la journée</h2>
                <canvas id="commandesEnCoursChart"></canvas>
            </div>

            <!-- Commandes validées de la journée -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-4">Commandes validées de la journée</h2>
                <canvas id="commandesValideesChart"></canvas>
            </div>

            <!-- Recettes journalières -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-4">Recettes journalières</h2>
                <canvas id="recettesJournalieresChart"></canvas>
            </div>

            <!-- Nombre de commandes par mois -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-4">Nombre de commandes par mois</h2>
                <canvas id="commandesParMoisChart"></canvas>
            </div>

            <!-- Nombre de burgers par catégorie -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-4">Nombre de burgers par catégorie</h2>
                <canvas id="burgersParnomChart"></canvas>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Commandes en cours de la journée
            const ctx1 = document.getElementById('commandesEnCoursChart').getContext('2d');
            new Chart(ctx1, {
                type: 'doughnut',
                data: {
                    labels: ['En cours'],
                    datasets: [{
                        data: [@json($chartData['commandesEnCours'])],
                        backgroundColor: ['#ff6384'],
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        },
                    }
                }
            });

            // Commandes validées de la journée
            const ctx2 = document.getElementById('commandesValideesChart').getContext('2d');
            new Chart(ctx2, {
                type: 'doughnut',
                data: {
                    labels: ['Validées'],
                    datasets: [{
                        data: [@json($chartData['commandesValidees'])],
                        backgroundColor: ['#36a2eb'],
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        },
                    }
                }
            });

            // Recettes journalières
            const ctx3 = document.getElementById('recettesJournalieresChart').getContext('2d');
            new Chart(ctx3, {
                type: 'bar',
                data: {
                    labels: ['Recettes'],
                    datasets: [{
                        label: 'Recettes journalières',
                        data: [@json($chartData['recettesJournalieres'])],
                        backgroundColor: ['#ffce56'],
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Nombre de commandes par mois
            const ctx4 = document.getElementById('commandesParMoisChart').getContext('2d');
            new Chart(ctx4, {
                type: 'line',
                data: {
                    labels: @json($chartData['commandesParMois']['labels']),
                    datasets: [{
                        label: 'Nombre de commandes',
                        data: @json($chartData['commandesParMois']['counts']),
                        borderColor: '#4caf50',
                        fill: false,
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Nombre de burgers par catégorie
            const ctx5 = document.getElementById('burgersParnomChart').getContext('2d');
            new Chart(ctx5, {
                type: 'bar',
                data: {
                    labels: @json($chartData['burgersParnom']['labels']),
                    datasets: [{
                        label: 'Nombre de burgers',
                        data: @json($chartData['burgersParnom']['counts']),
                        backgroundColor: ['#ff6384', '#36a2eb', '#ffce56', '#4caf50'],
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
@endsection
