<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Departement;
use App\Models\Employe;
use App\Models\TypeConge;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Créer un département
        $departement = Departement::create([
            'nom' => 'Direction Générale',
            'description' => 'Direction de l\'entreprise',
        ]);

        // Créer l'utilisateur admin
        $user = User::create([
            'name' => 'Administrateur RH',
            'email' => 'admin@rh.com',
            'password' => bcrypt('password123'),
        ]);

        // Créer le profil employé
        Employe::create([
            'user_id' => $user->id,
            'departement_id' => $departement->id,
            'matricule' => 'ADMIN001',
            'date_embauche' => now(),
            'role' => 'admin',
            'solde_conge' => 30,
        ]);

        // Créer des types de congé
        TypeConge::create(['nom' => 'Congés payés', 'jours_annuels' => 25, 'est_paye' => true]);
        TypeConge::create(['nom' => 'Congés maladie', 'jours_annuels' => 30, 'est_paye' => true]);
        TypeConge::create(['nom' => 'RTT', 'jours_annuels' => 10, 'est_paye' => true]);

        $this->command->info('✅ Admin créé avec succès!');
        $this->command->info('📧 Email: admin@rh.com');
        $this->command->info('🔑 Mot de passe: password123');
    }
}