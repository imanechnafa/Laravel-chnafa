@extends('layouts.app')

@section('page-title', 'Mon Profil')
@section('page-subtitle', auth()->user()->name)

@section('content')
<div class="row">
    <div class="col-md-8">
        <!-- Informations Personnelles -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-user-circle"></i> Informations Personnelles</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Nom Complet</label>
                            <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name', auth()->user()->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Adresse Email</label>
                            <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                   value="{{ old('email', auth()->user()->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    @if(auth()->user()->employe)
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Département</label>
                            <input type="text" class="form-control" disabled value="{{ auth()->user()->employe->departement->nom ?? '-' }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Rôle</label>
                            <input type="text" class="form-control" disabled value="{{ ucfirst(auth()->user()->employe->role) }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Matricule</label>
                            <input type="text" class="form-control" disabled value="{{ auth()->user()->employe->matricule ?? '-' }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Solde Congés</label>
                            <input type="text" class="form-control" disabled value="{{ auth()->user()->employe->solde_conge }} jours">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date d'embauche</label>
                            <input type="text" class="form-control" disabled value="{{ auth()->user()->employe->date_embauche?->format('d/m/Y') ?? '-' }}">
                        </div>
                    </div>
                    @endif

                    @if (session('status') === 'profile-updated')
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle"></i> Profil mis à jour avec succès.
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Mettre à jour le profil
                    </button>
                </form>
            </div>
        </div>

        <!-- Modifier le Mot de Passe -->
        <div class="card mb-4">
            <div class="card-header bg-warning text-white">
                <h5 class="mb-0"><i class="fas fa-lock"></i> Modifier le Mot de Passe</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('password.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="current_password" class="form-label">Mot de passe actuel</label>
                        <input type="password" id="current_password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" required>
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Nouveau mot de passe</label>
                        <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" required>
                        @error('password_confirmation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    @if (session('status') === 'password-updated')
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle"></i> Mot de passe mis à jour avec succès.
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-refresh"></i> Mettre à jour le mot de passe
                    </button>
                </form>
            </div>
        </div>

        <!-- Zone de Danger -->
        <div class="card border-danger">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0"><i class="fas fa-exclamation-triangle"></i> Zone de Danger</h5>
            </div>
            <div class="card-body">
                <p class="text-muted">Une fois votre compte supprimé, il n'y a pas de retour en arrière. Veuillez être certain.</p>
                
                <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                    <i class="fas fa-trash"></i> Supprimer le compte
                </button>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0">Résumé du Profil</h6>
            </div>
            <div class="card-body text-center">
                <div class="mb-3">
                    <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #4361ee, #3a0ca3); border-radius: 50%; margin: 0 auto; display: flex; align-items: center; justify-content: center; color: white; font-size: 36px;">
                        <i class="fas fa-user"></i>
                    </div>
                </div>
                <h5>{{ auth()->user()->name }}</h5>
                <p class="text-muted">{{ auth()->user()->email }}</p>

                @if(auth()->user()->employe)
                <hr>
                <div class="mb-3">
                    <small class="text-muted">DÉPARTEMENT</small>
                    <h6>{{ auth()->user()->employe->departement->nom ?? '-' }}</h6>
                </div>
                <div class="mb-3">
                    <small class="text-muted">RÔLE</small>
                    <h6>
                        <span class="badge 
                            @if(auth()->user()->employe->role === 'admin') bg-danger
                            @elseif(auth()->user()->employe->role === 'manager') bg-warning
                            @else bg-info
                            @endif">
                            {{ ucfirst(auth()->user()->employe->role) }}
                        </span>
                    </h6>
                </div>
                <div>
                    <small class="text-muted">SOLDE CONGÉS</small>
                    <h6>
                        <span class="badge bg-success">{{ auth()->user()->employe->solde_conge }} jours</span>
                    </h6>
                </div>
                @endif

                <hr>
                <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger w-100">
                        <i class="fas fa-sign-out-alt"></i> Se déconnecter
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Suppression -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Confirmer la suppression</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('profile.destroy') }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p>Êtes-vous sûr de vouloir supprimer votre compte ? Cette action est irréversible.</p>
                    <label for="password_confirm" class="form-label">Entrez votre mot de passe pour confirmer</label>
                    <input type="password" id="password_confirm" name="password" class="form-control @error('password') is-invalid @enderror" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">Supprimer mon compte</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection
