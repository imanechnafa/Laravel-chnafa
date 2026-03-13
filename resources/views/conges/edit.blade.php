@extends('layouts.app')

@section('title', 'Modifier la demande de congé')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="card-title mb-0"><i class="fas fa-edit me-2"></i>Modifier la demande de congé</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('conges.update', $conge) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        
                        <!-- Type de congé -->
                        <div class="mb-3">
                            <label for="type_conge_id" class="form-label">Type de congé</label>
                            <select name="type_conge_id" id="type_conge_id" class="form-select" required>
                                <option value="">Sélectionnez un type</option>
                                @foreach($typesConge as $type)
                                    <option value="{{ $type->id }}" 
                                            {{ old('type_conge_id', $conge->type_conge_id) == $type->id ? 'selected' : '' }}>
                                        {{ $type->nom }} ({{ $type->jours_annuels }} jours/an)
                                    </option>
                                @endforeach
                            </select>
                            @error('type_conge_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Dates -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="date_debut" class="form-label">Date début</label>
                                <input type="date" 
                                       name="date_debut" 
                                       id="date_debut" 
                                       class="form-control" 
                                       value="{{ old('date_debut', $conge->date_debut) }}"
                                       min="{{ date('Y-m-d') }}"
                                       required>
                                @error('date_debut')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="date_fin" class="form-label">Date fin</label>
                                <input type="date" 
                                       name="date_fin" 
                                       id="date_fin" 
                                       class="form-control" 
                                       value="{{ old('date_fin', $conge->date_fin) }}"
                                       min="{{ date('Y-m-d') }}"
                                       required>
                                @error('date_fin')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Calcul automatique du nombre de jours (JS) -->
                        <div class="mb-3">
                            <div class="alert alert-info">
                                <strong><i class="fas fa-calculator me-2"></i>Nombre de jours :</strong>
                                <span id="nombre_jours_calc">{{ $conge->nombre_jours }}</span> jours (weekends exclus)
                            </div>
                        </div>
                        
                        <!-- Motif -->
                        <div class="mb-3">
                            <label for="motif" class="form-label">Motif</label>
                            <textarea name="motif" 
                                      id="motif" 
                                      class="form-control" 
                                      rows="4" 
                                      required>{{ old('motif', $conge->motif) }}</textarea>
                            @error('motif')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Boutons -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('conges.show', $conge) }}" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i>Annuler
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Calcul automatique du nombre de jours (exclure weekends)
document.addEventListener('DOMContentLoaded', function() {
    const dateDebut = document.getElementById('date_debut');
    const dateFin = document.getElementById('date_fin');
    const nombreJours = document.getElementById('nombre_jours_calc');
    
    function calculerJours() {
        if (dateDebut.value && dateFin.value) {
            const debut = new Date(dateDebut.value);
            const fin = new Date(dateFin.value);
            
            if (debut > fin) {
                nombreJours.textContent = '0 (date début > date fin)';
                return;
            }
            
            // Différence en jours
            const diffTime = Math.abs(fin - debut);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
            
            // Soustraire les weekends
            let joursOuvres = 0;
            for (let i = 0; i < diffDays; i++) {
                const date = new Date(debut);
                date.setDate(debut.getDate() + i);
                const dayOfWeek = date.getDay();
                if (dayOfWeek !== 0 && dayOfWeek !== 6) { // 0=Dimanche, 6=Samedi
                    joursOuvres++;
                }
            }
            
            nombreJours.textContent = joursOuvres;
        }
    }
    
    dateDebut.addEventListener('change', calculerJours);
    dateFin.addEventListener('change', calculerJours);
});
</script>
@endpush
@endsection
