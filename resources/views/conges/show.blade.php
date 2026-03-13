@extends('layouts.app')

@section('title', 'Détails de la demande de congé')

@section('content')
<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2><i class="fas fa-file-alt me-2"></i>Demande de congé</h2>
        </div>
    </div>

    <!-- Alertes -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <!-- Infos principales -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-check me-2"></i>Informations générales
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Employé</label>
                            <p class="form-control-plaintext">
                                <strong>{{ $conge->employe->user->name }}</strong>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Matricule</label>
                            <p class="form-control-plaintext">
                                {{ $conge->employe->matricule }}
                            </p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Département</label>
                            <p class="form-control-plaintext">
                                {{ $conge->employe->departement->nom }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Type de congé</label>
                            <p class="form-control-plaintext">
                                <span class="badge bg-info">{{ $conge->typeConge->nom }}</span>
                            </p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Date début</label>
                            <p class="form-control-plaintext">
                                {{ \Carbon\Carbon::parse($conge->date_debut)->format('d/m/Y (l)') }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Date fin</label>
                            <p class="form-control-plaintext">
                                {{ \Carbon\Carbon::parse($conge->date_fin)->format('d/m/Y (l)') }}
                            </p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Nombre de jours</label>
                            <p class="form-control-plaintext">
                                <strong class="text-primary">{{ $conge->nombre_jours }}</strong> jours (weekends exclus)
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Statut</label>
                            <p class="form-control-plaintext">
                                @if($conge->statut === 'en_attente')
                                    <span class="badge bg-warning text-dark">
                                        <i class="fas fa-clock me-1"></i>En attente
                                    </span>
                                @elseif($conge->statut === 'approuve')
                                    <span class="badge bg-success">
                                        <i class="fas fa-check me-1"></i>Approuvé
                                    </span>
                                @elseif($conge->statut === 'rejete')
                                    <span class="badge bg-danger">
                                        <i class="fas fa-times me-1"></i>Rejeté
                                    </span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted">Motif</label>
                        <p class="form-control-plaintext">
                            {{ $conge->motif }}
                        </p>
                    </div>

                    @if($conge->commentaire_validation)
                        <div class="mb-3">
                            <label class="form-label text-muted">Commentaire du validateur</label>
                            <p class="form-control-plaintext text-danger">
                                {{ $conge->commentaire_validation }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar infos -->
        <div class="col-md-4">
            <!-- Solde -->
            <div class="card mb-4 bg-light">
                <div class="card-body">
                    <h6 class="card-title mb-3"><i class="fas fa-chart-pie me-2 text-primary"></i>Solde</h6>
                    <p class="mb-1">
                        <small class="text-muted">Avant congé :</small>
                        <strong>{{ $conge->employe->solde_conge + $conge->nombre_jours }}</strong> jours
                    </p>
                    <p class="mb-0">
                        <small class="text-muted">Après congé :</small>
                        <strong class="text-primary">{{ $conge->employe->solde_conge }}</strong> jours
                    </p>
                </div>
            </div>

            <!-- Dates importantes -->
            <div class="card mb-4">
                <div class="card-body">
                    <h6 class="card-title mb-3"><i class="fas fa-info-circle me-2"></i>Informations</h6>
                    <p class="mb-2">
                        <small class="text-muted">Créée le :</small><br>
                        {{ $conge->created_at->format('d/m/Y H:i') }}
                    </p>
                    <p class="mb-0">
                        <small class="text-muted">Modifiée le :</small><br>
                        {{ $conge->updated_at->format('d/m/Y H:i') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="row mt-4">
        <div class="col-md-12">
            <a href="{{ route('conges.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>Retour
            </a>
            @if(Auth::user()->employe && Auth::user()->employe->id === $conge->employe_id && $conge->statut === 'en_attente')
                <a href="{{ route('conges.edit', $conge) }}" class="btn btn-warning">
                    <i class="fas fa-edit me-1"></i>Modifier
                </a>
                <form action="{{ route('conges.destroy', $conge) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr ?')">
                        <i class="fas fa-trash me-1"></i>Supprimer
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
    .form-control-plaintext {
        padding: 0.375rem 0;
        color: #212529;
    }
</style>
@endpush
@endsection
