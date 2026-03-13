@extends('layouts.app')

@section('page-title', 'Éditer Employé')
@section('page-subtitle', $employe->user->name)

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('employes.update', $employe) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nom" class="form-label">Nom *</label>
                            <input type="text" class="form-control @error('nom') is-invalid @enderror" 
                                   id="nom" name="nom" value="{{ old('nom', $employe->user->name) }}" required>
                            @error('nom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="prenom" class="form-label">Prénom</label>
                            <input type="text" class="form-control @error('prenom') is-invalid @enderror" 
                                   id="prenom" name="prenom" value="{{ old('prenom', '') }}">
                            @error('prenom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email *</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email', $employe->user->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de passe (laisser vide pour ne pas changer)</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               id="password" name="password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="departement_id" class="form-label">Département *</label>
                            <select class="form-control @error('departement_id') is-invalid @enderror" 
                                    id="departement_id" name="departement_id" required>
                                <option value="">Sélectionner un département</option>
                                @foreach(\App\Models\Departement::all() as $dept)
                                    <option value="{{ $dept->id }}" {{ old('departement_id', $employe->departement_id) == $dept->id ? 'selected' : '' }}>
                                        {{ $dept->nom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('departement_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="role" class="form-label">Rôle *</label>
                            <select class="form-control @error('role') is-invalid @enderror" 
                                    id="role" name="role" required>
                                <option value="employe" {{ old('role', $employe->role) == 'employe' ? 'selected' : '' }}>Employé</option>
                                <option value="manager" {{ old('role', $employe->role) == 'manager' ? 'selected' : '' }}>Manager</option>
                                <option value="admin" {{ old('role', $employe->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="matricule" class="form-label">Matricule</label>
                            <input type="text" class="form-control @error('matricule') is-invalid @enderror" 
                                   id="matricule" name="matricule" value="{{ old('matricule', $employe->matricule) }}">
                            @error('matricule')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="solde_conge" class="form-label">Solde Congés (jours)</label>
                            <input type="number" class="form-control @error('solde_conge') is-invalid @enderror" 
                                   id="solde_conge" name="solde_conge" value="{{ old('solde_conge', $employe->solde_conge) }}" min="0">
                            @error('solde_conge')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="date_embauche" class="form-label">Date d'embauche</label>
                        <input type="date" class="form-control @error('date_embauche') is-invalid @enderror" 
                               id="date_embauche" name="date_embauche" value="{{ old('date_embauche', $employe->date_embauche?->format('Y-m-d')) }}">
                        @error('date_embauche')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Mettre à jour
                        </button>
                        <a href="{{ route('employes.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
