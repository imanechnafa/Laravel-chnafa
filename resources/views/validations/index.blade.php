@extends('layouts.app')

@section('page-title', 'Historique des Validations')
@section('page-subtitle', 'Suivi de toutes les validations de congés')

@section('content')
<div class="row">
    <div class="col-md-12">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Validations des congés</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Employé</th>
                            <th>Type de congé</th>
                            <th>Période</th>
                            <th>Statut</th>
                            <th>Manager</th>
                            <th>Date de validation</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($validations as $validation)
                        <tr>
                            <td>
                                <strong>{{ $validation->conge->employe->user->name }}</strong>
                            </td>
                            <td>{{ $validation->conge->typeConge->nom ?? 'N/A' }}</td>
                            <td>
                                {{ $validation->conge->date_debut->format('d/m/Y') }} - 
                                {{ $validation->conge->date_fin->format('d/m/Y') }}
                            </td>
                            <td>
                                @if($validation->conge->statut === 'approuve')
                                    <span class="badge bg-success">Approuvé</span>
                                @elseif($validation->conge->statut === 'rejete')
                                    <span class="badge bg-danger">Rejeté</span>
                                @else
                                    <span class="badge bg-warning">En attente</span>
                                @endif
                            </td>
                            <td>{{ $validation->manager->user->name ?? '-' }}</td>
                            <td>{{ $validation->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('validations.show', $validation) }}" class="btn btn-info" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if(auth()->user()->employe && auth()->user()->employe->role === 'admin')
                                    <form action="{{ route('validations.destroy', $validation) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Confirmer la suppression?');" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                Aucune validation trouvée
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($validations instanceof \Illuminate\Pagination\Paginator)
        <div class="mt-4">
            {{ $validations->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
