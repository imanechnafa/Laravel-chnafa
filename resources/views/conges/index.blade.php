@extends('layouts.app')

@section('page-title', 'Mes Demandes de Congé')
@section('page-subtitle', 'Gérez vos congés et solde')

@section('content')
<div class="row mb-4">
    <!-- Carte solde -->
    <div class="col-md-4 mb-4">
        <div class="stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-1">Solde disponible</h6>
                    <h2 class="mb-0">{{ auth()->user()->employe->solde_conge }} jours</h2>
                    <small>Congés payés annuels</small>
                </div>
                <div style="font-size: 48px; opacity: 0.8;">
                    <i class="fas fa-coins"></i>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Statistiques -->
    <div class="col-md-8">
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="stat-card">
                    <div class="text-center">
                        <div class="mb-2" style="font-size: 24px; color: #ffc107;">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h5 class="mb-1">{{ $conges->where('statut', 'en_attente')->count() }}</h5>
                        <small class="text-muted">En attente</small>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="stat-card">
                    <div class="text-center">
                        <div class="mb-2" style="font-size: 24px; color: #28a745;">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <h5 class="mb-1">{{ $conges->where('statut', 'approuve')->count() }}</h5>
                        <small class="text-muted">Approuvés</small>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="stat-card">
                    <div class="text-center">
                        <div class="mb-2" style="font-size: 24px; color: #dc3545;">
                            <i class="fas fa-times-circle"></i>
                        </div>
                        <h5 class="mb-1">{{ $conges->where('statut', 'rejete')->count() }}</h5>
                        <small class="text-muted">Rejetés</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bouton nouvelle demande -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-0">Historique des demandes</h5>
        <small class="text-muted">Toutes vos demandes de congé</small>
    </div>
    <a href="{{ route('conges.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Nouvelle demande
    </a>
</div>

<!-- Tableau des congés -->
<div class="stat-card">
    @if($conges->count() > 0)
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Période</th>
                    <th>Durée</th>
                    <th>Date demande</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($conges as $conge)
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <div style="width: 12px; height: 12px; background-color: {{ $conge->typeConge->couleur ?? '#3498db' }}; border-radius: 50%; margin-right: 10px;"></div>
                            {{ $conge->typeConge->nom }}
                        </div>
                    </td>
                    <td>
                        <div class="fw-bold">{{ $conge->date_debut->format('d/m/Y') }}</div>
                        <small class="text-muted">au {{ $conge->date_fin->format('d/m/Y') }}</small>
                    </td>
                    <td>
                        <span class="badge bg-light text-dark">{{ $conge->nombre_jours }} jours</span>
                    </td>
                    <td>{{ $conge->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        @if($conge->statut == 'en_attente')
                            <span class="badge badge-pending">
                                <i class="fas fa-clock me-1"></i>En attente
                            </span>
                        @elseif($conge->statut == 'approuve')
                            <span class="badge badge-approved">
                                <i class="fas fa-check me-1"></i>Approuvé
                            </span>
                        @else
                            <span class="badge badge-rejected">
                                <i class="fas fa-times me-1"></i>Rejeté
                            </span>
                        @endif
                    </td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('conges.show', $conge) }}" class="btn btn-outline-primary" title="Voir">
                                <i class="fas fa-eye"></i>
                            </a>
                            
                            @if($conge->statut == 'en_attente')
                            <a href="{{ route('conges.edit', $conge) }}" class="btn btn-outline-warning" title="Modifier">
                                <i class="fas fa-edit"></i>
                            </a>
                            
                            <button type="button" class="btn btn-outline-danger btn-delete" 
                                    data-url="{{ route('conges.destroy', $conge) }}"
                                    title="Supprimer">
                                <i class="fas fa-trash"></i>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="d-flex justify-content-between align-items-center mt-4">
        <div class="text-muted">
            Affichage de {{ $conges->firstItem() }} à {{ $conges->lastItem() }} sur {{ $conges->total() }} demandes
        </div>
        <div>
            {{ $conges->links() }}
        </div>
    </div>
    
    @else
    <div class="text-center py-5">
        <div class="mb-3" style="font-size: 64px; color: #e0e0e0;">
            <i class="fas fa-umbrella-beach"></i>
        </div>
        <h5 class="text-muted">Aucune demande de congé</h5>
        <p class="text-muted">Vous n'avez pas encore soumis de demande de congé.</p>
        <a href="{{ route('conges.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Créer votre première demande
        </a>
    </div>
    @endif
</div>

<!-- Formulaire de suppression caché -->
<form id="delete-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@push('scripts')
<script>
    $(document).ready(function() {
        // Gestion des suppressions
        $('.btn-delete').click(function() {
            const url = $(this).data('url');
            
            Swal.fire({
                title: 'Êtes-vous sûr ?',
                text: "Cette action est irréversible !",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#delete-form').attr('action', url).submit();
                }
            });
        });
        
        // Filtre par statut
        $('.filter-status').click(function(e) {
            e.preventDefault();
            const status = $(this).data('status');
            window.location.href = "{{ route('conges.index') }}?status=" + status;
        });
    });
</script>
@endpush
@endsection