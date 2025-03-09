<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Burger extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'prix',
        'description',
        'image',
        'stock',
    ];

    // Relation avec les commandes
    public function commandes()
    {
        return $this->belongsToMany(Commande::class)->withPivot('quantity');
    }
}
