<?php

namespace App\Http\Controllers;

use App\Models\Conge;
use App\Models\TypeConge;
use App\Http\Requests\StoreCongeRequest;
use App\Http\Requests\UpdateCongeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CongeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if (!$user->employe) {
            abort(403, 'Profil employé non trouvé.');
        }
        
        $role = $user->employe->role;
        
        if ($role === 'employe') {
            $conges = Conge::where('employe_id', $user->employe->id)
                          ->with('typeConge')
                          ->orderBy('created_at', 'desc')
                          ->paginate(10);
            $view = 'conges.index-employe';
            
        } elseif ($role === 'manager') {
            $departementId = $user->employe->departement_id;
            $conges = Conge::whereHas('employe', function($query) use ($departementId) {
                $query->where('departement_id', $departementId);
            })->with(['employe.user', 'typeConge'])
              ->orderBy('created_at', 'desc')
              ->paginate(10);
            $view = 'conges.index-manager';
            
        } elseif ($role === 'admin') {
            $conges = Conge::with(['employe.user', 'typeConge'])
                          ->orderBy('created_at', 'desc')
                          ->paginate(15);
            $view = 'conges.index-admin';
            
        } else {
            abort(403, 'Rôle non reconnu.');
        }
        
        return view($view, compact('conges'));
    }

    public function create()
    {
        $user = Auth::user();
        
        if (!$user->employe || $user->employe->role !== 'employe') {
            abort(403, 'Seuls les employés peuvent créer des demandes de congé.');
        }
        
        $typesConge = TypeConge::all();
        $employe = $user->employe;
        
        return view('conges.create', compact('typesConge', 'employe'));
    }

    public function store(StoreCongeRequest $request)
    {
        $user = Auth::user();
        
        if (!$user->employe || $user->employe->role !== 'employe') {
            abort(403, 'Seuls les employés peuvent créer des demandes de congé.');
        }
        
        $nombreJours = $this->calculerJoursOuvres(
            Carbon::parse($request->date_debut),
            Carbon::parse($request->date_fin)
        );
        
        $employe = $user->employe;
        
        // Vérifier le solde
        if ($employe->solde_conge < $nombreJours) {
            return back()->with('error', 'Solde insuffisant. Solde disponible : ' . $employe->solde_conge . ' jours.')
                         ->withInput();
        }
        
        Conge::create([
            'employe_id' => $employe->id,
            'type_conge_id' => $request->type_conge_id,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            'nombre_jours' => $nombreJours,
            'motif' => $request->motif,
            'statut' => 'en_attente',
        ]);
        
        return redirect()->route('conges.index')
                         ->with('success', 'Demande de congé soumise avec succès.');
    }

    public function show(Conge $conge)
    {
        $user = Auth::user();
        
        if (!$user->employe) {
            abort(403, 'Profil employé non trouvé.');
        }
        
        // Vérifier les permissions
        if ($user->employe->role === 'employe' && $conge->employe_id !== $user->employe->id) {
            abort(403, 'Vous ne pouvez voir que vos propres congés.');
        }
        
        if ($user->employe->role === 'manager' && $conge->employe->departement_id !== $user->employe->departement_id) {
            abort(403, 'Vous ne pouvez voir que les congés de votre département.');
        }
        
        $conge->load(['employe.user', 'typeConge']);
        
        return view('conges.show', compact('conge'));
    }

    public function edit(Conge $conge)
    {
        $user = Auth::user();
        
        if (!$user->employe || $user->employe->id !== $conge->employe_id) {
            abort(403, 'Vous ne pouvez modifier que vos propres demandes.');
        }
        
        if ($conge->statut !== 'en_attente') {
            abort(403, 'Seules les demandes en attente peuvent être modifiées.');
        }
        
        $typesConge = TypeConge::all();
        
        return view('conges.edit', compact('conge', 'typesConge'));
    }

    public function update(UpdateCongeRequest $request, Conge $conge)
    {
        $user = Auth::user();
        
        if (!$user->employe || $user->employe->id !== $conge->employe_id) {
            abort(403, 'Vous ne pouvez modifier que vos propres demandes.');
        }
        
        if ($conge->statut !== 'en_attente') {
            abort(403, 'Seules les demandes en attente peuvent être modifiées.');
        }
        
        $conge->update($request->validated());
        
        return redirect()->route('conges.show', $conge)
                         ->with('success', 'Demande de congé mise à jour avec succès.');
    }

    public function destroy(Conge $conge)
    {
        $user = Auth::user();
        
        if (!$user->employe || $user->employe->id !== $conge->employe_id) {
            abort(403, 'Vous ne pouvez supprimer que vos propres demandes.');
        }
        
        if ($conge->statut !== 'en_attente') {
            abort(403, 'Seules les demandes en attente peuvent être supprimées.');
        }
        
        $conge->delete();
        
        return redirect()->route('conges.index')
                         ->with('success', 'Demande de congé supprimée avec succès.');
    }
    
    private function calculerJoursOuvres(Carbon $debut, Carbon $fin)
    {
        $jours = 0;
        $date = clone $debut;
        
        while ($date <= $fin) {
            if ($date->dayOfWeekIso < 6) { // 1-5 = Lundi-Vendredi
                $jours++;
            }
            $date->addDay();
        }
        
        return $jours;
    }
    
    public function validation()
    {
        $user = Auth::user();
        
        if (!$user->employe || $user->employe->role !== 'manager') {
            abort(403, 'Seuls les managers peuvent accéder à cette page.');
        }
        
        $departementId = $user->employe->departement_id;
        $congesEnAttente = Conge::whereHas('employe', function($query) use ($departementId) {
            $query->where('departement_id', $departementId);
        })->where('statut', 'en_attente')
          ->with(['employe.user', 'typeConge'])
          ->orderBy('created_at', 'desc')
          ->paginate(10);
        
        return view('conges.validation', compact('congesEnAttente'));
    }
}