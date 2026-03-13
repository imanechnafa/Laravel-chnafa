@extends('layouts.app')

@section('page-title', 'Gestion des Employés')
@section('page-subtitle', 'Liste de tous les employés')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h3>Employés</h3>
    </div>
    <div class="col-md-4 text-end">
        <a href="{{ route('employes.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nouvel employé
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
                    <th>Email</th>
                    <th>Département</th>
                    <th>Rôle</th>
                    <th>Solde Congés</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employes as $employe)
                <tr>
                    <td>
                        <strong>{{ $employe->user->name }}</strong>
                    </td>
                    <td>{{ $employe->user->email }}</td>
                    <td>{{ $employe->departement->nom ?? '-' }}</td>
                    <td>
                        <span class="badge 
                            @if($employe->role === 'admin') bg-danger
                            @elseif($employe->role === 'manager') bg-warning
                            @else bg-info
                            @endif">
                            {{ ucfirst($employe->role) }}
                        </span>
                    </td>
                    <td>
                        <span class="badge bg-success">{{ $employe->solde_conge }} j</span>
                    </td>
                    <td>
                        <div class="btn-group btn-group-sm" role="group">
                            <a href="{{ route('employes.show', $employe) }}" class="btn btn-info" title="Voir">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('employes.edit', $employe) }}" class="btn btn-warning" title="Éditer">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('employes.destroy', $employe) }}" method="POST" style="display:inline;">
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
                    <td colspan="6" class="text-center text-muted py-4">
                        Aucun employé trouvé
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($employes instanceof \Illuminate\Pagination\Paginator)
    <div class="mt-4">
        {{ $employes->links() }}
    </div>
@endif
@endsection
