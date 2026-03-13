@extends('layouts.app')

@section('page-title', 'Détails Employé')
@section('page-subtitle', $employe->user->name)

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">{{ $employe->user->name }}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Email :</strong> {{ $employe->user->email }}</p>
                        <p><strong>Département :</strong> {{ $employe->departement->nom ?? '-' }}</p>
                        <p><strong>Rôle :</strong> 
                            <span class="badge 
                                @if($employe->role === 'admin') bg-danger
                                @elseif($employe->role === 'manager') bg-warning
                                @else bg-info
                                @endif">
                                {{ ucfirst($employe->role) }}
                            </span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Matricule :</strong> {{ $employe->matricule ?? '-' }}</p>
                        <p><strong>Date d'embauche :</strong> {{ $employe->date_embauche?->format('d/m/Y') ?? '-' }}</p>
                        <p><strong>Solde Congés :</strong> <span class="badge bg-success">{{ $employe->solde_conge }} j</span></p>
                    </div>
                </div>
                
                <div class="mt-3">
                    <a href="{{ route('employes.edit', $employe) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Éditer
                    </a>
                    <a href="{{ route('employes.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Retour
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
