<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conge extends Model
{
    use HasFactory;

    protected $fillable = [
        'employe_id',
        'type_conge_id',
        'date_debut',
        'date_fin',
        'nombre_jours',
        'motif',
        'statut',
        'commentaire_validation'
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'nombre_jours' => 'integer'
    ];

    // Relation avec Employe
    public function employe()
    {
        return $this->belongsTo(Employe::class);
    }

    // Relation avec TypeConge
    public function typeConge()
    {
        return $this->belongsTo(TypeConge::class);
    }

    // Relation avec Validation (historique des validations)
    public function validations()
    {
        return $this->hasMany(Validation::class);
    }

    // Dernière validation
    public function derniereValidation()
    {
        return $this->validations()->latest()->first();
    }

    // Scope pour les congés en attente
    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'en_attente');
    }

    // Scope pour les congés approuvés
    public function scopeApprouves($query)
    {
        return $query->where('statut', 'approuve');
    }

    // Scope pour les congés rejetés
    public function scopeRejetes($query)
    {
        return $query->where('statut', 'rejete');
    }

    // Scope pour les congés d'un département
    public function scopeParDepartement($query, $departementId)
    {
        return $query->whereHas('employe', function($q) use ($departementId) {
            $q->where('departement_id', $departementId);
        });
    }

    // Scope pour les congés d'une période
    public function scopeEntreDates($query, $debut, $fin)
    {
        return $query->whereBetween('date_debut', [$debut, $fin])
                    ->orWhereBetween('date_fin', [$debut, $fin]);
    }

    // Méthodes pour changer le statut
    public function approuver($commentaire = null)
    {
        $this->statut = 'approuve';
        $this->commentaire_validation = $commentaire;
        $this->save();
        
        // Consommer le solde
        $this->employe->consommerSolde($this->nombre_jours);
    }

    public function rejeter($commentaire = null)
    {
        $this->statut = 'rejete';
        $this->commentaire_validation = $commentaire;
        $this->save();
    }

    public function estEnAttente()
    {
        return $this->statut === 'en_attente';
    }

    public function estApprouve()
    {
        return $this->statut === 'approuve';
    }

    public function estRejete()
    {
        return $this->statut === 'rejete';
    }

    // Vérifier les chevauchements
    public function aChevauchement()
    {
        return Conge::where('employe_id', $this->employe_id)
                   ->where('id', '!=', $this->id)
                   ->where(function($query) {
                       $query->whereBetween('date_debut', [$this->date_debut, $this->date_fin])
                             ->orWhereBetween('date_fin', [$this->date_debut, $this->date_fin])
                             ->orWhere(function($q) {
                                 $q->where('date_debut', '<=', $this->date_debut)
                                   ->where('date_fin', '>=', $this->date_fin);
                             });
                   })
                   ->where('statut', '!=', 'rejete')
                   ->exists();
    }

    // Calculer les jours ouvrés
    public function calculerJoursOuvres()
    {
        $jours = 0;
        $date = clone $this->date_debut;
        
        while ($date <= $this->date_fin) {
            $jourSemaine = $date->format('N'); // 1 = Lundi, 7 = Dimanche
            if ($jourSemaine < 6) { // Lundi à Vendredi
                $jours++;
            }
            $date->addDay();
        }
        
        return $jours;
    }

    // Accessor pour la période formatée
    public function getPeriodeAttribute()
    {
        return $this->date_debut->format('d/m/Y') . ' au ' . $this->date_fin->format('d/m/Y');
    }
}