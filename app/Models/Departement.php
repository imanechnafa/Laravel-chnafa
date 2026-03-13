<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departement extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'description'
    ];

    // Relation avec Employe (1 département a plusieurs employés)
    public function employes()
    {
        return $this->hasMany(Employe::class);
    }

    // Relation avec le manager (1 département a 1 manager)
    public function manager()
    {
        return $this->hasOne(Employe::class)->where('role', 'manager');
    }

    // Récupérer tous les congés du département
    public function conges()
    {
        return Conge::whereHas('employe', function($query) {
            $query->where('departement_id', $this->id);
        });
    }

    // Statistiques du département
    public function statistiques()
    {
        return [
            'total_employes' => $this->employes()->count(),
            'total_conges' => $this->conges()->count(),
            'conges_en_attente' => $this->conges()->where('statut', 'en_attente')->count(),
            'conges_approuves' => $this->conges()->where('statut', 'approuve')->count(),
        ];
    }
}