import './bootstrap';
import 'animate.css';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
// resources/js/app.js

import Chart from 'chart.js/auto';
import ChartDataLabels from 'chartjs-plugin-datalabels';
Chart.register(ChartDataLabels);

document.addEventListener("DOMContentLoaded", function () {
    // Vérifier que window.chartData est disponible
    if (!window.chartData) {
        console.error("Les données des graphiques ne sont pas définies !");
        return;
    }

    const ctx1 = document.getElementById('commandesEnCoursChart')?.getContext('2d');
    if (ctx1) {
        new Chart(ctx1, {
            type: 'doughnut',
            data: {
                labels: ['En Attente'],
                datasets: [{
                    data: [window.chartData.commandesEnCours],
                    backgroundColor: ['#ff6384']
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { position: 'bottom' } }
            }
        });
    }

    const ctx2 = document.getElementById('commandesValideesChart')?.getContext('2d');
    if (ctx2) {
        new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: ['Payée'],
                datasets: [{
                    data: [window.chartData.commandesValidees],
                    backgroundColor: ['#36a2eb']
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { position: 'bottom' } }
            }
        });
    }

    // Graphique : Recettes journalières avec affichage en XOF (FCFA)
    const recettesValue = window.chartData.recettesJournalieres;
    // Valeur de base minimale : 1000 FCFA
    const baseValue = recettesValue >= 1000 ? recettesValue : 1000;
    const maxY = Math.ceil(baseValue * 1.5);
    const stepSize = Math.ceil(maxY / 5);

    const ctx3 = document.getElementById('recettesJournalieresChart')?.getContext('2d');
    if (ctx3) {
        new Chart(ctx3, {
            type: 'bar',
            data: {
                labels: ['Recettes'],
                datasets: [{
                    label: 'Recettes journalières',
                    data: [recettesValue],
                    backgroundColor: ['#ffce56']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    datalabels: {
                        anchor: 'end',
                        align: 'top',
                        formatter: function(value) {
                            return new Intl.NumberFormat('fr-FR', {
                                style: 'currency',
                                currency: 'XOF'
                            }).format(value);
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        min: 0,
                        max: maxY,
                        ticks: {
                            stepSize: stepSize,
                            callback: function(value) {
                                return new Intl.NumberFormat('fr-FR', {
                                    style: 'currency',
                                    currency: 'XOF'
                                }).format(value);
                            }
                        }
                    }
                }
            }
        });
    }

    const ctx4 = document.getElementById('commandesParMoisChart')?.getContext('2d');
    if (ctx4) {
        new Chart(ctx4, {
            type: 'line',
            data: {
                labels: window.chartData.commandesParMois.labels,
                datasets: [{
                    label: 'Nombre de commandes',
                    data: window.chartData.commandesParMois.counts,
                    borderColor: '#4caf50',
                    fill: false
                }]
            },
            options: {
                responsive: true,
                scales: { y: { beginAtZero: true } }
            }
        });
    }

    const ctx5 = document.getElementById('burgersParnomChart')?.getContext('2d');
    if (ctx5) {
        new Chart(ctx5, {
            type: 'bar',
            data: {
                labels: window.chartData.burgersParnom.labels,
                datasets: [{
                    label: 'Nombre de burgers',
                    data: window.chartData.burgersParnom.counts,
                    backgroundColor: ['#ff6384', '#36a2eb', '#ffce56', '#4caf50']
                }]
            },
            options: {
                responsive: true,
                scales: { y: { beginAtZero: true } }
            }
        });
    }
});
