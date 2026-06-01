<div class="flex flex-col gap-6">
    <div class="flex flex-col md:flex-row justify-between items-start gap-4">
        <div class="space-y-2">
            <h1 class="text-3xl font-semibold text-slate-100">
                {{ $formTitle ?? (isset($article) ? 'Modifier l\'article' : 'Créer un article') }}
            </h1>
            <p class="text-slate-400 max-w-2xl leading-6">
                Organisez vos articles, ajoutez des visuels et contrôlez facilement la publication depuis un module clair et moderne.
            </p>
        </div>
        <a href="{{ route('admin.articles.index') }}" class="btn btn-secondary inline-flex items-center gap-2">
            <i class="fas fa-arrow-left"></i>
            Retour à la liste
        </a>
    </div>

    @if(session('success'))
        <div class="glass-card p-4 border-l-4 border-emerald-400">
            <div class="flex items-center gap-3 text-slate-100">
                <i class="fas fa-check-circle text-emerald-400"></i>
                <div>{{ session('success') }}</div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="glass-card p-4 border-l-4 border-rose-500">
            <div class="flex items-center gap-3 text-slate-100">
                <i class="fas fa-exclamation-circle text-rose-500"></i>
                <div>{{ session('error') }}</div>
            </div>
        </div>
    @endif

    <div class="glass-card p-6">
        <form action="{{ isset($article) ? route('admin.articles.update', $article) : route('admin.articles.store') }}"
              method="POST"
              enctype="multipart/form-data"
              class="space-y-6">
            @csrf
            @if(isset($article))
                @method('PUT')
            @endif

            {{-- ===== TITRE + SLUG ===== --}}
            <div class="grid gap-6 lg:grid-cols-2">
                <div>
                    <label for="titre" class="block text-sm font-semibold text-slate-200 mb-2">
                        Titre <span class="text-rose-500">*</span>
                    </label>
                    <input type="text"
                           id="titre"
                           name="titre"
                           value="{{ old('titre', $article->titre ?? '') }}"
                           class="w-full rounded-2xl border border-slate-700 bg-slate-950/80 px-4 py-3 text-slate-100 placeholder:text-slate-500 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-300/20"
                           placeholder="Titre de l'article"
                           required>
                    @error('titre')
                        <div class="mt-2 text-sm text-rose-400">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label for="slug" class="block text-sm font-semibold text-slate-200 mb-2">
                        Slug <span class="text-slate-500 font-normal">(généré automatiquement)</span>
                    </label>
                    <input type="text"
                           id="slug"
                           name="slug"
                           value="{{ old('slug', $article->slug ?? '') }}"
                           class="w-full rounded-2xl border border-slate-700 bg-slate-950/80 px-4 py-3 text-slate-100 placeholder:text-slate-500 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-300/20"
                           placeholder="slug-de-l-article">
                    @error('slug')
                        <div class="mt-2 text-sm text-rose-400">{{ $message }}</div>
                    @enderror
                    <p class="mt-2 text-sm text-slate-500">Laissez vide pour générer automatiquement depuis le titre.</p>
                </div>
            </div>

            {{-- ===== CATÉGORIE ===== --}}
            <div class="grid gap-6 lg:grid-cols-2">
                <div>
                    <label for="category_name" class="block text-sm font-semibold text-slate-200 mb-2">
                        Catégorie <span class="text-rose-500">*</span>
                    </label>

                    @if(empty($categories) || count($categories) === 0)
                        <div class="rounded-2xl border border-rose-500/50 bg-rose-900/20 p-3 mb-3">
                            <p class="text-rose-300 text-sm">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                Aucune catégorie active trouvée.
                            </p>
                        </div>
                    @else
                        <div class="rounded-2xl border border-emerald-500/50 bg-emerald-900/20 p-3 mb-3">
                            <p class="text-emerald-300 text-sm">
                                <i class="fas fa-check-circle mr-2"></i>
                                {{ count($categories) }} catégorie(s) active(s) disponible(s)
                            </p>
                        </div>
                    @endif

                    {{-- Select direct pour fiabilité --}}
                    <select id="category_id"
                            name="category_id"
                            class="w-full rounded-2xl border-2 border-cyan-500/50 bg-slate-900/95 px-4 py-3 text-slate-100 font-medium focus:border-cyan-400 focus:ring-2 focus:ring-cyan-300/30 transition-all duration-200"
                            required>
                        <option value="">-- Sélectionner une catégorie --</option>
                        @foreach($categories as $category)
                            {{-- On filtre les catégories actives (actif = 1) --}}
                            @if($category->actif ?? true)
                                <option value="{{ $category->id }}"
                                    {{ old('category_id', $article->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                    {{ $category->nom ?? $category->name ?? 'Catégorie sans nom' }}
                                    @if($category->parent_id)
                                        &nbsp;(sous-catégorie)
                                    @endif
                                </option>
                            @endif
                        @endforeach
                    </select>

                    @error('category_id')
                        <div class="mt-2 text-sm text-rose-400 font-semibold">{{ $message }}</div>
                    @enderror
                    <p class="mt-2 text-sm text-slate-500">Seules les catégories actives sont affichées.</p>

                    {{-- Articles liés à la catégorie sélectionnée --}}
                    <div id="category-articles-container" class="mt-4 hidden rounded-3xl border border-slate-700 bg-slate-950/90 p-4">
                        <div class="flex items-center gap-2 text-slate-100 mb-3">
                            <i class="fas fa-link text-cyan-300"></i>
                            <h3 class="text-sm font-semibold">Articles associés à cette catégorie</h3>
                        </div>
                        <div id="category-articles-list" class="space-y-2 text-slate-300"></div>
                    </div>
                </div>

                {{-- Auteur (user_id) --}}
                <div>
                    <label for="user_id" class="block text-sm font-semibold text-slate-200 mb-2">
                        Auteur <span class="text-rose-500">*</span>
                    </label>
                    <!--<select id="user_id"
                            name="user_id"
                            class="w-full rounded-2xl border border-slate-700 bg-slate-950/80 px-4 py-3 text-slate-100 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-300/20"
                            required>
                        <option value="">-- Sélectionner un auteur --</option>
                        @foreach($users ?? [] as $user)
                            <option value="{{ $user->id }}"
                                {{ old('user_id', $article->user_id ?? auth()->id()) == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>--->
                    <input type="text">
                    @error('user_id')
                        <div class="mt-2 text-sm text-rose-400">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- ===== EXTRAIT + CONTENU ===== --}}
            <div class="grid gap-6 lg:grid-cols-2">
                <div>
                    <label for="extrait" class="block text-sm font-semibold text-slate-200 mb-2">Extrait</label>
                    <textarea id="extrait"
                              name="extrait"
                              rows="4"
                              class="w-full rounded-2xl border border-slate-700 bg-slate-950/80 px-4 py-3 text-slate-100 placeholder:text-slate-500 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-300/20"
                              placeholder="Résumé court de l'article...">{{ old('extrait', $article->extrait ?? '') }}</textarea>
                    @error('extrait')
                        <div class="mt-2 text-sm text-rose-400">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label for="contenu" class="block text-sm font-semibold text-slate-200 mb-2">
                        Contenu <span class="text-rose-500">*</span>
                    </label>
                    <textarea id="contenu"
                              name="contenu"
                              rows="10"
                              class="w-full rounded-2xl border border-slate-700 bg-slate-950/80 px-4 py-3 text-slate-100 placeholder:text-slate-500 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-300/20"
                              placeholder="Contenu complet de l'article..."
                              required>{{ old('contenu', $article->contenu ?? '') }}</textarea>
                    @error('contenu')
                        <div class="mt-2 text-sm text-rose-400">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- ===== IMAGE + DOCUMENT ===== --}}
            <div class="grid gap-6 lg:grid-cols-2">
                {{-- Image --}}
                <div>
                    <label for="image" class="block text-sm font-semibold text-slate-200 mb-2">Image de l'article</label>
                    <div class="flex gap-2">
                        <input type="file"
                               id="image"
                               name="image"
                               accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                               class="w-full rounded-2xl border border-slate-700 bg-slate-950/80 px-4 py-3 text-slate-100"
                               onchange="previewImage(this)">
                        <button type="button" onclick="clearImagePreview()" class="btn btn-secondary inline-flex items-center gap-2">
                            <i class="fas fa-times"></i> Effacer
                        </button>
                    </div>

                    <div id="image-preview-container" class="mt-4 hidden rounded-3xl border border-slate-700 bg-slate-950/90 p-4">
                        <div class="flex flex-col lg:flex-row gap-4 items-start">
                            <img id="image-preview" src="" alt="Prévisualisation" class="h-40 w-full rounded-3xl object-cover lg:w-1/2">
                            <div class="space-y-2 text-slate-300">
                                <h3 class="text-lg font-semibold text-white">Prévisualisation</h3>
                                <p><span class="font-semibold">Nom :</span> <span id="image-name"></span></p>
                                <p><span class="font-semibold">Taille :</span> <span id="image-size"></span></p>
                                <p><span class="font-semibold">Type :</span> <span id="image-type"></span></p>
                                <div class="rounded-2xl bg-emerald-500/10 border border-emerald-400/30 p-3 text-emerald-200">
                                    <i class="fas fa-check-circle"></i> Image prête pour publication.
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(isset($article) && $article->image)
                        <div class="mt-4 rounded-3xl border border-slate-700 bg-slate-950/90 p-4">
                            <div class="flex items-center gap-3 text-slate-100 mb-3">
                                <i class="fas fa-image text-cyan-300"></i>
                                <span>Image actuelle</span>
                                @if($article->storage_size)
                                    <span class="text-xs text-slate-500">({{ number_format($article->storage_size / 1024, 1) }} KB)</span>
                                @endif
                            </div>
                            <img src="{{ asset('storage/' . $article->image) }}"
                                 alt="Image actuelle"
                                 class="h-32 w-full max-w-xs rounded-3xl object-cover border border-slate-700">
                        </div>
                    @endif

                    <p class="mt-3 text-sm text-slate-500">Formats : JPG, PNG, GIF, WEBP. Max 2MB.</p>
                    @error('image')
                        <div class="mt-2 text-sm text-rose-400">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Document --}}
                <div>
                    <label for="document" class="block text-sm font-semibold text-slate-200 mb-2">Document à télécharger</label>
                    <div class="flex gap-2">
                        <input type="file"
                               id="document"
                               name="document"
                               accept=".pdf,.doc,.docx,.txt,.xls,.xlsx,.ppt,.pptx"
                               class="w-full rounded-2xl border border-slate-700 bg-slate-950/80 px-4 py-3 text-slate-100"
                               onchange="previewDocument(this)">
                        <button type="button" onclick="clearDocumentPreview()" class="btn btn-secondary inline-flex items-center gap-2">
                            <i class="fas fa-times"></i> Effacer
                        </button>
                    </div>

                    <div id="document-preview-container" class="mt-4 hidden rounded-3xl border border-slate-700 bg-slate-950/90 p-4">
                        <div class="flex flex-col gap-4 lg:flex-row lg:items-center">
                            <div class="flex h-24 w-24 items-center justify-center rounded-3xl bg-slate-800 text-3xl text-cyan-300">
                                <i id="document-icon" class="fas fa-file-alt"></i>
                            </div>
                            <div class="space-y-2 text-slate-300">
                                <h3 class="text-lg font-semibold text-white">Document sélectionné</h3>
                                <p><span class="font-semibold">Nom :</span> <span id="document-name"></span></p>
                                <p><span class="font-semibold">Taille :</span> <span id="document-size"></span></p>
                                <p><span class="font-semibold">Type :</span> <span id="document-type"></span></p>
                                <div class="rounded-2xl bg-emerald-500/10 border border-emerald-400/30 p-3 text-emerald-200">
                                    Le fichier est prêt pour téléchargement.
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(isset($article) && $article->document_path)
                        <div class="mt-4 rounded-3xl border border-slate-700 bg-slate-950/90 p-4">
                            <div class="flex items-center justify-between gap-3 text-slate-100">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-file-alt text-cyan-300"></i>
                                    <div>
                                        <span>Document actuel</span>
                                        @if($article->file_size)
                                            <span class="block text-xs text-slate-500">{{ number_format($article->file_size / 1024, 1) }} KB</span>
                                        @endif
                                    </div>
                                </div>
                                <a href="{{ asset('storage/' . $article->document_path) }}"
                                   target="_blank"
                                   class="btn btn-secondary inline-flex items-center gap-2">
                                    <i class="fas fa-external-link-alt"></i> Voir
                                </a>
                            </div>
                            <p class="mt-3 text-sm text-slate-500">{{ $article->file_original_name ?? 'Document existant' }}</p>
                        </div>
                    @endif

                    <p class="mt-3 text-sm text-slate-500">Formats : PDF, DOC, DOCX, TXT, XLS, XLSX, PPT, PPTX. Max 10MB.</p>
                    @error('document')
                        <div class="mt-2 text-sm text-rose-400">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- ===== TYPE + PRIX + TÉLÉCHARGEMENTS ===== --}}
            <div class="grid gap-6 lg:grid-cols-3">
                <div>
                    <label for="article_type" class="block text-sm font-semibold text-slate-200 mb-2">Type d'article</label>
                    <select id="article_type"
                            name="article_type"
                            class="w-full rounded-2xl border border-slate-700 bg-slate-950/80 px-4 py-3 text-slate-100 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-300/20">
                        <option value="">Sélectionner un type</option>
                        <option value="breve"       {{ old('article_type', $article->article_type ?? '') == 'breve'       ? 'selected' : '' }}>Brève</option>
                        <option value="communique"  {{ old('article_type', $article->article_type ?? '') == 'communique'  ? 'selected' : '' }}>Communiqué</option>
                        <option value="analyse"     {{ old('article_type', $article->article_type ?? '') == 'analyse'     ? 'selected' : '' }}>Analyse</option>
                        <option value="enquete"     {{ old('article_type', $article->article_type ?? '') == 'enquete'     ? 'selected' : '' }}>Enquête</option>
                        <option value="interview"   {{ old('article_type', $article->article_type ?? '') == 'interview'   ? 'selected' : '' }}>Interview</option>
                        <option value="tutoriel"    {{ old('article_type', $article->article_type ?? '') == 'tutoriel'    ? 'selected' : '' }}>Tutoriel</option>
                        <option value="explicatif"  {{ old('article_type', $article->article_type ?? '') == 'explicatif'  ? 'selected' : '' }}>Explicatif</option>
                    </select>
                    @error('article_type')
                        <div class="mt-2 text-sm text-rose-400">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label for="unit_price" class="block text-sm font-semibold text-slate-200 mb-2">Prix unitaire</label>
                    <input type="number"
                           step="0.01"
                           min="0"
                           id="unit_price"
                           name="unit_price"
                           value="{{ old('unit_price', $article->unit_price ?? '') }}"
                           class="w-full rounded-2xl border border-slate-700 bg-slate-950/80 px-4 py-3 text-slate-100 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-300/20"
                           placeholder="0.00">
                    @error('unit_price')
                        <div class="mt-2 text-sm text-rose-400">{{ $message }}</div>
                    @enderror
                    <p class="mt-2 text-sm text-slate-500">Laisser vide pour désactiver la vente à l'unité.</p>
                </div>

                <div>
                    <label for="free_download_limit" class="block text-sm font-semibold text-slate-200 mb-2">Limite téléchargements gratuits</label>
                    <input type="number"
                           id="free_download_limit"
                           name="free_download_limit"
                           min="0"
                           value="{{ old('free_download_limit', $article->free_download_limit ?? '') }}"
                           class="w-full rounded-2xl border border-slate-700 bg-slate-950/80 px-4 py-3 text-slate-100 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-300/20"
                           placeholder="0">
                    @error('free_download_limit')
                        <div class="mt-2 text-sm text-rose-400">{{ $message }}</div>
                    @enderror
                    <p class="mt-2 text-sm text-slate-500">0 = illimité pour les contenus gratuits.</p>
                </div>
            </div>

            {{-- ===== QUALITÉ + DATE DE PUBLICATION ===== --}}
            <div class="grid gap-6 lg:grid-cols-2 items-end">
                <div class="space-y-3">
                    <label for="content_quality" class="block text-sm font-semibold text-slate-200">Qualité du contenu</label>
                    <div class="flex items-center gap-4">
                        <input type="range"
                               id="content_quality"
                               name="content_quality"
                               min="0" max="100" step="1"
                               value="{{ old('content_quality', $article->content_quality ?? 50) }}"
                               class="w-full"
                               oninput="document.getElementById('content_quality_value').textContent = this.value;">
                        <span id="content_quality_value" class="min-w-[48px] text-slate-100 font-mono">
                            {{ old('content_quality', $article->content_quality ?? 50) }}
                        </span>
                    </div>
                    @error('content_quality')
                        <div class="mt-2 text-sm text-rose-400">{{ $message }}</div>
                    @enderror
                    <p class="text-sm text-slate-500">0 = court / brut, 100 = analyse complète.</p>
                </div>

                <div>
                    <label for="published_at" class="block text-sm font-semibold text-slate-200 mb-2">
                        Date de publication
                        <span class="text-slate-500 font-normal">(optionnel)</span>
                    </label>
                    <input type="datetime-local"
                           id="published_at"
                           name="published_at"
                           value="{{ old('published_at', isset($article->published_at) ? \Carbon\Carbon::parse($article->published_at)->format('Y-m-d\TH:i') : '') }}"
                           class="w-full rounded-2xl border border-slate-700 bg-slate-950/80 px-4 py-3 text-slate-100 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-300/20">
                    @error('published_at')
                        <div class="mt-2 text-sm text-rose-400">{{ $message }}</div>
                    @enderror
                    <p class="mt-2 text-sm text-slate-500">Laisser vide pour publier immédiatement (si activé).</p>
                </div>
            </div>

            {{-- ===== OPTIONS DE PUBLICATION ===== --}}
            <div class="rounded-2xl border border-slate-700 bg-slate-900/50 p-5 space-y-4">
                <h3 class="text-sm font-bold text-slate-200 uppercase tracking-wider">
                    <i class="fas fa-sliders-h text-cyan-300 mr-2"></i>Options de publication
                </h3>

                <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                    {{-- is_published --}}
                    <label class="inline-flex items-center gap-3 rounded-2xl border border-slate-700 bg-slate-950/80 px-4 py-3 cursor-pointer hover:border-cyan-500/50 transition-colors">
                        <input type="checkbox" id="is_published" name="is_published" value="1"
                               {{ old('is_published', $article->is_published ?? false) ? 'checked' : '' }}
                               class="h-5 w-5 rounded border-slate-700 bg-slate-900 text-cyan-400 focus:ring-cyan-300">
                        <div>
                            <span class="text-slate-100 font-medium block">Publier</span>
                            <span class="text-xs text-slate-500">Visible sur le site</span>
                        </div>
                    </label>

                    {{-- is_premium --}}
                    <label class="inline-flex items-center gap-3 rounded-2xl border border-slate-700 bg-slate-950/80 px-4 py-3 cursor-pointer hover:border-cyan-500/50 transition-colors">
                        <input type="checkbox" id="is_premium" name="is_premium" value="1"
                               {{ old('is_premium', $article->is_premium ?? false) ? 'checked' : '' }}
                               class="h-5 w-5 rounded border-slate-700 bg-slate-900 text-cyan-400 focus:ring-cyan-300">
                        <div>
                            <span class="text-slate-100 font-medium block">Premium</span>
                            <span class="text-xs text-slate-500">Réservé aux abonnés</span>
                        </div>
                    </label>

                    {{-- is_featured --}}
                    <label class="inline-flex items-center gap-3 rounded-2xl border border-slate-700 bg-slate-950/80 px-4 py-3 cursor-pointer hover:border-cyan-500/50 transition-colors">
                        <input type="checkbox" id="is_featured" name="is_featured" value="1"
                               {{ old('is_featured', $article->is_featured ?? false) ? 'checked' : '' }}
                               class="h-5 w-5 rounded border-slate-700 bg-slate-900 text-cyan-400 focus:ring-cyan-300">
                        <div>
                            <span class="text-slate-100 font-medium block">Mis en avant</span>
                            <span class="text-xs text-slate-500">Encarts premium</span>
                        </div>
                    </label>

                    {{-- featured_on_homepage --}}
                    <label class="inline-flex items-center gap-3 rounded-2xl border border-slate-700 bg-slate-950/80 px-4 py-3 cursor-pointer hover:border-cyan-500/50 transition-colors">
                        <input type="checkbox" id="featured_on_homepage" name="featured_on_homepage" value="1"
                               {{ old('featured_on_homepage', $article->featured_on_homepage ?? false) ? 'checked' : '' }}
                               class="h-5 w-5 rounded border-slate-700 bg-slate-900 text-cyan-400 focus:ring-cyan-300">
                        <div>
                            <span class="text-slate-100 font-medium block">Page d'accueil</span>
                            <span class="text-xs text-slate-500">Affiché en homepage</span>
                        </div>
                    </label>
                </div>
            </div>

            {{-- ===== STATISTIQUES (lecture seule en édition) ===== --}}
            @if(isset($article))
                <div class="rounded-2xl border border-slate-700 bg-slate-900/30 p-5">
                    <h3 class="text-sm font-bold text-slate-200 uppercase tracking-wider mb-4">
                        <i class="fas fa-chart-bar text-cyan-300 mr-2"></i>Statistiques
                    </h3>
                    <div class="grid gap-4 sm:grid-cols-3">
                        <div class="rounded-2xl bg-slate-950/80 border border-slate-700 px-4 py-3 text-center">
                            <div class="text-2xl font-bold text-cyan-300">{{ number_format($article->views_count ?? 0) }}</div>
                            <div class="text-xs text-slate-500 mt-1">Vues</div>
                        </div>
                        <div class="rounded-2xl bg-slate-950/80 border border-slate-700 px-4 py-3 text-center">
                            <div class="text-2xl font-bold text-cyan-300">{{ number_format($article->download_count ?? 0) }}</div>
                            <div class="text-xs text-slate-500 mt-1">Téléchargements</div>
                        </div>
                        <div class="rounded-2xl bg-slate-950/80 border border-slate-700 px-4 py-3 text-center">
                            <div class="text-2xl font-bold text-cyan-300">
                                {{ $article->storage_size ? number_format($article->storage_size / 1024, 1) . ' KB' : '—' }}
                            </div>
                            <div class="text-xs text-slate-500 mt-1">Taille stockage</div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- ===== BOUTONS ===== --}}
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-end">
                <a href="{{ route('admin.articles.index') }}" class="btn btn-secondary inline-flex items-center gap-2 justify-center">
                    <i class="fas fa-arrow-left"></i> Annuler
                </a>
                <button type="submit" class="btn btn-primary inline-flex items-center gap-2 justify-center">
                    <i class="fas fa-save"></i>
                    {{ isset($article) ? 'Mettre à jour' : 'Créer l\'article' }}
                </button>
            </div>

        </form>
    </div>
</div>

@push('scripts')
<style>
    select {
        font-family: 'Inter', 'Segoe UI', sans-serif;
        appearance: none;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
    }
    select:hover { border-color: #22d3ee !important; }
    select:focus {
        outline: none;
        border-color: #0ea5e9 !important;
        box-shadow: 0 0 0 3px rgba(34, 211, 238, 0.1) !important;
    }
    select option { background-color: #0f172a; color: #e2e8f0; }
    select option:checked {
        background-color: #0ea5e9 !important;
        color: #ffffff !important;
        font-weight: 600;
    }
</style>

<script>
    // ===== AUTO-SLUG depuis le TITRE =====
    const titreInput = document.getElementById('titre');
    const slugInput  = document.getElementById('slug');

    if (titreInput && slugInput) {
        titreInput.addEventListener('input', function () {
            // Ne remplace le slug que s'il est vide (ou identique à l'ancien auto-généré)
            if (!slugInput.dataset.manuallyEdited) {
                slugInput.value = generateSlug(titreInput.value);
            }
        });
        slugInput.addEventListener('input', function () {
            slugInput.dataset.manuallyEdited = '1';
        });
    }

    function generateSlug(text) {
        return text
            .toLowerCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')   // Supprime les accents
            .replace(/[^a-z0-9\s-]/g, '')
            .trim()
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-');
    }

    // ===== PREVIEW IMAGE =====
    function previewImage(input) {
        const file = input.files[0];
        const container = document.getElementById('image-preview-container');
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('image-preview').src = e.target.result;
                document.getElementById('image-name').textContent = file.name;
                document.getElementById('image-size').textContent = formatFileSize(file.size);
                document.getElementById('image-type').textContent = file.type;
                container.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        } else {
            clearImagePreview();
            if (file) { alert('Image invalide (JPG, PNG, GIF, WEBP uniquement).'); input.value = ''; }
        }
    }

    function clearImagePreview() {
        document.getElementById('image-preview').src = '';
        document.getElementById('image-name').textContent = '';
        document.getElementById('image-size').textContent = '';
        document.getElementById('image-type').textContent = '';
        document.getElementById('image-preview-container').classList.add('hidden');
        document.getElementById('image').value = '';
    }

    // ===== PREVIEW DOCUMENT =====
    function previewDocument(input) {
        const file = input.files[0];
        if (file) {
            document.getElementById('document-name').textContent = file.name;
            document.getElementById('document-size').textContent = formatFileSize(file.size);
            document.getElementById('document-type').textContent = file.type || 'Fichier';
            const ext = file.name.split('.').pop().toLowerCase();
            const iconMap = { pdf: 'fa-file-pdf', doc: 'fa-file-word', docx: 'fa-file-word', xls: 'fa-file-excel', xlsx: 'fa-file-excel', ppt: 'fa-file-powerpoint', pptx: 'fa-file-powerpoint', txt: 'fa-file-lines' };
            document.getElementById('document-icon').className = 'fas ' + (iconMap[ext] || 'fa-file-alt');
            document.getElementById('document-preview-container').classList.remove('hidden');
        } else {
            clearDocumentPreview();
        }
    }

    function clearDocumentPreview() {
        document.getElementById('document-name').textContent = '';
        document.getElementById('document-size').textContent = '';
        document.getElementById('document-type').textContent = '';
        document.getElementById('document-icon').className = 'fas fa-file-alt';
        document.getElementById('document-preview-container').classList.add('hidden');
        document.getElementById('document').value = '';
    }

    function formatFileSize(bytes) {
        if (bytes < 1024) return bytes + ' B';
        const sizes = ['KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(1024));
        return parseFloat((bytes / Math.pow(1024, i)).toFixed(2)) + ' ' + sizes[i - 1];
    }

    // ===== ARTICLES LIÉS À LA CATÉGORIE =====
    const categoryArticlesData = {!! json_encode(
        $categoryArticles->map(function ($category) {
            return [
                'id'       => $category->id,
                'nom'      => $category->nom ?? $category->name ?? 'Catégorie sans nom',
                'articles' => $category->articles->map(fn($a) => ['id' => $a->id, 'titre' => $a->titre])->values()->toArray(),
            ];
        })->toArray()
    ) !!};

    const categorySelect = document.getElementById('category_id');

    if (categorySelect) {
        categorySelect.addEventListener('change', updateCategoryArticles);
        // Déclenche au chargement si une catégorie est déjà sélectionnée
        if (categorySelect.value) updateCategoryArticles();
    }

    function updateCategoryArticles() {
        const selectedId = parseInt(categorySelect.value);
        const container  = document.getElementById('category-articles-container');
        const list       = document.getElementById('category-articles-list');

        if (!selectedId) {
            container.classList.add('hidden');
            list.innerHTML = '';
            return;
        }

        const category = categoryArticlesData.find(c => c.id === selectedId);

        if (!category) {
            container.classList.add('hidden');
            return;
        }

        if (!category.articles || category.articles.length === 0) {
            list.innerHTML = '<p class="text-slate-400 italic"><i class="fas fa-info-circle mr-2"></i>Aucun article lié à cette catégorie.</p>';
        } else {
            list.innerHTML = category.articles.map(a => `
                <div class="rounded-2xl bg-slate-900/80 border border-slate-700 px-4 py-3 text-slate-100 text-sm">
                    <i class="fas fa-file-lines text-cyan-400 mr-2"></i>${a.titre}
                </div>
            `).join('');
        }

        container.classList.remove('hidden');
    }

    // ===== LIAISON is_published ↔ published_at =====
    const isPublishedCheckbox = document.getElementById('is_published');
    const publishedAtInput    = document.getElementById('published_at');

    if (isPublishedCheckbox && publishedAtInput) {
        isPublishedCheckbox.addEventListener('change', function () {
            if (this.checked && !publishedAtInput.value) {
                const now = new Date();
                publishedAtInput.value = now.toISOString().slice(0, 16);
            }
        });
    }
</script>
@endpush