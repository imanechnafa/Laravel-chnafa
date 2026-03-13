<?php

namespace App\Http\Controllers;

use App\Models\Validation;
use App\Models\Conge;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ValidationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if (!$user->employe) {
            abort(403, 'Profil employé non trouvé.');
        }
        
        // Les managers et admins voient les validations
        if ($user->employe->role === 'manager') {
            $validations = Validation::whereHas('conge', function($query) use ($user) {
                $query->whereHas('employe', function($subquery) use ($user) {
                    $subquery->where('departement_id', $user->employe->departement_id);
                });
            })->with(['conge.employe.user', 'conge.typeConge', 'validatedBy.employe'])->paginate(15);
        } elseif ($user->employe->role === 'admin') {
            $validations = Validation::with(['conge.employe.user', 'conge.typeConge', 'validatedBy.employe'])->paginate(15);
        } else {
            abort(403, 'Vous n\'avez pas accès aux validations.');
        }
        
        return view('validations.index', compact('validations'));
    }

    public function show(Validation $validation)
    {
        $user = Auth::user();
        
        if (!$user->employe) {
            abort(403, 'Profil employé non trouvé.');
        }
        
        // Vérifier les permissions
        if ($user->employe->role === 'manager') {
            if ($validation->conge->employe->departement_id !== $user->employe->departement_id) {
                abort(403, 'Vous ne pouvez voir que les validations de votre département.');
            }
        } elseif ($user->employe->role !== 'admin') {
            abort(403, 'Vous n\'avez pas accès.');
        }
        
        $validation->load(['conge.employe.user', 'conge.typeConge', 'validatedBy.employe']);
        
        return view('validations.show', compact('validation'));
    }

    public function destroy(Validation $validation)
    {
        $user = Auth::user();
        
        if (!$user->employe || $user->employe->role !== 'admin') {
            abort(403, 'Seuls les admins peuvent supprimer les validations.');
        }
        
        $validation->delete();
        
        return redirect()->route('validations.index')
                         ->with('success', 'Validation supprimée.');
    }

    /**
     * Approver une demande de congé
     */
    public function approve($congeId)
    {
        $user = Auth::user();
        
        if (!$user->employe || !in_array($user->employe->role, ['manager', 'admin'])) {
            abort(403, 'Seuls les managers et admins peuvent approuver.');
        }

        $conge = Conge::findOrFail($congeId);

        // Vérification des droits
        if ($user->employe->role === 'manager' && $conge->employe->departement_id !== $user->employe->departement_id) {
            abort(403, 'Vous ne pouvez approuver que les congés de votre département.');
        }

        // Approuver
        $conge->statut = 'approuve';
        $conge->save();

        // Créer la validation
        Validation::create([
            'conge_id' => $conge->id,
            'validated_by_user_id' => $user->id,
            'statut' => 'approuve',
            'commentaire' => null,
        ]);

        // Notifier l'employé
        Notification::create([
            'user_id' => $conge->employe->user_id,
            'title' => 'Demande de congé approuvée',
            'message' => 'Votre demande de congé du ' . $conge->date_debut . ' au ' . $conge->date_fin . ' a été approuvée.',
            'type' => 'success',
            'read' => false,
        ]);

        return redirect()->back()->with('success', 'Demande approuvée avec succès.');
    }

    /**
     * Refuser une demande de congé
     */
    public function reject(Request $request, $congeId)
    {
        $user = Auth::user();
        
        if (!$user->employe || !in_array($user->employe->role, ['manager', 'admin'])) {
            abort(403, 'Seuls les managers et admins peuvent refuser.');
        }

        $conge = Conge::findOrFail($congeId);

        // Vérification des droits
        if ($user->employe->role === 'manager' && $conge->employe->departement_id !== $user->employe->departement_id) {
            abort(403, 'Vous ne pouvez refuser que les congés de votre département.');
        }

        // Refuser
        $conge->statut = 'rejete';
        $conge->commentaire_validation = $request->commentaire ?? 'Refusée.';
        $conge->save();

        // Créer la validation
        Validation::create([
            'conge_id' => $conge->id,
            'validated_by_user_id' => $user->id,
            'statut' => 'rejete',
            'commentaire' => $request->commentaire ?? null,
        ]);

        // Notifier l'employé
        Notification::create([
            'user_id' => $conge->employe->user_id,
            'title' => 'Demande de congé refusée',
            'message' => 'Votre demande de congé a été refusée. ' . ($request->commentaire ? 'Motif : ' . $request->commentaire : ''),
            'type' => 'danger',
            'read' => false,
        ]);

        return redirect()->back()->with('success', 'Demande refusée avec succès.');
    }
}