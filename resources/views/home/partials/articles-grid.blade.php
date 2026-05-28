{{-- Vue partielle simple pour les articles filtrés --}}
<div class="filtered-articles">
    @if($categoryName !== 'Tous les Articles')
        <div style="background: #e8f5e8; border-left: 4px solid #28a745; padding: 15px; margin-bottom: 25px; border-radius: 5px;">
            <h3 style="color: #155724; margin: 0; font-size: 1.3rem;">
                {{ $categoryName }} - {{ $articles->total() }} article{{ $articles->total() > 1 ? 's' : '' }}
            </h3>
        </div>
    @endif
    
    @if($articles->count() > 0)
        <div class="articles-grid">
            @foreach($articles as $article)
                <article class="article-card" onclick="window.location.href='{{ route('article.show', $article->id) }}'" style="cursor: pointer;">
                    {{-- Gestion intelligente des images --}}
                    @php
                        $imageUrl = null;
                        
                        if($article->image) {
                            $storagePath = storage_path('app/public/' . $article->image);
                            if(file_exists($storagePath)) {
                                $imageUrl = asset('storage/' . $article->image);
                            } else {
                                $publicPath = public_path('image/' . basename($article->image));
                                if(file_exists($publicPath)) {
                                    $imageUrl = asset('image/' . basename($article->image));
                                }
                            }
                        }
                        
                        if(!$imageUrl) {
                            $catKey = strtolower($article->category->nom ?? 'general');
                            $categoryImages = [
                                'economie' => 'economie2.webp',
                                'sport' => 'sport-monde.jpg',
                                'politique' => 'politique.jpg',
                                'culture et média' => 'culture.webp',
                                'pdci-rda' => 'pdci1.jpg',
                                'société' => 'justice.webp',
                                'afrique' => 'ivoire.jpg',
                                'dossiers' => 'im3.png'
                            ];
                            $fallbackImage = $categoryImages[$catKey] ?? 'pdci1.jpg';
                            $imageUrl = asset('image/' . $fallbackImage);
                        }
                    @endphp
                    <img src="{{ $imageUrl }}" alt="{{ $article->titre }}" onerror="this.src='{{ asset('image/pdci1.jpg') }}'">

                    <div class="article-card-content">
                        @php
                            $categoryClass = 'general';
                            $categoryName = $article->category->nom ?? 'Général';
                            switch(strtolower($categoryName)) {
                                case 'economie': $categoryClass = 'economie'; break;
                                case 'sport': $categoryClass = 'sport'; break;
                                case 'culture et média': $categoryClass = 'culture'; break;
                                case 'politique': $categoryClass = 'politique'; break;
                                case 'pdci-rda': $categoryClass = 'pdci'; break;
                                case 'afrique': $categoryClass = 'afrique'; break;
                                case 'société': $categoryClass = 'societe'; break;
                                case 'dossiers': $categoryClass = 'dossiers'; break;
                            }
                        @endphp

                        <div class="article-category {{ $categoryClass }}">{{ strtoupper($categoryName) }}</div>
                        <h3 class="article-card-title">{{ $article->titre }}</h3>
                        <p class="article-card-excerpt">{{ Str::limit($article->extrait ?: strip_tags($article->contenu), 120) }}</p>

                        <div class="article-meta">
                            <span>📅 {{ $article->created_at->format('d M Y') }}</span>

                            @if($categoryClass === 'politique')
                                <span style="margin-left: 10px;">
                                    <span style="color: #dc3545; font-weight: bold;">
                                        <i class="fas fa-lock"></i> Réservé abonnés
                                    </span>
                                </span>
                            @endif

                            @if($article->document_path)
                                <span style="margin-left: 10px;">
                                    <span style="color: #28a745; font-weight: bold;" title="Document disponible">
                                        <i class="fas fa-file-pdf"></i> PDF
                                    </span>
                                </span>
                            @endif
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
        
        {{-- Pagination --}}
        <div class="pagination-wrapper" style="margin-top: 40px; display: flex; justify-content: center;">
            {{ $articles->links() }}
        </div>
    @else
        <div style="text-align: center; padding: 60px 20px;">
            <i class="fas fa-search" style="font-size: 4rem; color: #ccc; margin-bottom: 20px;"></i>
            <h3 style="color: #666;">Aucun article dans cette catégorie</h3>
            <p style="color: #999;">Essayez de sélectionner une autre catégorie ou consultez tous les articles.</p>
            <button onclick="filterArticles('all')" class="btn" style="background: #28a745; color: white; margin-top: 15px;">
                <i class="fas fa-list"></i> Voir tous les articles
            </button>
        </div>
    @endif
</div>