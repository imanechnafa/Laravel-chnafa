@extends('layouts.app')

@section('title', 'Mes notifications')

@section('content')
<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2><i class="fas fa-bell me-2"></i>Mes notifications</h2>
        </div>
        <div class="col-md-4 text-end">
            @if(Auth::user()->notifications()->where('read', false)->count() > 0)
                <form action="{{ route('notifications.mark-all-read') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-check-double me-1"></i>Tout marquer comme lu
                    </button>
                </form>
            @endif
        </div>
    </div>

    @php
        $notifications = Auth::user()->notifications()->orderBy('created_at', 'desc')->paginate(10);
    @endphp

    @if($notifications->count() > 0)
        <div class="list-group">
            @foreach($notifications as $notification)
                <div class="list-group-item d-flex justify-content-between align-items-center 
                            {{ !$notification->read ? 'list-group-item-light border-start border-5' : '' }}" 
                     style="{{ !$notification->read ? 'border-color: #0d6efd !important;' : '' }}">
                    
                    <div class="d-flex align-items-center">
                        {{-- Icône selon le type --}}
                        @if($notification->type === 'success')
                            <i class="fas fa-check-circle text-success me-3" style="font-size: 1.5rem;"></i>
                        @elseif($notification->type === 'danger')
                            <i class="fas fa-times-circle text-danger me-3" style="font-size: 1.5rem;"></i>
                        @elseif($notification->type === 'warning')
                            <i class="fas fa-exclamation-circle text-warning me-3" style="font-size: 1.5rem;"></i>
                        @else
                            <i class="fas fa-info-circle text-info me-3" style="font-size: 1.5rem;"></i>
                        @endif

                        <div>
                            <h5 class="mb-1 {{ !$notification->read ? 'fw-bold' : '' }}">
                                {{ $notification->title }}
                                @if(!$notification->read)
                                    <span class="badge bg-primary ms-2">Nouveau</span>
                                @endif
                            </h5>
                            <p class="mb-1 text-muted">{{ $notification->message }}</p>
                            <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                        </div>
                    </div>

                    <div class="text-end">
                        {{-- Bouton marquer comme lu --}}
                        @if(!$notification->read)
                            <form action="{{ route('notifications.mark-read', $notification) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-primary" title="Marquer comme lu">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </form>
                        @endif

                        {{-- Bouton supprimer --}}
                        <form action="{{ route('notifications.destroy', $notification) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $notifications->links() }}
        </div>
    @else
        <div class="alert alert-info text-center py-5">
            <h5><i class="fas fa-inbox me-2"></i>Pas de notifications</h5>
            <p class="mb-0">Vous n'avez aucune notification pour le moment.</p>
        </div>
    @endif
</div>

@push('styles')
<style>
    .list-group-item {
        transition: background-color 0.2s;
    }
    .list-group-item:hover {
        background-color: #f8f9fa;
    }
</style>
@endpush
@endsection
