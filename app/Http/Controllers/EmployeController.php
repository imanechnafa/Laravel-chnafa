<?php

namespace App\Http\Controllers;

use App\Models\Employe;
use App\Models\User;
use App\Models\Departement;
use Illuminate\Http\Request;

class EmployeController extends Controller
{
    public function index()
    {
        $employes = Employe::with(['user', 'departement'])->latest()->paginate(15);
        return view('employes.index', compact('employes'));
    }

    public function create()
    {
        $departements = Departement::all();
        return view('employes.create', compact('departements'));
    }

    public function store(Request $request)
    {
        // Valider les données
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'departement_id' => 'required|exists:departements,id',
            'matricule' => 'nullable|string|unique:employes,matricule',
            'role' => 'required|in:employe,manager,admin',
            'solde_conge' => 'nullable|numeric|min:0',
            'date_embauche' => 'nullable|date',
        ]);

        // Créer l'utilisateur
        $user = User::create([
            'name' => trim($validated['nom'] . ' ' . ($validated['prenom'] ?? '')),
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);

        // Créer l'employé
        Employe::create([
            'user_id' => $user->id,
            'departement_id' => $validated['departement_id'],
            'matricule' => $validated['matricule'] ?? null,
            'role' => $validated['role'],
            'solde_conge' => $validated['solde_conge'] ?? 20,
            'date_embauche' => $validated['date_embauche'] ?? now(),
        ]);

        return redirect()->route('employes.index')
                         ->with('success', 'Employé créé avec succès.');
    }

    public function show(Employe $employe)
    {
        $employe->load(['user', 'departement']);
        return view('employes.show', compact('employe'));
    }

    public function edit(Employe $employe)
    {
        $employe->load('user', 'departement');
        $departements = Departement::all();
        return view('employes.edit', compact('employe', 'departements'));
    }

    public function update(Request $request, Employe $employe)
    {
        // Valider les données
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email,' . $employe->user_id,
            'password' => 'nullable|string|min:8',
            'departement_id' => 'required|exists:departements,id',
            'matricule' => 'nullable|string|unique:employes,matricule,' . $employe->id,
            'role' => 'required|in:employe,manager,admin',
            'solde_conge' => 'nullable|numeric|min:0',
            'date_embauche' => 'nullable|date',
        ]);

        // Récupérer l'utilisateur lié
        $user = $employe->user;

        // Mettre à jour les informations de l'utilisateur
        $user->update([
            'name' => trim($validated['nom'] . ' ' . ($validated['prenom'] ?? '')),
            'email' => $validated['email'],
        ]);

        // Mettre à jour le mot de passe si fourni
        if ($request->filled('password')) {
            $user->update([
                'password' => bcrypt($validated['password']),
            ]);
        }

        // Mettre à jour l'employé
        $employe->update([
            'departement_id' => $validated['departement_id'],
            'matricule' => $validated['matricule'] ?? null,
            'date_embauche' => $validated['date_embauche'] ?? $employe->date_embauche,
            'role' => $validated['role'],
            'solde_conge' => $validated['solde_conge'] ?? $employe->solde_conge,
        ]);

        return redirect()->route('employes.index')
                         ->with('success', 'Employé mis à jour avec succès.');
    }

    public function destroy(Employe $employe)
    {
        // Vérifier s'il y a des congés liés
        if ($employe->conges()->exists()) {
            return redirect()->route('employes.index')
                             ->with('error', 'Impossible de supprimer l\'employé : il a des congés associés.');
        }

        // Supprimer l'utilisateur associé
        $user = $employe->user;
        
        // Supprimer l'employé
        $employe->delete();

        // Supprimer l'utilisateur lié
        if ($user) {
            $user->delete();
        }

        return redirect()->route('employes.index')
                         ->with('success', 'Employé supprimé avec succès.');
    }

    /**
     * Afficher l'équipe du manager
     */
    public function mesEmployes()
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        
        if (!$user->employe || $user->employe->role !== 'manager') {
            abort(403, 'Seuls les managers peuvent accéder à cette page.');
        }
        
        $departementId = $user->employe->departement_id;
        $employes = Employe::where('departement_id', $departementId)
                            ->with(['user', 'departement'])
                            ->where('role', 'employe')
                            ->paginate(15);
        
        return view('employes.mes-employes', compact('employes'));
    }
}
