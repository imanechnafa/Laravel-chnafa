<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Conge;
use App\Models\Employe;
use App\Models\Departement;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect('/login');
        }
        
        // Vérifier si l'utilisateur a un profil employé
        if (!$user->employe) {
            return view('dashboard.no-profile', compact('user'));
        }
        
        $role = $user->employe->role;
        
        if ($role === 'admin') {
            return $this->adminDashboard($user);
        } elseif ($role === 'manager') {
            return $this->managerDashboard($user);
        } elseif ($role === 'employe') {
            return $this->employeDashboard($user);
        }
        
        return view('dashboard.default', compact('user'));
    }
    
    public function adminDashboard($user = null)
    {
        if (!$user) {
            $user = Auth::user();
        }
        
        $stats = [
            'total_employes' => Employe::count(),
            'total_departements' => Departement::count(),
            'conges_en_attente' => Conge::where('statut', 'en_attente')->count(),
            'conges_ce_mois' => Conge::whereMonth('created_at', now()->month)->count(),
        ];
        
        $derniersConges = Conge::with(['employe.user', 'typeConge'])
                              ->latest()
                              ->take(10)
                              ->get();
        
        $congesParDepartement = Departement::withCount(['employes as conges_count' => function($query) {
            $query->select(DB::raw('count(conges.id)'))
                  ->leftJoin('conges', 'employes.id', '=', 'conges.employe_id')
                  ->where('conges.created_at', '>=', now()->subMonth());
        }])->get();
        
        return view('dashboard.admin', compact('stats', 'derniersConges', 'congesParDepartement'));
    }
    
    public function managerDashboard($user = null)
    {
        if (!$user) {
            $user = Auth::user();
        }
        
        $manager = $user->employe;
        $manager->load('departement');
        
        $departementId = $manager->departement_id;
        
        $stats = [
            'total_equipe' => Employe::where('departement_id', $departementId)
                                    ->where('role', 'employe')
                                    ->count(),
            'conges_en_attente' => Conge::whereHas('employe', function($query) use ($departementId) {
                $query->where('departement_id', $departementId);
            })->where('statut', 'en_attente')->count(),
            'conges_approuves_mois' => Conge::whereHas('employe', function($query) use ($departementId) {
                $query->where('departement_id', $departementId);
            })->where('statut', 'approuve')
              ->whereMonth('created_at', now()->month)
              ->count(),
            'absences_actuelles' => Conge::whereHas('employe', function($query) use ($departementId) {
                $query->where('departement_id', $departementId);
            })->where('statut', 'approuve')
              ->where('date_debut', '<=', now())
              ->where('date_fin', '>=', now())
              ->count(),
        ];
        
        $congesEnAttente = Conge::whereHas('employe', function($query) use ($departementId) {
            $query->where('departement_id', $departementId);
        })->where('statut', 'en_attente')
          ->with(['employe.user', 'typeConge'])
          ->latest()
          ->take(10)
          ->get();
        
        $absencesAVenir = Conge::whereHas('employe', function($query) use ($departementId) {
            $query->where('departement_id', $departementId);
        })->where('statut', 'approuve')
          ->where('date_debut', '>=', now())
          ->with('employe.user')
          ->orderBy('date_debut')
          ->take(5)
          ->get();
        
        return view('dashboard.manager', compact('stats', 'manager', 'congesEnAttente', 'absencesAVenir'));
    }
    
    public function employeDashboard($user = null)
    {
        if (!$user) {
            $user = Auth::user();
        }
        
        $employe = $user->employe;
        $employe->load('departement');
        
        $stats = [
            'solde_conge' => $employe->solde_conge,
            'conges_en_attente' => $employe->conges()->where('statut', 'en_attente')->count(),
            'conges_approuves' => $employe->conges()->where('statut', 'approuve')->count(),
            'jours_prises_annee' => $employe->conges()->where('statut', 'approuve')
                                       ->whereYear('created_at', now()->year)
                                       ->sum('nombre_jours'),
        ];
        
        $mesDerniersConges = $employe->conges()->with('typeConge')
                                     ->latest()
                                     ->take(5)
                                     ->get();
        
        $congesAVenir = $employe->conges()->where('statut', 'approuve')
                                 ->where('date_debut', '>=', now())
                                 ->orderBy('date_debut')
                                 ->take(5)
                                 ->get();
        
        return view('dashboard.employe', compact('stats', 'employe', 'mesDerniersConges', 'congesAVenir'));
    }
    
    public function statistiques()
    {
        $user = Auth::user();
        
        if (!$user->employe || $user->employe->role !== 'admin') {
            abort(403, 'Accès réservé aux administrateurs.');
        }
        
        // Statistiques détaillées
        $statistiques = [
            'total_conges' => Conge::count(),
            'conges_en_attente' => Conge::where('statut', 'en_attente')->count(),
            'conges_approuves' => Conge::where('statut', 'approuve')->count(),
            'conges_rejetes' => Conge::where('statut', 'rejete')->count(),
            
            // Par mois (6 derniers mois)
            'conges_par_mois' => Conge::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as mois, COUNT(*) as total')
                                     ->where('created_at', '>=', now()->subMonths(6))
                                     ->groupBy('mois')
                                     ->orderBy('mois')
                                     ->get(),
            
            // Par type
            'conges_par_type' => Conge::selectRaw('type_conge_id, COUNT(*) as total')
                                     ->with('typeConge')
                                     ->groupBy('type_conge_id')
                                     ->get(),
            
            // Par département
            'conges_par_departement' => Departement::withCount(['employes as conges_count' => function($query) {
                $query->select(DB::raw('count(conges.id)'))
                      ->leftJoin('conges', 'employes.id', '=', 'conges.employe_id');
            }])->get(),
        ];
        
        return view('dashboard.statistiques', compact('statistiques'));
    }
}