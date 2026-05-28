<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    protected $fillable = [
        'user_id',
        'plan_id',
        'plan_name',
        'plan',        // Ajouté pour le contrôleur
        'status',      // Ajouté pour le contrôleur
        'amount',      // Prix de l'abonnement
        'payment_method', // Méthode de paiement
        'price',
        'starts_at',   // Date de début
        'started_at',
        'ends_at',     // Date de fin
        'cinetpay_transaction_id', // ID de transaction CinetPay
    ];

    protected $casts = [
        'starts_at' => 'date',
        'started_at' => 'date',
        'ends_at' => 'date',
        'price' => 'decimal:2',
        'amount' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
