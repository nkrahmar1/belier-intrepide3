<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Facture</title>
    <style>
        body { font-family: DejaVu Sans; }
        .header { font-size: 20px; font-weight: bold; margin-bottom: 20px; }
        .content { font-size: 14px; }
    </style>
</head>
<body>
    <div class="header">Facture #{{ $subscription->id }}</div>

    <div class="content">
        <p><strong>Nom :</strong> {{ $subscription->user->name }}</p>
        <p><strong>Plan :</strong> {{ $subscription->plan_name }}</p>
        <p><strong>Prix :</strong> {{ $subscription->price }} €</p>
        <p><strong>Date de souscription :</strong> {{ $subscription->started_at->format('d/m/Y') }}</p>
        <p><strong>Date d’expiration :</strong> {{ $subscription->ends_at->format('d/m/Y') }}</p>
    </div>

    <p style="margin-top: 40px;">Merci pour votre abonnement à <strong>Le Bélier Intrépide</strong>.</p>
</body>
</html>
