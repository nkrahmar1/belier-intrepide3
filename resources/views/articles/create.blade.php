@extends('home.base')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm border-0">
                <div class="card-body p-5">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start mb-4 gap-3">
                        <div>
                            <h1 class="h3 mb-2">Publier un article</h1>
                            <p class="text-muted mb-0">Remplissez ce formulaire pour publier votre article dans la bonne catégorie.</p>
                        </div>
                        <a href="{{ route('articles.index') }}" class="btn btn-outline-secondary">Retour aux articles</a>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>Veuillez corriger les erreurs suivantes :</strong>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('articles.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="titre" class="form-label">Titre <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="titre" name="titre" value="{{ old('titre') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="category_id" class="form-label">Catégorie <span class="text-danger">*</span></label>
                                <select class="form-select" id="category_id" name="category_id" required>
                                    <option value="">Sélectionnez une catégorie</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->nom ?? $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row g-3 mt-3">
                            <div class="col-md-12">
                                <label for="extrait" class="form-label">Extrait</label>
                                <textarea class="form-control" id="extrait" name="extrait" rows="3">{{ old('extrait') }}</textarea>
                                <div class="form-text">Résumé court de l'article : utile pour l'aperçu dans les listes.</div>
                            </div>
                        </div>

                        <div class="row g-3 mt-3">
                            <div class="col-md-12">
                                <label for="contenu" class="form-label">Contenu <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="contenu" name="contenu" rows="10" required>{{ old('contenu') }}</textarea>
                            </div>
                        </div>

                        <div class="row g-3 mt-3">
                            <div class="col-md-6">
                                <label for="image" class="form-label">Image de l'article</label>
                                <input class="form-control" type="file" id="image" name="image" accept="image/jpeg,image/png,image/webp,image/gif">
                                <div class="form-text">Formats autorisés : JPG, PNG, WEBP, GIF. Taille max : 2MB.</div>
                            </div>
                            <div class="col-md-6">
                                <label for="document" class="form-label">Document téléchargeable</label>
                                <input class="form-control" type="file" id="document" name="document" accept=".pdf,.doc,.docx,.txt,.xls,.xlsx,.ppt,.pptx">
                                <div class="form-text">Ajouter un PDF ou un document si nécessaire.</div>
                            </div>
                        </div>

                        <div class="row g-3 mt-3">
                            <div class="col-md-4">
                                <label for="article_type" class="form-label">Type d'article</label>
                                <select id="article_type" name="article_type" class="form-select">
                                    <option value="">Type par défaut</option>
                                    <option value="breve" {{ old('article_type') == 'breve' ? 'selected' : '' }}>Brève</option>
                                    <option value="communique" {{ old('article_type') == 'communique' ? 'selected' : '' }}>Communiqué</option>
                                    <option value="analyse" {{ old('article_type') == 'analyse' ? 'selected' : '' }}>Analyse</option>
                                    <option value="enquete" {{ old('article_type') == 'enquete' ? 'selected' : '' }}>Enquête</option>
                                    <option value="interview" {{ old('article_type') == 'interview' ? 'selected' : '' }}>Interview</option>
                                    <option value="tutoriel" {{ old('article_type') == 'tutoriel' ? 'selected' : '' }}>Tutoriel</option>
                                    <option value="explicatif" {{ old('article_type') == 'explicatif' ? 'selected' : '' }}>Explicatif</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="content_quality" class="form-label">Qualité du contenu</label>
                                <input type="number" class="form-control" id="content_quality" name="content_quality" min="0" max="100" value="{{ old('content_quality', 50) }}">
                                <div class="form-text">0 = court, 100 = analyse complète.</div>
                            </div>
                            <div class="col-md-4"></div>
                        </div>

                        <div class="row g-3 mt-3">
                            <div class="col-md-6">
                                <label for="unit_price" class="form-label">Prix unitaire</label>
                                <input type="number" step="0.01" min="0" class="form-control" id="unit_price" name="unit_price" value="{{ old('unit_price') }}">
                                <div class="form-text">Laisser vide pour ne pas vendre l'article à l'unité.</div>
                            </div>
                            <div class="col-md-6">
                                <label for="free_download_limit" class="form-label">Téléchargements gratuits</label>
                                <input type="number" min="0" class="form-control" id="free_download_limit" name="free_download_limit" value="{{ old('free_download_limit') }}">
                                <div class="form-text">0 = illimité pour les contenus gratuits.</div>
                            </div>
                        </div>

                        <div class="row g-3 mt-4">
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_featured">Mettre en avant</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_premium" name="is_premium" value="1" {{ old('is_premium') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_premium">Article premium</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_published" name="is_published" value="1" {{ old('is_published') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_published">Publier l'article</label>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center gap-3 mt-4 flex-column flex-sm-row">
                            <a href="{{ route('articles.index') }}" class="btn btn-secondary">Annuler</a>
                            <button type="submit" class="btn btn-primary">Publier mon article</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
