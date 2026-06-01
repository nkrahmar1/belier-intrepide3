@extends('layouts.admin')

@section('content')
@php
    $totalArticles = \App\Models\Article::count();
    $publishedCount = \App\Models\Article::where('is_published', true)->count();
    $draftCount = \App\Models\Article::where('is_published', false)->count();
    $premiumCount = \App\Models\Article::where('is_premium', true)->count();
@endphp

<div class="container-fluid px-4">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-6">
        <div class="space-y-2">
            <h1 class="text-3xl font-semibold text-slate-100">📰 Gestion des articles</h1>
            <p class="text-slate-400 max-w-2xl">
                Accédez à vos publications, contrôlez les statuts et éditez vos contenus depuis une interface claire et dynamique.
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('admin.articles.create') }}" class="btn btn-primary inline-flex items-center gap-2">
                <i class="fas fa-plus"></i>
                Nouvel article
            </a>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary inline-flex items-center gap-2">
                <i class="fas fa-tachometer-alt"></i>
                Dashboard
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="glass-card p-4 mb-6 border-l-4 border-emerald-400">
            <div class="flex items-center gap-3 text-slate-100">
                <i class="fas fa-check-circle text-emerald-400"></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="glass-card p-4 mb-6 border-l-4 border-rose-500">
            <div class="flex items-center gap-3 text-slate-100">
                <i class="fas fa-exclamation-circle text-rose-500"></i>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <div class="grid gap-4 xl:grid-cols-4 mb-6">
        <div class="glass-card p-5 border border-slate-700">
            <div class="flex items-center justify-between gap-3 mb-4">
                <div>
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Total Articles</p>
                    <h2 class="text-3xl font-semibold text-white">{{ $totalArticles }}</h2>
                </div>
                <div class="text-cyan-300 text-3xl">
                    <i class="fas fa-newspaper"></i>
                </div>
            </div>
            <p class="text-sm text-slate-400">Articles actifs dans la base de données.</p>
        </div>
        <div class="glass-card p-5 border border-slate-700">
            <div class="flex items-center justify-between gap-3 mb-4">
                <div>
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Publiés</p>
                    <h2 class="text-3xl font-semibold text-white">{{ $publishedCount }}</h2>
                </div>
                <div class="text-emerald-300 text-3xl">
                    <i class="fas fa-eye"></i>
                </div>
            </div>
            <p class="text-sm text-slate-400">Articles accessibles aux visiteurs.</p>
        </div>
        <div class="glass-card p-5 border border-slate-700">
            <div class="flex items-center justify-between gap-3 mb-4">
                <div>
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Brouillons</p>
                    <h2 class="text-3xl font-semibold text-white">{{ $draftCount }}</h2>
                </div>
                <div class="text-amber-300 text-3xl">
                    <i class="fas fa-eye-slash"></i>
                </div>
            </div>
            <p class="text-sm text-slate-400">Contenus encore en préparation.</p>
        </div>
        <div class="glass-card p-5 border border-slate-700">
            <div class="flex items-center justify-between gap-3 mb-4">
                <div>
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Premium</p>
                    <h2 class="text-3xl font-semibold text-white">{{ $premiumCount }}</h2>
                </div>
                <div class="text-yellow-300 text-3xl">
                    <i class="fas fa-crown"></i>
                </div>
            </div>
            <p class="text-sm text-slate-400">Articles réservés aux abonnés.</p>
        </div>
    </div>

    <div class="glass-card overflow-hidden border border-slate-700">
        <div class="border-b border-slate-700 p-5 bg-slate-950/80">
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-white">Liste des articles</h2>
                    <p class="text-slate-400">{{ $articles->total() }} résultats affichés par page.</p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ route('admin.articles.create') }}" class="btn btn-primary inline-flex items-center gap-2">
                        <i class="fas fa-plus"></i> Créer un article
                    </a>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto p-5">
            <table class="min-w-full text-left text-sm text-slate-300">
                <thead class="bg-slate-900/90 text-slate-400 uppercase tracking-[0.08em] text-[11px]">
                    <tr>
                        <th class="px-4 py-3">ID</th>
                        <th class="px-4 py-3">Visuel</th>
                        <th class="px-4 py-3">Titre</th>
                        <th class="px-4 py-3">Catégorie</th>
                        <th class="px-4 py-3">Statut</th>
                        <th class="px-4 py-3">Premium</th>
                        <th class="px-4 py-3">Vues</th>
                        <th class="px-4 py-3">Date</th>
                        <th class="px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    @forelse($articles as $article)
                        <tr class="hover:bg-slate-950/70 transition-colors">
                            <td class="px-4 py-4 font-medium text-slate-100">#{{ $article->id }}</td>
                            <td class="px-4 py-4">
                                @if($article->image)
                                    <img src="{{ asset('storage/' . $article->image) }}"
                                         alt="Image"
                                         class="h-12 w-12 rounded-xl object-cover">
                                @else
                                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-slate-800 text-slate-500">
                                        <i class="fas fa-image"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-slate-100">
                                <div class="font-semibold">{{ Str::limit($article->titre, 36) }}</div>
                                @if($article->document_path)
                                    <div class="mt-1 text-[13px] text-cyan-300">
                                        <i class="fas fa-file"></i> Document joint
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-4">
                                @if($article->category)
                                    <span class="rounded-full bg-cyan-500/15 px-3 py-1 text-xs text-cyan-200">{{ $article->category->nom }}</span>
                                @else
                                    <span class="rounded-full bg-slate-800 px-3 py-1 text-xs text-slate-400">Non classé</span>
                                @endif
                            </td>
                            <td class="px-4 py-4">
                                @if($article->is_published)
                                    <span class="rounded-full bg-emerald-500/15 px-3 py-1 text-xs text-emerald-200">Publié</span>
                                @else
                                    <span class="rounded-full bg-amber-500/15 px-3 py-1 text-xs text-amber-200">Brouillon</span>
                                @endif
                            </td>
                            <td class="px-4 py-4">
                                @if($article->is_premium)
                                    <span class="rounded-full bg-yellow-500/15 px-3 py-1 text-xs text-yellow-200">Premium</span>
                                @else
                                    <span class="rounded-full bg-slate-800 px-3 py-1 text-xs text-slate-400">Gratuit</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-slate-300">{{ $article->views_count }}</td>
                            <td class="px-4 py-4 text-slate-300">{{ $article->created_at ? $article->created_at->format('d/m/Y') : 'N/A' }}</td>
                            <td class="px-4 py-4">
                                <div class="flex flex-wrap gap-2">
                                    <form action="{{ route('admin.articles.toggle-publish', ['article' => $article->id]) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                                class="btn btn-secondary btn-sm inline-flex items-center gap-2"
                                                title="{{ $article->is_published ? 'Dépublier' : 'Publier' }}">
                                            <i class="fas {{ $article->is_published ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                                        </button>
                                    </form>

                                    <a href="{{ route('admin.articles.edit', $article) }}"
                                       class="btn btn-secondary btn-sm inline-flex items-center gap-2"
                                       title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    @if($article->is_published)
                                        @if($article->featured_on_homepage ?? false)
                                            <button type="button"
                                                    onclick="removeFromHomepage({{ $article->id }})"
                                                    class="btn btn-secondary btn-sm inline-flex items-center gap-2"
                                                    title="Retirer de la page d'accueil">
                                                <i class="fas fa-home"></i>
                                                <i class="fas fa-times" style="font-size: 0.65em;"></i>
                                            </button>
                                        @else
                                            <button type="button"
                                                    onclick="publishToHomepage({{ $article->id }})"
                                                    class="btn btn-secondary btn-sm inline-flex items-center gap-2"
                                                    title="Publier sur la page d'accueil">
                                                <i class="fas fa-home"></i>
                                                <i class="fas fa-plus" style="font-size: 0.65em;"></i>
                                            </button>
                                        @endif
                                    @endif

                                    <a href="{{ route('articles.show', $article->id) }}"
                                       target="_blank"
                                       class="btn btn-secondary btn-sm inline-flex items-center gap-2"
                                       title="Voir l'article">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>

                                    <form action="{{ route('admin.articles.destroy', $article) }}"
                                          method="POST"
                                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-secondary btn-sm inline-flex items-center gap-2"
                                                title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-12 text-center text-slate-400">
                                <div class="space-y-3">
                                    <i class="fas fa-newspaper fa-3x text-slate-500"></i>
                                    <div class="text-lg font-semibold text-white">Aucun article trouvé</div>
                                    <p>Commencez par créer votre premier article pour le voir ici.</p>
                                    <a href="{{ route('admin.articles.create') }}" class="btn btn-primary inline-flex items-center gap-2 justify-center mx-auto mt-2">
                                        <i class="fas fa-plus"></i> Créer un article
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($articles->hasPages())
            <div class="border-t border-slate-700 p-4 bg-slate-950/80">
                {{ $articles->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
.bg-gold {
    background-color: #ffd700 !important;
}
.homepage-featured {
    box-shadow: 0 0 0 2px #28a745;
}
</style>
@endpush

@push('scripts')
<script>
function publishToHomepage(articleId) {
    if (!confirm('Voulez-vous publier cet article sur la page d\'accueil ?')) {
        return;
    }

    fetch(`/admin/articles/${articleId}/publish-homepage`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            setTimeout(() => location.reload(), 1400);
        } else {
            showNotification(data.message || 'Erreur lors de la publication', 'error');
        }
    })
    .catch(() => showNotification('Erreur de connexion', 'error'));
}

function removeFromHomepage(articleId) {
    if (!confirm('Voulez-vous retirer cet article de la page d\'accueil ?')) {
        return;
    }

    fetch(`/admin/articles/${articleId}/remove-homepage`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            setTimeout(() => location.reload(), 1400);
        } else {
            showNotification(data.message || 'Erreur lors de la suppression', 'error');
        }
    })
    .catch(() => showNotification('Erreur de connexion', 'error'));
}

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    const icon = type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-triangle' : 'info-circle';
    notification.className = `toast ${type}`;
    notification.innerHTML = `
        <div style="display:flex; align-items:center; gap:10px;">
            <i class="fas fa-${icon}"></i>
            <span>${message}</span>
        </div>`;

    document.body.appendChild(notification);
    setTimeout(() => notification.remove(), 5000);
}
</script>
@endpush
