@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">{{ isset($article) ? 'Modifier l\'article' : 'Créer un article' }}</h1>

    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ isset($article) ? route('admin.articles.update', $article) : route('admin.articles.store') }}"
                  method="POST"
                  enctype="multipart/form-data">
                @csrf
                @if(isset($article))
                    @method('PUT')
                @endif

                <div class="mb-3">
                    <label for="titre" class="form-label">Titre</label>
                    <input type="text" class="form-control @error('titre') is-invalid @enderror"
                           id="titre" name="titre" value="{{ old('titre', $article->titre ?? '') }}" required>
                    @error('titre')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="category_id" class="form-label">
                        <i class="fas fa-folder-open"></i> Catégorie
                    </label>
                    <select class="form-select @error('category_id') is-invalid @enderror"
                            id="category_id" name="category_id" required>
                        <option value="">📁 Sélectionner une catégorie</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ old('category_id', $article->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                🏷️ {{ $category->nom ?? $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Catégories disponibles sur votre navbar</small>
                </div>

                <div class="mb-3">
                    <label for="extrait" class="form-label">Extrait</label>
                    <textarea class="form-control @error('extrait') is-invalid @enderror"
                              id="extrait" name="extrait" rows="3">{{ old('extrait', $article->extrait ?? '') }}</textarea>
                    @error('extrait')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="contenu" class="form-label">Contenu</label>
                    <textarea class="form-control @error('contenu') is-invalid @enderror"
                              id="contenu" name="contenu" rows="10" required>{{ old('contenu', $article->contenu ?? '') }}</textarea>
                    @error('contenu')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">
                        <i class="fas fa-image"></i> Image de l'article
                    </label>
                    <div class="input-group">
                        <input type="file" class="form-control @error('image') is-invalid @enderror"
                               id="image" name="image" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                               onchange="previewImage(this)">
                        <button class="btn btn-outline-secondary" type="button" onclick="clearImagePreview()">
                            <i class="fas fa-times"></i> Effacer
                        </button>
                    </div>
                    
                    <!-- Zone de prévisualisation d'image -->
                    <div id="image-preview-container" class="mt-3" style="display: none;">
                        <div class="card border-success">
                            <div class="card-header bg-success text-white">
                                <i class="fas fa-eye"></i> Prévisualisation de l'image
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <img id="image-preview" src="" alt="Prévisualisation" class="img-fluid rounded shadow">
                                    </div>
                                    <div class="col-md-6">
                                        <h6><i class="fas fa-info-circle text-info"></i> Informations</h6>
                                        <p><strong>Nom:</strong> <span id="image-name"></span></p>
                                        <p><strong>Taille:</strong> <span id="image-size"></span></p>
                                        <p><strong>Type:</strong> <span id="image-type"></span></p>
                                        <div class="alert alert-success">
                                            <i class="fas fa-check-circle"></i> Image prête pour publication!
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @if(isset($article) && $article->image)
                        <div class="mt-2">
                            <div class="alert alert-info">
                                <i class="fas fa-image"></i> Image actuelle:
                                <img src="{{ asset('storage/' . $article->image) }}" alt="Image actuelle" class="img-thumbnail ms-2" style="max-width: 100px">
                            </div>
                        </div>
                    @endif
                    
                    <small class="form-text text-muted">
                        <i class="fas fa-info-circle"></i> Formats acceptés : JPG, PNG, GIF, WEBP (max 2MB)
                    </small>
                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="document" class="form-label">
                        <i class="fas fa-file-download"></i> Document à télécharger
                    </label>
                    <div class="input-group">
                        <input type="file" class="form-control @error('document') is-invalid @enderror"
                               id="document" name="document" accept=".pdf,.doc,.docx,.txt,.xls,.xlsx,.ppt,.pptx"
                               onchange="previewDocument(this)">
                        <button class="btn btn-outline-secondary" type="button" onclick="clearDocumentPreview()">
                            <i class="fas fa-times"></i> Effacer
                        </button>
                    </div>
                    
                    <!-- Zone de prévisualisation de document -->
                    <div id="document-preview-container" class="mt-3" style="display: none;">
                        <div class="card border-primary">
                            <div class="card-header bg-primary text-white">
                                <i class="fas fa-file-alt"></i> Document sélectionné
                            </div>
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-2 text-center">
                                        <i id="document-icon" class="fas fa-file-pdf fa-3x text-danger"></i>
                                    </div>
                                    <div class="col-md-10">
                                        <h6><i class="fas fa-info-circle text-info"></i> Informations du fichier</h6>
                                        <p><strong>Nom:</strong> <span id="document-name"></span></p>
                                        <p><strong>Taille:</strong> <span id="document-size"></span></p>
                                        <p><strong>Type:</strong> <span id="document-type"></span></p>
                                        <div class="alert alert-success">
                                            <i class="fas fa-check-circle"></i> Document prêt pour téléchargement!
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @if(isset($article) && $article->document_path)
                        <div class="mt-2">
                            <div class="alert alert-info">
                                <i class="fas fa-file-alt"></i> Document actuel : {{ $article->file_original_name ?? 'Document existant' }}
                                <a href="{{ asset('storage/' . $article->document_path) }}" target="_blank" class="btn btn-sm btn-outline-primary ms-2">
                                    <i class="fas fa-external-link-alt"></i> Voir
                                </a>
                            </div>
                        </div>
                    @endif
                    
                    <small class="form-text text-muted">
                        <i class="fas fa-info-circle"></i> Formats acceptés : PDF, DOC, DOCX, TXT, XLS, XLSX, PPT, PPTX (max 10MB)
                    </small>
                    @error('document')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="is_premium" name="is_premium"
                           value="1" {{ old('is_premium', $article->is_premium ?? false) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_premium">Article Premium (nécessite un abonnement)</label>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="is_published" name="is_published"
                           value="1" {{ old('is_published', $article->is_published ?? false) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_published">Publier l'article</label>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary">
                        {{ isset($article) ? 'Mettre à jour' : 'Créer' }}
                    </button>
                    <a href="{{ route('admin.articles.index') }}" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js"></script>
<script>
    // Configuration TinyMCE
    tinymce.init({
        selector: '#contenu',
        height: 500,
        plugins: [
            'advlist autolink lists link image charmap print preview anchor',
            'searchreplace visualblocks code fullscreen',
            'insertdatetime media table paste code help wordcount'
        ],
        toolbar: 'undo redo | formatselect | bold italic backcolor | \
                alignleft aligncenter alignright alignjustify | \
                bullist numlist outdent indent | removeformat | help'
    });

    // Fonction de prévisualisation d'image
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
                previewContainer.style.display = 'block';
                
                // Animation d'apparition
                previewContainer.style.opacity = '0';
                setTimeout(() => {
                    previewContainer.style.transition = 'opacity 0.5s ease';
                    previewContainer.style.opacity = '1';
                }, 100);
            };
            
            reader.readAsDataURL(file);
        } else {
            clearImagePreview();
            if (file) {
                alert('⚠️ Veuillez sélectionner un fichier image valide (JPG, PNG, GIF, WEBP)');
                input.value = '';
            }
        }
    }

    // Fonction de prévisualisation de document
    function previewDocument(input) {
        const file = input.files[0];
        const previewContainer = document.getElementById('document-preview-container');
        const documentName = document.getElementById('document-name');
        const documentSize = document.getElementById('document-size');
        const documentType = document.getElementById('document-type');
        const documentIcon = document.getElementById('document-icon');

        if (file) {
            // Déterminer l'icône selon le type de fichier
            const extension = file.name.split('.').pop().toLowerCase();
            const iconMap = {
                'pdf': { icon: 'fas fa-file-pdf', color: 'text-danger' },
                'doc': { icon: 'fas fa-file-word', color: 'text-primary' },
                'docx': { icon: 'fas fa-file-word', color: 'text-primary' },
                'xls': { icon: 'fas fa-file-excel', color: 'text-success' },
                'xlsx': { icon: 'fas fa-file-excel', color: 'text-success' },
                'ppt': { icon: 'fas fa-file-powerpoint', color: 'text-warning' },
                'pptx': { icon: 'fas fa-file-powerpoint', color: 'text-warning' },
                'txt': { icon: 'fas fa-file-alt', color: 'text-secondary' }
            };

            const iconInfo = iconMap[extension] || { icon: 'fas fa-file', color: 'text-muted' };
            
            documentIcon.className = `${iconInfo.icon} fa-3x ${iconInfo.color}`;
            documentName.textContent = file.name;
            documentSize.textContent = formatFileSize(file.size);
            documentType.textContent = file.type || 'Type inconnu';
            
            previewContainer.style.display = 'block';
            
            // Animation d'apparition
            previewContainer.style.opacity = '0';
            setTimeout(() => {
                previewContainer.style.transition = 'opacity 0.5s ease';
                previewContainer.style.opacity = '1';
            }, 100);
        } else {
            clearDocumentPreview();
        }
    }

    // Fonction pour effacer la prévisualisation d'image
    function clearImagePreview() {
        const previewContainer = document.getElementById('image-preview-container');
        const imageInput = document.getElementById('image');
        
        previewContainer.style.display = 'none';
        imageInput.value = '';
    }

    // Fonction pour effacer la prévisualisation de document
    function clearDocumentPreview() {
        const previewContainer = document.getElementById('document-preview-container');
        const documentInput = document.getElementById('document');
        
        previewContainer.style.display = 'none';
        documentInput.value = '';
    }

    // Fonction utilitaire pour formater la taille des fichiers
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    // Validation en temps réel
    document.addEventListener('DOMContentLoaded', function() {
        // Validation de l'image
        const imageInput = document.getElementById('image');
        if (imageInput) {
            imageInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file && file.size > 2048000) { // 2MB
                    alert('⚠️ La taille de l\'image ne doit pas dépasser 2MB');
                    clearImagePreview();
                    return;
                }
            });
        }

        // Validation du document
        const documentInput = document.getElementById('document');
        if (documentInput) {
            documentInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file && file.size > 10485760) { // 10MB
                    alert('⚠️ La taille du document ne doit pas dépasser 10MB');
                    clearDocumentPreview();
                    return;
                }
            });
        }
    });
</script>
@endpush
@endsection
