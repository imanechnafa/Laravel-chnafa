@extends('layouts.app')

@section('page-title', 'Dashboard Administrateur')
@section('page-subtitle', 'Vue d\'ensemble du système')

@section('content')
<div class="row mb-4">
    <!-- Statistiques -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card">
            <div class="d-flex justify-content-between">
                <div>
                    <h6 class="text-muted mb-1">Total Employés</h6>
                    <h3 class="mb-0">{{ $stats['total_employes'] }}</h3>
                </div>
                <div class="stat-icon" style="background-color: rgba(67, 97, 238, 0.1); color: var(--primary-color);">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card">
            <div class="d-flex justify-content-between">
                <div>
                    <h6 class="text-muted mb-1">Départements</h6>
                    <h3 class="mb-0">{{ $stats['total_departements'] }}</h3>
                </div>
                <div class="stat-icon" style="background-color: rgba(76, 201, 240, 0.1); color: var(--success-color);">
                    <i class="fas fa-building"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card">
            <div class="d-flex justify-content-between">
                <div>
                    <h6 class="text-muted mb-1">Congés en attente</h6>
                    <h3 class="mb-0">{{ $stats['conges_en_attente'] }}</h3>
                </div>
                <div class="stat-icon" style="background-color: rgba(255, 193, 7, 0.1); color: #ffc107;">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card">
            <div class="d-flex justify-content-between">
                <div>
                    <h6 class="text-muted mb-1">Congés ce mois</h6>
                    <h3 class="mb-0">{{ $stats['conges_ce_mois'] }}</h3>
                </div>
                <div class="stat-icon" style="background-color: rgba(40, 167, 69, 0.1); color: #28a745;">
                    <i class="fas fa-calendar-check"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Derniers congés -->
    <div class="col-lg-8 mb-4">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="mb-0">Dernières demandes de congé</h5>
                <a href="{{ route('conges.index') }}" class="btn btn-sm btn-outline-primary">Voir tout</a>
            </div>
            
            @if($derniersConges && $derniersConges->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Employé</th>
                            <th>Type</th>
                            <th>Période</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($derniersConges as $conge)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                        {{ substr($conge->employe->user->name, 0, 1) }}
                                    </div>
                                    <div class="ms-3">
                                        <div class="fw-bold">{{ $conge->employe->user->name }}</div>
                                        <small class="text-muted">{{ $conge->employe->matricule }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $conge->typeConge->nom }}</td>
                            <td>
                                <small>{{ $conge->date_debut->format('d/m') }} - {{ $conge->date_fin->format('d/m/Y') }}</small>
                                <div><small class="text-muted">{{ $conge->nombre_jours }} jours</small></div>
                            </td>
                            <td>
                                @if($conge->statut == 'en_attente')
                                    <span class="badge badge-pending">En attente</span>
                                @elseif($conge->statut == 'approuve')
                                    <span class="badge badge-approved">Approuvé</span>
                                @else
                                    <span class="badge badge-rejected">Rejeté</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('conges.show', $conge) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                <p class="text-muted">Aucune demande de congé récente</p>
            </div>
            @endif
        </div>
    </div>
    
    <!-- Actions rapides -->
    <div class="col-lg-4 mb-4">
        <div class="stat-card">
            <h5 class="mb-4">Actions rapides</h5>
            
            <div class="d-grid gap-3">
                <a href="{{ route('employes.create') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-user-plus me-2"></i>Nouvel employé
                </a>
                
                <a href="{{ route('departements.create') }}" class="btn btn-outline-primary btn-lg">
                    <i class="fas fa-plus-circle me-2"></i>Nouveau département
                </a>
                
                <a href="{{ route('dashboard.statistiques') }}" class="btn btn-outline-secondary btn-lg">
                    <i class="fas fa-chart-bar me-2"></i>Voir les statistiques
                </a>
                
                <a href="{{ route('validations.index') }}" class="btn btn-outline-info btn-lg">
                    <i class="fas fa-history me-2"></i>Historique des validations
                </a>
            </div>
            
            <hr class="my-4">
            
            <h6 class="mb-3">Statistiques par département</h6>
            @if($congesParDepartement && $congesParDepartement->count() > 0)
                @foreach($congesParDepartement as $dep)
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span>{{ $dep->nom }}</span>
                    <span class="badge bg-primary">{{ $dep->conges_count }} congés</span>
                </div>
                @endforeach
            @else
                <p class="text-muted">Aucune donnée</p>
            @endif
        </div>
    </div>
</div>
@endsection