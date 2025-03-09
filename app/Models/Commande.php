<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'montant_total',
        'is_paid',
        'payment_date',
    ];

    // Relation avec les burgers
    public function burgers()
    {
        return $this->belongsToMany(Burger::class)->withPivot('quantity');
    }

    // Relation avec l'utilisateur
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relation avec les paiements
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
