@extends('layouts.app')

@section('title', 'Validation des congés')

@section('content')
<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2><i class="fas fa-check-double me-2"></i>Validation des demandes de congé</h2>
        </div>
    </div>

    <!-- Alertes -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Filtres par statut -->
    <div class="mb-4">
        <div class="btn-group" role="group">
            <a href="{{ route('conges.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-list me-1"></i>Toutes ({{ $conges->total() }})
            </a>
            <a href="{{ route('conges.index', ['filter' => 'en_attente']) }}" class="btn btn-outline-warning">
                <i class="fas fa-clock me-1"></i>En attente
            </a>
            <a href="{{ route('conges.index', ['filter' => 'approuve']) }}" class="btn btn-outline-success">
                <i class="fas fa-check me-1"></i>Approuvés
            </a>
            <a href="{{ route('conges.index', ['filter' => 'rejete']) }}" class="btn btn-outline-danger">
                <i class="fas fa-times me-1"></i>Rejetés
            </a>
        </div>
    </div>

    <!-- Liste des demandes -->
    @if($conges->count() > 0)
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Employé</th>
                        <th>Département</th>
                        <th>Type</th>
                        <th>Dates</th>
                        <th>Jours</th>
                        <th>Motif</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($conges as $conge)
                        <tr>
                            <td>
                                <strong>{{ $conge->employe->user->name }}</strong><br>
                                <small class="text-muted">{{ $conge->employe->matricule }}</small>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $conge->employe->departement->nom }}</span>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $conge->typeConge->nom }}</span>
                            </td>
                            <td>
                                <small>
                                    {{ \Carbon\Carbon::parse($conge->date_debut)->format('d/m/Y') }} 
                                    à 
                                    {{ \Carbon\Carbon::parse($conge->date_fin)->format('d/m/Y') }}
                                </small>
                            </td>
                            <td>
                                <strong>{{ $conge->nombre_jours }}</strong> j.
                            </td>
                            <td>
                                <small>{{ Str::limit($conge->motif, 30) }}</small>
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
                                <a href="{{ route('conges.show', $conge) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye me-1"></i>Voir
                                </a>
                                @if($conge->statut === 'en_attente')
                                    <button class="btn btn-sm btn-success" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#approveModal{{ $conge->id }}">
                                        <i class="fas fa-check me-1"></i>Approuver
                                    </button>
                                    <button class="btn btn-sm btn-danger" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#rejectModal{{ $conge->id }}">
                                        <i class="fas fa-times me-1"></i>Refuser
                                    </button>
                                @endif
                            </td>
                        </tr>

                        <!-- Modal Approuver -->
                        <div class="modal fade" id="approveModal{{ $conge->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-success text-white">
                                        <h5 class="modal-title">Approuver la demande</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Êtes-vous sûr d'approuver cette demande de congé ?</p>
                                        <p class="mb-0"><strong>{{ $conge->employe->user->name }}</strong> - {{ $conge->nombre_jours }} jours</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                        <form action="{{ route('validations.approve', $conge->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-success">
                                                <i class="fas fa-check me-1"></i>Approuver
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Refuser -->
                        <div class="modal fade" id="rejectModal{{ $conge->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-danger text-white">
                                        <h5 class="modal-title">Refuser la demande</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('validations.reject', $conge->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <p>Êtes-vous sûr de refuser cette demande ?</p>
                                            <div class="mb-3">
                                                <label for="commentaire{{ $conge->id }}" class="form-label">Raison du refus (optionnel)</label>
                                                <textarea name="commentaire" 
                                                          id="commentaire{{ $conge->id }}" 
                                                          class="form-control" 
                                                          rows="3" 
                                                          placeholder="Expliquez pourquoi cette demande est refusée..."></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                            <button type="submit" class="btn btn-danger">
                                                <i class="fas fa-times me-1"></i>Refuser
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
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
            <h5><i class="fas fa-inbox me-2"></i>Aucune demande trouvée</h5>
            <p class="mb-0">Il n'y a aucune demande de congé à valider.</p>
        </div>
    @endif
</div>

@push('styles')
<style>
    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
    }
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
</style>
@endpush
@endsection
