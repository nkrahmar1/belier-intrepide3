@props(['articles' => []])
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Articles Récents</h3>
    <div class="overflow-x-auto">
        <table class="table table-hover table-striped align-middle mb-0">
            <thead class="table-light text-center">
                <tr>
                    <th class="text-start ps-4">Titre</th>
                    <th>Auteur</th>
                    <th>Catégorie</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($articles as $article)
                    <tr>
                        <td class="ps-4 fw-semibold text-dark">{{ $article->title }}</td>
                        <td class="text-muted">{{ $article->user->name ?? 'N/A' }}</td>
                        <td>{{ $article->category->name ?? 'N/A' }}</td>
                        <td class="text-muted">{{ $article->created_at->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('articles.show', $article->id) }}" class="btn btn-sm btn-outline-info shadow-sm" data-bs-toggle="tooltip" title="Voir l'article">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">Aucun article récent</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
