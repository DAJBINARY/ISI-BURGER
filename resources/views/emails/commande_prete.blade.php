<!-- resources/views/emails/commande_prete.blade.php -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commande Prête - ISI BURGER</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f7f7f7; padding: 20px; }
        .container { background: #fff; padding: 20px; border-radius: 8px; max-width: 600px; margin: auto; }
        .header { text-align: center; margin-bottom: 20px; }
        .btn { display: inline-block; padding: 10px 20px; background: #007bff; color: #fff; text-decoration: none; border-radius: 4px; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>ISI BURGER</h1>
    </div>
    <p>Bonjour {{ $commande->user->name }},</p>
    <p>Ta commande #{{ $commande->id }} est désormais prête ! Retrouvez ta facture en pièce jointe.</p>
    <p>Merci pour ta confiance et bon appétit !</p>
    <p>Cordialement,</p>
    <p>L'équipe ISI BURGER</p>
    <a href="{{ route('client.catalogue') }}" class="btn">Retour au catalogue</a>
</div>
</body>
</html>
