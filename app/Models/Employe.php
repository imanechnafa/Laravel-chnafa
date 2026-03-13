<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employe extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'departement_id',
        'matricule',
        'date_embauche',
        'role',
        'solde_conge'
    ];

    protected $casts = [
        'date_embauche' => 'date',
        'solde_conge' => 'integer'
    ];

    // Relation avec User (1 employé = 1 user)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relation avec Département
    public function departement()
    {
        return $this->belongsTo(Departement::class);
    }

    // Relation avec Congés (1 employé a plusieurs congés)
    public function conges()
    {
        return $this->hasMany(Conge::class);
    }

    // Relation avec Validations (si manager)
    public function validations()
    {
        return $this->hasMany(Validation::class, 'manager_id');
    }

    // Employés sous la responsabilité du manager
    public function equipe()
    {
        if ($this->role !== 'manager') {
            return collect();
        }
        
        return Employe::where('departement_id', $this->departement_id)
                     ->where('id', '!=', $this->id)
                     ->where('role', 'employe')
                     ->get();
    }

    // Congés en attente de validation (pour manager)
    public function congesEnAttente()
    {
        return Conge::whereHas('employe', function($query) {
            $query->where('departement_id', $this->departement_id);
        })->where('statut', 'en_attente')->get();
    }

    // Calcul de l'ancienneté
    public function getAncienneteAttribute()
    {
        return $this->date_embauche->diffInYears(now());
    }

    // Vérifier si l'employé a assez de solde
    public function aAssezDeSolde($joursDemandes)
    {
        return $this->solde_conge >= $joursDemandes;
    }

    // Consommer du solde
    public function consommerSolde($jours)
    {
        $this->solde_conge -= $jours;
        $this->save();
    }

    // Ajouter du solde (en fin d'année par exemple)
    public function ajouterSolde($jours)
    {
        $this->solde_conge += $jours;
        $this->save();
    }

    // Méthodes de rôle
    public function estAdmin()
    {
        return $this->role === 'admin';
    }

    public function estManager()
    {
        return $this->role === 'manager';
    }

    public function estEmploye()
    {
        return $this->role === 'employe';
    }
}