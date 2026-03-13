<?php

namespace App\Http\Controllers;

use App\Models\Departement;
use App\Http\Requests\StoreDepartementRequest;
use App\Http\Requests\UpdateDepartementRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class DepartementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Vérifier les permissions (si vous utilisez Gates/Policies)
        // Gate::authorize('viewAny', Departement::class);
        
        $departements = Departement::withCount('employes')->latest()->get();
        return view('departements.index', compact('departements'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Gate::authorize('create', Departement::class);
        
        return view('departements.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDepartementRequest $request)
    {
        // Gate::authorize('create', Departement::class);
        
        Departement::create($request->validated());
        
        return redirect()->route('departements.index')
                         ->with('success', 'Département créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Departement $departement)
    {
        // Gate::authorize('view', $departement);
        
        // Charger les relations pour les statistiques
        $departement->load(['employes' => function($query) {
            $query->withCount('conges');
        }]);
        
        $statistiques = $departement->statistiques();
        
        return view('departements.show', compact('departement', 'statistiques'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Departement $departement)
    {
        // Gate::authorize('update', $departement);
        
        return view('departements.edit', compact('departement'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDepartementRequest $request, Departement $departement)
    {
        // Gate::authorize('update', $departement);
        
        $departement->update($request->validated());
        
        return redirect()->route('departements.index')
                         ->with('success', 'Département mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Departement $departement)
    {
        // Gate::authorize('delete', $departement);
        
        // Vérifier s'il y a des employés dans le département
        if ($departement->employes()->count() > 0) {
            return redirect()->route('departements.index')
                             ->with('error', 'Impossible de supprimer le département : il contient des employés.');
        }
        
        $departement->delete();
        
        return redirect()->route('departements.index')
                         ->with('success', 'Département supprimé avec succès.');
    }
    
    /**
     * Méthode supplémentaire : employés par département
     */
    public function employes(Departement $departement)
    {
        $employes = $departement->employes()->with('user')->paginate(10);
        
        return view('departements.employes', compact('departement', 'employes'));
    }
}