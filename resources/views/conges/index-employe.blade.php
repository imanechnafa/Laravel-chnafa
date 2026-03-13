@extends('layouts.app')

@section('title', 'Mes demandes de congé')

@section('content')
<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2><i class="fas fa-calendar-alt me-2"></i>Mes demandes de congé</h2>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('conges.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Nouvelle demande
            </a>
        </div>
    </div>

    <!-- Alertes -->
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong><i class="fas fa-exclamation-circle me-2"></i>Erreur !</strong>
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Solde disponible -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card bg-light border-primary">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-clock me-2 text-primary"></i>Solde disponible</h5>
                    <h3 class="text-primary">{{ Auth::user()->employe->solde_conge }} jours</h3>
                    <small class="text-muted">Jours de congé restants cette année</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des demandes -->
    @if($conges->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Type</th>
                        <th>Dates</th>
                        <th>Jours</th>
                        <th>Statut</th>
                        <th>Créée le</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($conges as $conge)
                        <tr>
                            <td>
                                <span class="badge bg-info">{{ $conge->typeConge->nom }}</span>
                            </td>
                            <td>
                                {{ \Carbon\Carbon::parse($conge->date_debut)->format('d/m/Y') }} 
                                à 
                                {{ \Carbon\Carbon::parse($conge->date_fin)->format('d/m/Y') }}
                            </td>
                            <td>
                                <strong>{{ $conge->nombre_jours }}</strong>
                            </td>
                            <td>
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
                            </td>
                            <td>
                                <small class="text-muted">
                                    {{ $conge->created_at->format('d/m/Y H:i') }}
                                </small>
                            </td>
                            <td>
                                <a href="{{ route('conges.show', $conge) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($conge->statut === 'en_attente')
                                    <a href="{{ route('conges.edit', $conge) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('conges.destroy', $conge) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr ?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <nav aria-label="Page navigation">
            {{ $conges->links() }}
        </nav>
    @else
        <div class="alert alert-info text-center py-5">
            <h5><i class="fas fa-inbox me-2"></i>Aucune demande de congé</h5>
            <p class="mb-0">Vous n'avez pas encore créé de demande de congé.</p>
        </div>
    @endif
</div>

@push('styles')
<style>
    .table-hover tbody tr:hover {
        background-color: #f5f5f5;
    }
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
</style>
@endpush
@endsection
