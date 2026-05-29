@extends('layouts.admin')

@section('title', 'Liste des utilisateurs')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<div class="container mt-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-bold text-dark"><i class="fas fa-users me-2"></i>Gestion des utilisateurs</h2>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-dark shadow-sm">
            <i class="fas fa-arrow-left me-1"></i> Retour
        </a>
    </div>

    <!-- Success message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
    @endif

    <!-- Table -->
    <div class="card shadow-sm border-0 admin-users-card">
        <div class="card-header bg-slate-950/90 border-bottom border-slate-700 d-flex flex-column flex-md-row justify-content-between align-items-start gap-3 py-4 px-4">
            <div>
                <h2 class="fw-bold text-white mb-1"><i class="fas fa-users me-2"></i>Gestion des utilisateurs</h2>
                <p class="text-slate-400 mb-0">Affichez la liste des utilisateurs de manière claire, ordonnée et facile à gérer.</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-light shadow-sm">
                <i class="fas fa-arrow-left me-1"></i> Retour
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle mb-0 admin-users-table">
                    <thead class="text-white text-uppercase" style="font-size:12px; letter-spacing:0.05em;">
                        <tr>
                            <th class="text-start ps-4" style="min-width: 240px;">Nom</th>
                            <th style="min-width: 240px;">Email</th>
                            <th style="width: 130px;">Statut</th>
                            <th class="text-center" style="width: 170px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td class="ps-4 fw-semibold text-white">{{ $user->name }}</td>
                                <td class="text-slate-300">{{ $user->email }}</td>
                                <td>
                                    <span class="badge rounded-pill {{ $user->status === 'actif' ? 'bg-success text-white' : 'bg-secondary text-white' }}">
                                        {{ ucfirst($user->status) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2 flex-wrap">
                                        <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-outline-info shadow-sm" data-bs-toggle="tooltip" title="Voir détails">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline-warning shadow-sm" data-bs-toggle="tooltip" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Confirmer la suppression ?')" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger shadow-sm" data-bs-toggle="tooltip" title="Supprimer">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-slate-400 py-4">Aucun utilisateur trouvé.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $users->links() }}
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        tooltips.forEach(el => new bootstrap.Tooltip(el));
    });
</script>
@endsection

<style>
    .btn:hover {
        transform: scale(1.03);
        transition: all 0.2s ease-in-out;
    }

    .admin-users-card {
        background: rgba(15, 23, 42, 0.92);
        border: 1px solid rgba(148, 163, 184, 0.12);
        border-radius: 18px;
        overflow: hidden;
    }

    .admin-users-card .card-header {
        background: rgba(15, 23, 42, 0.98);
    }

    .admin-users-table thead th {
        background: rgba(30, 41, 59, 0.98) !important;
        border-bottom: 1px solid rgba(148, 163, 184, 0.2) !important;
        color: #cbd5e1 !important;
    }

    .admin-users-table tbody tr {
        border-bottom: 1px solid rgba(148, 163, 184, 0.08);
    }

    .admin-users-table tbody tr:hover {
        background: rgba(255, 255, 255, 0.03);
    }

    .admin-users-table tbody td {
        color: #e2e8f0;
        vertical-align: middle;
        padding: 14px 16px;
    }

    .admin-users-table .badge {
        min-width: 88px;
    }
</style>
