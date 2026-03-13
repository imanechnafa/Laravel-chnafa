@extends('layouts.app')

@section('page-title', 'Détails Département')
@section('page-subtitle', $departement->nom)

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">{{ $departement->nom }}</h5>
            </div>
            <div class="card-body">
                <p><strong>Description :</strong> {{ $departement->description ?? 'N/A' }}</p>
                <p><strong>Responsable :</strong> {{ $departement->responsable ?? 'Non assigné' }}</p>
                <p><strong>Nombre d\'employés :</strong> <span class="badge bg-info">{{ $departement->employes_count }}</span></p>
                
                <div class="mt-3">
                    <a href="{{ route('departements.edit', $departement) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Éditer
                    </a>
                    <a href="{{ route('departements.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Retour
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Statistiques</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <small class="text-muted">Total Employés</small>
                    <h4 class="mb-0">{{ $departement->employes_count }}</h4>
                </div>
                <div class="mb-3">
                    <small class="text-muted">Congés cette année</small>
                    <h4 class="mb-0">{{ $statistiques['conges_annee'] ?? 0 }}</h4>
                </div>
                <div>
                    <small class="text-muted">Congés en attente</small>
                    <h4 class="mb-0">{{ $statistiques['conges_attente'] ?? 0 }}</h4>
                </div>
            </div>
        </div>
    </div>
</div>

@if($departement->employes->count() > 0)
<div class="card mt-4">
    <div class="card-header">
        <h6 class="mb-0">Employés du département</h6>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Congés</th>
                </tr>
            </thead>
            <tbody>
                @foreach($departement->employes as $employe)
                <tr>
                    <td>{{ $employe->user->name }}</td>
                    <td>{{ $employe->user->email }}</td>
                    <td><span class="badge bg-secondary">{{ $employe->role }}</span></td>
                    <td><span class="badge bg-info">{{ $employe->conges_count }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection
