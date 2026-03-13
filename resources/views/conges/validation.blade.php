@extends('layouts.app')

@section('page-title', 'Validations des Congés')
@section('page-subtitle', 'Demandes en attente de validation')

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-hourglass-half"></i> Demandes en Attente de Validation</h5>
            </div>
            <div class="card-body">
                <!-- Alertes -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Liste des demandes -->
                @if($congesEnAttente->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Employé</th>
                                    <th>Type de Congé</th>
                                    <th>Dates</th>
                                    <th>Jours</th>
                                    <th>Motif</th>
                                    <th>Demandé le</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($congesEnAttente as $conge)
                                    <tr>
                                        <td>
                                            <strong>{{ $conge->employe->user->name }}</strong><br>
                                            <small class="text-muted">{{ $conge->employe->matricule ?? '-' }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $conge->typeConge->nom }}</span>
                                        </td>
                                        <td>
                                            <small>
                                                {{ $conge->date_debut->format('d/m/Y') }} 
                                                <i class="fas fa-arrow-right"></i> 
                                                {{ $conge->date_fin->format('d/m/Y') }}
                                            </small>
                                        </td>
                                        <td>
                                            <strong>{{ $conge->nombre_jours }}</strong> j.
                                        </td>
                                        <td>
                                            <small>{{ Str::limit($conge->motif, 30) ?? '-' }}</small>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $conge->created_at->format('d/m/Y H:i') }}</small>
                                        </td>
                                        <td>
                                            <a href="{{ route('conges.show', $conge) }}" class="btn btn-sm btn-outline-primary" title="Détails">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button class="btn btn-sm btn-outline-success" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#approveModal{{ $conge->id }}"
                                                    title="Approuver">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#rejectModal{{ $conge->id }}"
                                                    title="Refuser">
                                                <i class="fas fa-times"></i>
                                            </button>
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
                                                    <p>Êtes-vous sûr d'approuver la demande de congé de <strong>{{ $conge->employe->user->name }}</strong> ?</p>
                                                    <div class="alert alert-info mb-0">
                                                        <small>
                                                            Période: {{ $conge->date_debut->format('d/m/Y') }} au {{ $conge->date_fin->format('d/m/Y') }}
                                                            ({{ $conge->nombre_jours }} jours)
                                                        </small>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                    <form action="{{ route('validations.approve', $conge->id) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-success">
                                                            <i class="fas fa-check"></i> Approuver
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
                                                        <p>Êtes-vous sûr de refuser la demande de <strong>{{ $conge->employe->user->name }}</strong> ?</p>
                                                        <div class="mb-3">
                                                            <label for="raison{{ $conge->id }}" class="form-label">Raison du refus (optionnel)</label>
                                                            <textarea name="raison" 
                                                                      id="raison{{ $conge->id }}" 
                                                                      class="form-control" 
                                                                      rows="3" 
                                                                      placeholder="Indiquez la raison..."></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                        <button type="submit" class="btn btn-danger">
                                                            <i class="fas fa-times"></i> Refuser
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
                    <div class="d-flex justify-content-center mt-3">
                        {{ $congesEnAttente->links() }}
                    </div>
                @else
                    <div class="alert alert-info text-center py-5">
                        <i class="fas fa-inbox" style="font-size: 3rem;"></i>
                        <h5 class="mt-3">Aucune demande en attente</h5>
                        <p class="text-muted">Toutes les demandes de congé de votre équipe ont été traitées.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
