@extends('layouts.app')

@section('page-title', 'Mon Équipe')
@section('page-subtitle', 'Employés du département')

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <h3><i class="fas fa-users"></i> Mon Équipe</h3>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
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
                    <th>Matricule</th>
                    <th>Rôle</th>
                    <th>Solde Congés</th>
                    <th>Date d'embauche</th>
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
                    <td>{{ $employe->matricule ?? '-' }}</td>
                    <td>
                        <span class="badge bg-info">
                            {{ ucfirst($employe->role) }}
                        </span>
                    </td>
                    <td>
                        <span class="badge bg-success">{{ $employe->solde_conge }} j</span>
                    </td>
                    <td>
                        @if($employe->date_embauche)
                            {{ $employe->date_embauche->format('d/m/Y') }}
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        <div class="btn-group btn-group-sm" role="group">
                            <a href="{{ route('employes.show', $employe) }}" class="btn btn-info" title="Voir les détails">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">
                        <i class="fas fa-inbox" style="font-size: 2rem;"></i>
                        <p class="mt-2">Aucun employé dans votre équipe.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
<div class="d-flex justify-content-center mt-4">
    {{ $employes->links() }}
</div>
@endsection
