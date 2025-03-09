@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-6">Statistiques des Commandes</h1>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <canvas id="commandesChart"></canvas>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const ctx = document.getElementById('commandesChart').getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: @json($chartData['labels']),
                    datasets: [{
                        data: @json($chartData['counts']),
                        backgroundColor: ['#ff6384', '#36a2eb', '#ffce56', '#4caf50'],
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
        });
    </script>
@endsection
