<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Validation extends Model
{
    use HasFactory;

    protected $fillable = [
        'conge_id',
        'validated_by_user_id',
        'statut',
        'commentaire',
    ];

    // Relation avec Conge
    public function conge()
    {
        return $this->belongsTo(Conge::class);
    }

    // Relation avec l’utilisateur qui valide (manager ou admin)
    public function validatedBy()
    {
        return $this->belongsTo(User::class, 'validated_by_user_id');
    }

    // Scope pour les approbations
    public function scopeApprobations($query)
    {
        return $query->where('statut', 'approuve');
    }

    // Scope pour les rejets
    public function scopeRejets($query)
    {
        return $query->where('statut', 'rejete');
    }

    // Accessor pour la décision formatée
    public function getStatutFormateAttribute()
    {
        return $this->statut === 'approuve' ? 'Approuvé' : 'Rejeté';
    }

    // Méthode pour obtenir le nom du validateur
    public function getNomValidateurAttribute()
    {
        return $this->validatedBy ? $this->validatedBy->name : 'Inconnu';
    }
}
