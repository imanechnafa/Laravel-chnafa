@extends('layouts.app')

@section('page-title', 'Statistiques')
@section('page-subtitle', 'Analyse détaillée des congés')

@section('content')
<div class="row mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-1">Total Congés</h6>
                    <h3 class="mb-0">{{ $statistiques['total_conges'] ?? 0 }}</h3>
                </div>
                <div class="stat-icon" style="background-color: rgba(67, 97, 238, 0.1); color: var(--primary-color);">
                    <i class="fas fa-calendar-alt"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-1">En Attente</h6>
                    <h3 class="mb-0">{{ $statistiques['conges_en_attente'] ?? 0 }}</h3>
                </div>
                <div class="stat-icon" style="background-color: rgba(255, 193, 7, 0.1); color: #ffc107;">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-1">Approuvés</h6>
                    <h3 class="mb-0">{{ $statistiques['conges_approuves'] ?? 0 }}</h3>
                </div>
                <div class="stat-icon" style="background-color: rgba(40, 167, 69, 0.1); color: #28a745;">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-1">Rejetés</h6>
                    <h3 class="mb-0">{{ $statistiques['conges_rejetes'] ?? 0 }}</h3>
                </div>
                <div class="stat-icon" style="background-color: rgba(220, 53, 69, 0.1); color: #dc3545;">
                    <i class="fas fa-times-circle"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Congés par Type</h6>
            </div>
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Nombre</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($statistiques['conges_par_type'] ?? [] as $type)
                        <tr>
                            <td>{{ $type->typeConge->nom ?? 'N/A' }}</td>
                            <td><span class="badge bg-info">{{ $type->total }}</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="text-center text-muted py-3">
                                Aucune donnée disponible
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Congés par Département</h6>
            </div>
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead>
                        <tr>
                            <th>Département</th>
                            <th>Congés</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($statistiques['conges_par_departement'] ?? [] as $dept)
                        <tr>
                            <td>{{ $dept->nom }}</td>
                            <td><span class="badge bg-primary">{{ $dept->conges_count ?? 0 }}</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="text-center text-muted py-3">
                                Aucune donnée disponible
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h6 class="mb-0">Congés par Mois (6 derniers mois)</h6>
    </div>
    <div class="table-responsive">
        <table class="table table-sm mb-0">
            <thead>
                <tr>
                    <th>Mois</th>
                    <th>Nombre de congés</th>
                </tr>
            </thead>
            <tbody>
                @forelse($statistiques['conges_par_mois'] ?? [] as $mois)
                <tr>
                    <td>{{ \Carbon\Carbon::createFromFormat('Y-m', $mois->mois)->format('F Y') }}</td>
                    <td><span class="badge bg-success">{{ $mois->total }}</span></td>
                </tr>
                @empty
                <tr>
                    <td colspan="2" class="text-center text-muted py-3">
                        Aucune donnée disponible
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
