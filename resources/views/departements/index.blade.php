@extends('layouts.app')

@section('page-title', 'Gestion des Départements')
@section('page-subtitle', 'Liste de tous les départements')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h3>Départements</h3>
    </div>
    <div class="col-md-4 text-end">
        <a href="{{ route('departements.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nouveau département
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Description</th>
                    <th>Employés</th>
                    <th>Responsable</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($departements as $dept)
                <tr>
                    <td>
                        <strong>{{ $dept->nom }}</strong>
                    </td>
                    <td>{{ Str::limit($dept->description, 50) }}</td>
                    <td>
                        <span class="badge bg-info">{{ $dept->employes_count }}</span>
                    </td>
                    <td>{{ $dept->responsable ?? '-' }}</td>
                    <td>
                        <div class="btn-group btn-group-sm" role="group">
                            <a href="{{ route('departements.show', $dept) }}" class="btn btn-info" title="Voir">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('departements.edit', $dept) }}" class="btn btn-warning" title="Éditer">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('departements.destroy', $dept) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Confirmer la suppression?');" title="Supprimer">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">
                        Aucun département trouvé
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
