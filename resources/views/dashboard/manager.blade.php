@extends('layouts.app')

@section('page-title', 'Dashboard Manager')
@section('page-subtitle', 'Gestion de l\'équipe - ' . $manager->departement->nom)

@section('content')
<div class="row mb-4">
    <!-- Statistiques -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card">
            <div class="d-flex justify-content-between">
                <div>
                    <h6 class="text-muted mb-1">Effectif</h6>
                    <h3 class="mb-0">{{ $stats['total_equipe'] }}</h3>
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
                    <h6 class="text-muted mb-1">Approuvés ce mois</h6>
                    <h3 class="mb-0">{{ $stats['conges_approuves_mois'] }}</h3>
                </div>
                <div class="stat-icon" style="background-color: rgba(76, 201, 240, 0.1); color: var(--success-color);">
                    <i class="fas fa-check"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card">
            <div class="d-flex justify-content-between">
                <div>
                    <h6 class="text-muted mb-1">Absences actuelles</h6>
                    <h3 class="mb-0">{{ $stats['absences_actuelles'] }}</h3>
                </div>
                <div class="stat-icon" style="background-color: rgba(220, 53, 69, 0.1); color: #dc3545;">
                    <i class="fas fa-user-slash"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Congés en Attente -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-hourglass-half"></i> Congés en Attente de Validation</h5>
            </div>
            <div class="card-body">
                @if($congesEnAttente->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Employé</th>
                                    <th>Type</th>
                                    <th>Période</th>
                                    <th>Jours</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($congesEnAttente as $conge)
                                    <tr>
                                        <td>
                                            <strong>{{ $conge->employe->user->name }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $conge->typeConge->nom }}</span>
                                        </td>
                                        <td>
                                            {{ $conge->date_debut->format('d/m/Y') }} - {{ $conge->date_fin->format('d/m/Y') }}
                                        </td>
                                        <td>
                                            <strong>{{ $conge->nombre_jours }}</strong>
                                        </td>
                                        <td>
                                            <a href="{{ route('conges.show', $conge) }}" class="btn btn-sm btn-outline-primary" title="Détails">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info mb-0">
                        <i class="fas fa-info-circle"></i> Aucun congé en attente de validation.
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Absences à Venir -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-calendar"></i> Absences à Venir</h5>
            </div>
            <div class="card-body">
                @if($absencesAVenir->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($absencesAVenir as $absence)
                            <div class="list-group-item px-0 py-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">{{ $absence->employe->user->name }}</h6>
                                        <small class="text-muted">
                                            {{ $absence->date_debut->format('d/m/Y') }} 
                                            <i class="fas fa-arrow-right"></i> 
                                            {{ $absence->date_fin->format('d/m/Y') }}
                                        </small>
                                    </div>
                                    <span class="badge bg-success">{{ $absence->nombre_jours }}j</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-info mb-0">
                        <i class="fas fa-info-circle"></i> Aucune absence prévue.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Informations du Département -->
<div class="row mt-4">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="fas fa-building"></i> Département: {{ $manager->departement->nom }}</h5>
            </div>
            <div class="card-body">
                <p class="text-muted">Vous gérez l'équipe du département <strong>{{ $manager->departement->nom }}</strong> avec un effectif de <strong>{{ $stats['total_equipe'] }}</strong> employé(s).</p>
            </div>
        </div>
    </div>
</div>
@endsection
