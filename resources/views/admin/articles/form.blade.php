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

            <div class="grid gap-6 lg:grid-cols-2">
                <div>
                    <label for="titre" class="block text-sm font-semibold text-slate-200 mb-2">Titre</label>
                    <input type="text"
                           id="titre"
                           name="titre"
                           value="{{ old('titre', $article->titre ?? '') }}"
                           class="w-full rounded-2xl border border-slate-700 bg-slate-950/80 px-4 py-3 text-slate-100 placeholder:text-slate-500 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-300/20"
                           required>
                    @error('titre')
                        <div class="mt-2 text-sm text-rose-400">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label for="category_id" class="block text-sm font-semibold text-slate-200 mb-2">Catégorie <span class="text-rose-500">*</span></label>
                    
                    {{-- Débogage: Affiche le nombre de catégories --}}
                    @if(empty($categories) || count($categories) === 0)
                        <div class="rounded-2xl border border-rose-500/50 bg-rose-900/20 p-3 mb-3">
                            <p class="text-rose-300 text-sm"><i class="fas fa-exclamation-triangle mr-2"></i> Aucune catégorie trouvée dans la base de données.</p>
                        </div>
                    @else
                        <div class="rounded-2xl border border-emerald-500/50 bg-emerald-900/20 p-3 mb-3">
                            <p class="text-emerald-300 text-sm"><i class="fas fa-check-circle mr-2"></i> {{ count($categories) }} catégorie(s) disponible(s)</p>
                        </div>
                    @endif
                    
                    <select id="category_id"
                            name="category_id"
                            class="w-full rounded-2xl border-2 border-cyan-500/50 bg-slate-900/95 px-4 py-3 text-slate-100 font-medium focus:border-cyan-400 focus:ring-2 focus:ring-cyan-300/30 transition-all duration-200 appearance-none cursor-pointer hover:border-cyan-400"
                            style="background-image: url('data:image/svg+xml;utf8,<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"%2322d3ee\" stroke-width=\"2\"><polyline points=\"6 9 12 15 18 9\"></polyline></svg>'); background-repeat: no-repeat; background-position: right 0.75rem center; background-size: 1.5em; padding-right: 2.5rem;"
                            required>
                        <option value="" style="background-color: #0f172a; color: #cbd5e1;">-- Sélectionnez une catégorie --</option>
                        
                        @forelse($categories as $category)
                            <option value="{{ $category->id }}"
                                style="background-color: #0f172a; color: #e2e8f0;"
                                {{ old('category_id', $article->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                {{ $category->nom ?? $category->name ?? 'Catégorie sans nom' }} (ID: {{ $category->id }})
                            </option>
                        @empty
                            <option value="" disabled style="background-color: #0f172a; color: #64748b;">
                                ⚠️ Aucune catégorie disponible
                            </option>
                        @endforelse
                    </select>
                    @error('category_id')
                        <div class="mt-2 text-sm text-rose-400 font-semibold">{{ $message }}</div>
                    @enderror
                    <p class="mt-2 text-sm text-slate-400">Choisissez la catégorie qui correspond le mieux à cet article.</p>

                    <div id="category-articles-container" class="mt-4 hidden rounded-3xl border border-slate-700 bg-slate-950/90 p-4">
                        <div class="flex items-center gap-2 text-slate-100 mb-3">
                            <i class="fas fa-link text-cyan-300"></i>
                            <h3 class="text-sm font-semibold">Articles associés à la catégorie</h3>
                        </div>
                        <div id="category-articles-list" class="space-y-2 text-slate-300"></div>
                    </div>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <div>
                    <label for="extrait" class="block text-sm font-semibold text-slate-200 mb-2">Extrait</label>
                    <textarea id="extrait"
                              name="extrait"
                              rows="4"
                              class="w-full rounded-2xl border border-slate-700 bg-slate-950/80 px-4 py-3 text-slate-100 placeholder:text-slate-500 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-300/20">{{ old('extrait', $article->extrait ?? '') }}</textarea>
                    @error('extrait')
                        <div class="mt-2 text-sm text-rose-400">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label for="contenu" class="block text-sm font-semibold text-slate-200 mb-2">Contenu</label>
                    <textarea id="contenu"
                              name="contenu"
                              rows="10"
                              class="w-full rounded-2xl border border-slate-700 bg-slate-950/80 px-4 py-3 text-slate-100 placeholder:text-slate-500 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-300/20"
                              required>{{ old('contenu', $article->contenu ?? '') }}</textarea>
                    @error('contenu')
                        <div class="mt-2 text-sm text-rose-400">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <div>
                    <label for="image" class="block text-sm font-semibold text-slate-200 mb-2">Image de l'article</label>
                    <div class="flex gap-2">
                        <input type="file"
                               id="image"
                               name="image"
                               accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                               class="w-full rounded-2xl border border-slate-700 bg-slate-950/80 px-4 py-3 text-slate-100"
                               onchange="previewImage(this)">
                        <button type="button"
                                onclick="clearImagePreview()"
                                class="btn btn-secondary inline-flex items-center gap-2">
                            <i class="fas fa-times"></i>
                            Effacer
                        </button>
                    </div>

                    <div id="image-preview-container" class="mt-4 hidden rounded-3xl border border-slate-700 bg-slate-950/90 p-4">
                        <div class="flex flex-col lg:flex-row gap-4 items-start">
                            <img id="image-preview" src="" alt="Prévisualisation" class="h-40 w-full rounded-3xl object-cover lg:w-1/2">
                            <div class="space-y-2 text-slate-300">
                                <h3 class="text-lg font-semibold text-white">Prévisualisation de l'image</h3>
                                <p><span class="font-semibold">Nom :</span> <span id="image-name"></span></p>
                                <p><span class="font-semibold">Taille :</span> <span id="image-size"></span></p>
                                <p><span class="font-semibold">Type :</span> <span id="image-type"></span></p>
                                <div class="rounded-2xl bg-emerald-500/10 border border-emerald-400/30 p-3 text-emerald-200">
                                    <i class="fas fa-check-circle"></i>
                                    Image prête pour publication.
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(isset($article) && $article->image)
                        <div class="mt-4 rounded-3xl border border-slate-700 bg-slate-950/90 p-4">
                            <div class="flex items-center gap-3 text-slate-100">
                                <i class="fas fa-image text-cyan-300"></i>
                                <span>Image actuelle</span>
                            </div>
                            <img src="{{ asset('storage/' . $article->image) }}"
                                 alt="Image actuelle"
                                 class="mt-3 h-32 w-full max-w-xs rounded-3xl object-cover border border-slate-700">
                        </div>
                    @endif

                    <p class="mt-3 text-sm text-slate-500">Formats acceptés : JPG, PNG, GIF, WEBP. Taille max 2MB.</p>
                    @error('image')
                        <div class="mt-2 text-sm text-rose-400">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label for="document" class="block text-sm font-semibold text-slate-200 mb-2">Document à télécharger</label>
                    <div class="flex gap-2">
                        <input type="file"
                               id="document"
                               name="document"
                               accept=".pdf,.doc,.docx,.txt,.xls,.xlsx,.ppt,.pptx"
                               class="w-full rounded-2xl border border-slate-700 bg-slate-950/80 px-4 py-3 text-slate-100"
                               onchange="previewDocument(this)">
                        <button type="button"
                                onclick="clearDocumentPreview()"
                                class="btn btn-secondary inline-flex items-center gap-2">
                            <i class="fas fa-times"></i>
                            Effacer
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
                                    <span>Document actuel</span>
                                </div>
                                <a href="{{ asset('storage/' . $article->document_path) }}"
                                   target="_blank"
                                   class="btn btn-secondary inline-flex items-center gap-2">
                                    <i class="fas fa-external-link-alt"></i>
                                    Voir
                                </a>
                            </div>
                            <p class="mt-3 text-sm text-slate-500">{{ $article->file_original_name ?? 'Document existant' }}</p>
                        </div>
                    @endif

                    <p class="mt-3 text-sm text-slate-500">Formats acceptés : PDF, DOC, DOCX, TXT, XLS, XLSX, PPT, PPTX. Taille max 10MB.</p>
                    @error('document')
                        <div class="mt-2 text-sm text-rose-400">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-3">
                <div>
                    <label for="article_type" class="block text-sm font-semibold text-slate-200 mb-2">Type d'article</label>
                    <select id="article_type"
                            name="article_type"
                            class="w-full rounded-2xl border border-slate-700 bg-slate-950/80 px-4 py-3 text-slate-100 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-300/20">
                        <option value="">Sélectionner un type</option>
                        <option value="breve" {{ old('article_type', $article->article_type ?? '') == 'breve' ? 'selected' : '' }}>Brève</option>
                        <option value="communique" {{ old('article_type', $article->article_type ?? '') == 'communique' ? 'selected' : '' }}>Communiqué</option>
                        <option value="analyse" {{ old('article_type', $article->article_type ?? '') == 'analyse' ? 'selected' : '' }}>Analyse</option>
                        <option value="enquete" {{ old('article_type', $article->article_type ?? '') == 'enquete' ? 'selected' : '' }}>Enquête</option>
                        <option value="interview" {{ old('article_type', $article->article_type ?? '') == 'interview' ? 'selected' : '' }}>Interview</option>
                        <option value="tutoriel" {{ old('article_type', $article->article_type ?? '') == 'tutoriel' ? 'selected' : '' }}>Tutoriel</option>
                        <option value="explicatif" {{ old('article_type', $article->article_type ?? '') == 'explicatif' ? 'selected' : '' }}>Explicatif</option>
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
                           class="w-full rounded-2xl border border-slate-700 bg-slate-950/80 px-4 py-3 text-slate-100 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-300/20">
                    @error('unit_price')
                        <div class="mt-2 text-sm text-rose-400">{{ $message }}</div>
                    @enderror
                    <p class="mt-2 text-sm text-slate-500">Laisser vide pour désactiver la vente à l'unité.</p>
                </div>

                <div>
                    <label for="free_download_limit" class="block text-sm font-semibold text-slate-200 mb-2">Limite de téléchargements gratuits</label>
                    <input type="number"
                           id="free_download_limit"
                           name="free_download_limit"
                           min="0"
                           value="{{ old('free_download_limit', $article->free_download_limit ?? '') }}"
                           class="w-full rounded-2xl border border-slate-700 bg-slate-950/80 px-4 py-3 text-slate-100 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-300/20">
                    @error('free_download_limit')
                        <div class="mt-2 text-sm text-rose-400">{{ $message }}</div>
                    @enderror
                    <p class="mt-2 text-sm text-slate-500">0 = illimité pour les contenus gratuits.</p>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-2 items-end">
                <div class="space-y-3">
                    <label for="content_quality" class="block text-sm font-semibold text-slate-200">Qualité du contenu</label>
                    <div class="flex items-center gap-4">
                        <input type="range"
                               id="content_quality"
                               name="content_quality"
                               min="0"
                               max="100"
                               step="1"
                               value="{{ old('content_quality', $article->content_quality ?? 50) }}"
                               class="w-full"
                               oninput="document.getElementById('content_quality_value').textContent = this.value;">
                        <span id="content_quality_value" class="min-w-[48px] text-slate-100">{{ old('content_quality', $article->content_quality ?? 50) }}</span>
                    </div>
                    @error('content_quality')
                        <div class="mt-2 text-sm text-rose-400">{{ $message }}</div>
                    @enderror
                    <p class="text-sm text-slate-500">0 = court, 100 = analyse complète.</p>
                </div>

                <div class="grid gap-3">
                    <label class="inline-flex items-center gap-3 rounded-2xl border border-slate-700 bg-slate-950/80 px-4 py-3">
                        <input type="checkbox" id="is_featured" name="is_featured" value="1"
                               {{ old('is_featured', $article->is_featured ?? false) ? 'checked' : '' }}
                               class="h-5 w-5 rounded border-slate-700 bg-slate-900 text-cyan-400 focus:ring-cyan-300">
                        <span class="text-slate-100 font-medium">Mettre en avant</span>
                    </label>
                    <p class="text-sm text-slate-500">Activez pour rendre l'article éligible à la page d'accueil ou aux encarts premium.</p>
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <label class="inline-flex items-center gap-3 rounded-2xl border border-slate-700 bg-slate-950/80 px-4 py-3">
                    <input type="checkbox" id="is_premium" name="is_premium" value="1"
                           {{ old('is_premium', $article->is_premium ?? false) ? 'checked' : '' }}
                           class="h-5 w-5 rounded border-slate-700 bg-slate-900 text-cyan-400 focus:ring-cyan-300">
                    <span class="text-slate-100 font-medium">Article Premium</span>
                </label>
                <label class="inline-flex items-center gap-3 rounded-2xl border border-slate-700 bg-slate-950/80 px-4 py-3">
                    <input type="checkbox" id="is_published" name="is_published" value="1"
                           {{ old('is_published', $article->is_published ?? false) ? 'checked' : '' }}
                           class="h-5 w-5 rounded border-slate-700 bg-slate-900 text-cyan-400 focus:ring-cyan-300">
                    <span class="text-slate-100 font-medium">Publier l'article</span>
                </label>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-end">
                <a href="{{ route('admin.articles.index') }}" class="btn btn-secondary inline-flex items-center gap-2 justify-center">
                    <i class="fas fa-arrow-left"></i>
                    Annuler
                </a>
                <button type="submit" class="btn btn-primary inline-flex items-center gap-2 justify-center">
                    <i class="fas fa-save"></i>
                    {{ isset($article) ? 'Mettre à jour' : 'Créer' }}
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<style>
    /* === Styles globaux pour les selects du Dashboard === */
    select {
        font-family: 'Inter', 'Segoe UI', sans-serif;
        appearance: none;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
    }

    select:hover {
        border-color: #22d3ee !important;
    }

    select:focus {
        outline: none;
        border-color: #0ea5e9 !important;
        box-shadow: 0 0 0 3px rgba(34, 211, 238, 0.1) !important;
    }

    select option {
        background-color: #0f172a;
        color: #e2e8f0;
        padding: 8px 10px;
        margin: 4px 0;
    }

    select option:checked {
        background: linear-gradient(#0ea5e9, #0ea5e9);
        background-color: #0ea5e9 !important;
        color: #ffffff !important;
        font-weight: 600;
    }

    select option:hover {
        background-color: #1e293b !important;
        color: #e2e8f0 !important;
    }
</style>

<script>
    function previewImage(input) {
        const file = input.files[0];
        const previewContainer = document.getElementById('image-preview-container');
        const previewImg = document.getElementById('image-preview');
        const imageName = document.getElementById('image-name');
        const imageSize = document.getElementById('image-size');
        const imageType = document.getElementById('image-type');

        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                imageName.textContent = file.name;
                imageSize.textContent = formatFileSize(file.size);
                imageType.textContent = file.type;
                previewContainer.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        } else {
            clearImagePreview();
            if (file) {
                alert('Veuillez sélectionner une image valide (JPG, PNG, GIF, WEBP).');
                input.value = '';
            }
        }
    }

    function clearImagePreview() {
        const previewContainer = document.getElementById('image-preview-container');
        const previewImg = document.getElementById('image-preview');
        const imageName = document.getElementById('image-name');
        const imageSize = document.getElementById('image-size');
        const imageType = document.getElementById('image-type');

        previewImg.src = '';
        imageName.textContent = '';
        imageSize.textContent = '';
        imageType.textContent = '';
        previewContainer.classList.add('hidden');
    }

    function previewDocument(input) {
        const file = input.files[0];
        const previewContainer = document.getElementById('document-preview-container');
        const documentName = document.getElementById('document-name');
        const documentSize = document.getElementById('document-size');
        const documentType = document.getElementById('document-type');
        const documentIcon = document.getElementById('document-icon');

        if (file) {
            documentName.textContent = file.name;
            documentSize.textContent = formatFileSize(file.size);
            documentType.textContent = file.type || 'Fichier';
            const extension = file.name.split('.').pop().toLowerCase();
            documentIcon.className = 'fas fa-file-' + (['pdf', 'doc', 'docx'].includes(extension) ? extension : 'alt');
            previewContainer.classList.remove('hidden');
        } else {
            clearDocumentPreview();
        }
    }

    function clearDocumentPreview() {
        const previewContainer = document.getElementById('document-preview-container');
        const documentName = document.getElementById('document-name');
        const documentSize = document.getElementById('document-size');
        const documentType = document.getElementById('document-type');
        const documentIcon = document.getElementById('document-icon');

        documentName.textContent = '';
        documentSize.textContent = '';
        documentType.textContent = '';
        documentIcon.className = 'fas fa-file-alt';
        previewContainer.classList.add('hidden');
    }

    function formatFileSize(bytes) {
        if (bytes < 1024) return bytes + ' B';
        const sizes = ['KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(1024));
        return parseFloat((bytes / Math.pow(1024, i)).toFixed(2)) + ' ' + sizes[i - 1];
    }

    // Données des articles par catégorie
    const categoryArticlesData = {!! json_encode(
        $categoryArticles->map(function ($category) {
            return [
                'id' => $category->id,
                'nom' => $category->nom ?? $category->name ?? 'Catégorie sans nom',
                'articles' => $category->articles->map(function ($article) {
                    return ['id' => $article->id, 'titre' => $article->titre];
                })->values()->toArray(),
            ];
        })->toArray()
    ) !!};

    // Initialiser la sélection de catégorie
    const categorySelect = document.getElementById('category_id');
    if (categorySelect) {
        categorySelect.addEventListener('change', updateCategoryArticles);
        updateCategoryArticles();
    }

    function updateCategoryArticles() {
        const selectedId = parseInt(categorySelect.value, 10);
        const container = document.getElementById('category-articles-container');
        const list = document.getElementById('category-articles-list');

        if (!selectedId) {
            container.classList.add('hidden');
            list.innerHTML = '';
            return;
        }

        const category = categoryArticlesData.find(item => item.id === selectedId);
        if (!category) {
            container.classList.add('hidden');
            list.innerHTML = '';
            return;
        }

        if (!category.articles || category.articles.length === 0) {
            list.innerHTML = '<p class="text-slate-400 italic">Aucun article lié à cette catégorie.</p>';
        } else {
            list.innerHTML = category.articles.map(article => `
                <div class="rounded-2xl bg-slate-900/80 border border-slate-700 px-4 py-3 text-slate-100 text-sm">
                    <i class="fas fa-file-lines text-cyan-400 mr-2"></i>${article.titre}
                </div>
            `).join('');
        }

        container.classList.remove('hidden');
    }
</script>
@endpush
