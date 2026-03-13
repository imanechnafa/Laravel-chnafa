<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeConge extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'jours_annuels',
        'est_paye',
        'couleur'
    ];

    protected $casts = [
        'est_paye' => 'boolean',
        'jours_annuels' => 'integer'
    ];

    // Relation avec Conge (1 type a plusieurs congés)
    public function conges()
    {
        return $this->hasMany(Conge::class);
    }

    // Accessor pour le nom formaté
    public function getNomFormateAttribute()
    {
        return ucfirst($this->nom) . " ({$this->jours_annuels} jours)";
    }

    // Scope pour les congés payés
    public function scopePayes($query)
    {
        return $query->where('est_paye', true);
    }

    // Scope pour les congés non payés
    public function scopeNonPayes($query)
    {
        return $query->where('est_paye', false);
    }
}