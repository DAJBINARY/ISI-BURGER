<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture #{{ $commande->id }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-10">
<div class="max-w-3xl mx-auto bg-white shadow-lg rounded-lg p-6">
    <!-- En-tête -->
    <div class="flex justify-between items-center border-b pb-4 mb-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">🧾 Facture</h1>
            <p class="text-gray-600">Commande #{{ $commande->id }}</p>
        </div>
        <div class="text-right">
            <h2 class="text-xl font-semibold text-gray-700">ISI BURGER</h2>
            <p class="text-gray-500">Dakar, Sénégal</p>
            <p class="text-gray-500">contact@isiburger.com</p>
        </div>
    </div>

    <!-- Informations client -->
    <div class="mb-6">
        <h3 class="text-lg font-semibold text-gray-700">Client :</h3>
        <p class="text-gray-800">{{ $commande->user->name }}</p>
        <p class="text-gray-600">{{ $commande->user->email }}</p>
    </div>

    <!-- Liste des burgers commandés -->
    <table class="w-full border-collapse border border-gray-300 mb-6">
        <thead>
        <tr class="bg-gray-200">
            <th class="border border-gray-300 px-4 py-2 text-left">🍔 Burger</th>
            <th class="border border-gray-300 px-4 py-2 text-center">Quantité</th>
            <th class="border border-gray-300 px-4 py-2 text-right">Prix Unitaire</th>
            <th class="border border-gray-300 px-4 py-2 text-right">Total</th>
        </tr>
        </thead>
        <tbody>
        @foreach($commande->burgers as $burger)
            <tr class="border-b">
                <td class="border border-gray-300 px-4 py-2">{{ $burger->nom }}</td>
                <td class="border border-gray-300 px-4 py-2 text-center">{{ $burger->pivot->quantity }}</td>
                <td class="border border-gray-300 px-4 py-2 text-right">{{ number_format($burger->prix, 2) }} Fcfa</td>
                <td class="border border-gray-300 px-4 py-2 text-right">{{ number_format($burger->pivot->quantity * $burger->prix, 2) }} Fcfa</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <!-- Montant total -->
    <div class="flex justify-between text-lg font-semibold">
        <span>Total :</span>
        <span class="text-blue-600">{{ number_format($commande->montant_total, 2) }} Fcfa</span>
    </div>

    <p class="mt-6 text-center text-gray-600">Merci pour votre commande chez ISI BURGER ! 🍔</p>
</div>
</body>
</html>
