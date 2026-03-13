@extends('layouts.app')

@section('page-title', 'Détails Validation')
@section('page-subtitle', 'Informations de validation de congé')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Validation #{{ $validation->id }}</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <h6 class="text-muted">Employé</h6>
                        <p><strong>{{ $validation->conge->employe->user->name }}</strong></p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Manager</h6>
                        <p><strong>{{ $validation->manager->user->name }}</strong></p>
                    </div>
                </div>

                <hr>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <h6 class="text-muted">Type de congé</h6>
                        <p><strong>{{ $validation->conge->typeConge->nom ?? 'N/A' }}</strong></p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Nombre de jours</h6>
                        <p><strong>{{ $validation->conge->nombre_jours }} jour(s)</strong></p>
                    </div>
                </div>

                <hr>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <h6 class="text-muted">Période</h6>
                        <p>
                            <strong>Du {{ $validation->conge->date_debut->format('d/m/Y') }}</strong><br>
                            <strong>Au {{ $validation->conge->date_fin->format('d/m/Y') }}</strong>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Statut</h6>
                        <p>
                            @if($validation->conge->statut === 'approuve')
                                <span class="badge bg-success">Approuvé</span>
                            @elseif($validation->conge->statut === 'rejete')
                                <span class="badge bg-danger">Rejeté</span>
                            @else
                                <span class="badge bg-warning">En attente</span>
                            @endif
                        </p>
                    </div>
                </div>

                <hr>

                <div class="mb-3">
                    <h6 class="text-muted">Motif/Commentaire</h6>
                    <p>{{ $validation->conge->motif ?? '-' }}</p>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted">Demandé le</h6>
                        <p>{{ $validation->conge->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Validé le</h6>
                        <p>{{ $validation->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>

                <div class="mt-4">
                    <a href="{{ route('validations.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Retour
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
