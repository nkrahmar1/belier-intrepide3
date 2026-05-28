<!-- Formulaire de création rapide d'article -->
<div class="row">
    <div class="col-md-8">
        <!-- Informations principales -->
        <div class="mb-3">
            <label for="quick_titre" class="form-label">Titre de l'article *</label>
            <input type="text" 
                   class="form-control" 
                   id="quick_titre" 
                   name="titre" 
                   required
                   placeholder="Entrez le titre de votre article">
        </div>

        <div class="mb-3">
            <label for="quick_extrait" class="form-label">Extrait</label>
            <textarea class="form-control" 
                      id="quick_extrait" 
                      name="extrait" 
                      rows="2"
                      placeholder="Résumé court de l'article (optionnel)"></textarea>
        </div>

        <div class="mb-3">
            <label for="quick_contenu" class="form-label">Contenu *</label>
            <textarea class="form-control" 
                      id="quick_contenu" 
                      name="contenu" 
                      rows="6" 
                      required
                      placeholder="Rédigez le contenu de votre article ici..."></textarea>
        </div>
    </div>
    
    <div class="col-md-4">
        <!-- Options et fichiers -->
        <div class="mb-3">
            <label for="quick_category_id" class="form-label">Catégorie *</label>
            <select class="form-select" id="quick_category_id" name="category_id" required>
                <option value="">Choisir une catégorie</option>
                @foreach(\App\Models\Category::all() as $category)
                    <option value="{{ $category->id }}">{{ $category->nom }}</option>
                @endforeach
            </select>
        </div>

        <!-- Upload d'image -->
        <div class="mb-3">
            <label for="quick_image" class="form-label">Image de l'article</label>
            <input type="file" 
                   class="form-control" 
                   id="quick_image" 
                   name="image" 
                   accept="image/*"
                   onchange="previewImage(this)">
            <div id="imagePreview" class="mt-2"></div>
        </div>

        <!-- Upload de document -->
        <div class="mb-3">
            <label for="quick_document" class="form-label">Document (PDF, DOC, etc.)</label>
            <input type="file" 
                   class="form-control" 
                   id="quick_document" 
                   name="document"
                   accept=".pdf,.doc,.docx,.txt,.zip">
            <small class="form-text text-muted">Formats: PDF, DOC, DOCX, TXT, ZIP</small>
        </div>

        <!-- Options de publication -->
        <div class="card bg-light">
            <div class="card-body">
                <h6 class="card-title">Options de publication</h6>
                
                <div class="form-check mb-2">
                    <input class="form-check-input" 
                           type="checkbox" 
                           id="quick_is_published" 
                           name="is_published" 
                           value="1">
                    <label class="form-check-label" for="quick_is_published">
                        Publier immédiatement
                    </label>
                </div>

                <div class="form-check mb-2">
                    <input class="form-check-input" 
                           type="checkbox" 
                           id="quick_is_premium" 
                           name="is_premium" 
                           value="1">
                    <label class="form-check-label" for="quick_is_premium">
                        Article Premium (abonnement requis)
                    </label>
                </div>

                <div class="mb-2">
                    <label for="quick_published_at" class="form-label">Date de publication</label>
                    <input type="datetime-local" 
                           class="form-control form-control-sm" 
                           id="quick_published_at" 
                           name="published_at"
                           value="{{ now()->format('Y-m-d\TH:i') }}">
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Barre de progression upload -->
<div id="uploadProgress" class="progress mt-3" style="display: none;">
    <div class="progress-bar progress-bar-striped progress-bar-animated" 
         role="progressbar" 
         style="width: 0%"></div>
</div>

<script>
function previewImage(input) {
    const preview = document.getElementById('imagePreview');
    preview.innerHTML = '';
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.innerHTML = `
                <div class="position-relative">
                    <img src="${e.target.result}" 
                         class="img-fluid rounded" 
                         style="max-height: 150px; width: 100%; object-fit: cover;">
                    <button type="button" 
                            class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1"
                            onclick="clearImagePreview()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

function clearImagePreview() {
    document.getElementById('quick_image').value = '';
    document.getElementById('imagePreview').innerHTML = '';
}

// Auto-génération du slug depuis le titre
document.getElementById('quick_titre').addEventListener('input', function() {
    // Le slug sera généré automatiquement côté serveur
});

// Validation en temps réel
document.getElementById('quick_titre').addEventListener('blur', function() {
    if (this.value.length < 5) {
        this.classList.add('is-invalid');
    } else {
        this.classList.remove('is-invalid');
        this.classList.add('is-valid');
    }
});

document.getElementById('quick_contenu').addEventListener('blur', function() {
    if (this.value.length < 20) {
        this.classList.add('is-invalid');
    } else {
        this.classList.remove('is-invalid');
        this.classList.add('is-valid');
    }
});
</script>
