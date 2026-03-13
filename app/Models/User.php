<?php

namespace App\Models;

// REMPLACEZ TOUT LE CONTENU DU FICHIER par ceci :

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Employe;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relation avec Employe
    public function employe()
    {
        return $this->hasOne(Employe::class);
    }

    // Relation avec Notifications
    public function notifications()
    {
        return $this->hasMany(\App\Models\Notification::class);
    }

    // Méthodes pour vérifier les rôles - FORCEZ LE CHARGEMENT
    public function isAdmin()
    {
        // Charge explicitement la relation si pas déjà chargée
        if (!$this->relationLoaded('employe')) {
            $this->load('employe');
        }
        
        return $this->employe && $this->employe->role === 'admin';
    }

    public function isManager()
    {
        if (!$this->relationLoaded('employe')) {
            $this->load('employe');
        }
        
        return $this->employe && $this->employe->role === 'manager';
    }

    public function isEmploye()
    {
        if (!$this->relationLoaded('employe')) {
            $this->load('employe');
        }
        
        return $this->employe && $this->employe->role === 'employe';
    }

    // Vérifie si l'utilisateur a un profil employé
    public function hasEmployeProfile()
    {
        if (!$this->relationLoaded('employe')) {
            $this->load('employe');
        }
        
        return $this->employe !== null;
    }

    // Récupère le rôle ou 'guest' si pas de profil
    public function getRoleAttribute()
    {
        if (!$this->hasEmployeProfile()) {
            return 'guest';
        }
        
        return $this->employe->role;
    }

    // Raccourci pour le département
    public function departement()
    {
        if (!$this->hasEmployeProfile()) {
            return null;
        }
        
        if (!$this->employe->relationLoaded('departement')) {
            $this->employe->load('departement');
        }
        
        return $this->employe->departement;
    }
}