@extends('home.base')

@section('content')
<div class="container">
    <h1>Créer un nouvel article</h1>

    <form method="POST" action="{{ route('articles.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="titre" class="form-label">Titre</label>
            <input type="text" class="form-control" id="titre" name="titre" required>
        </div>

        <div class="mb-3">
            <label for="contenu" class="form-label">Contenu</label>
            <textarea class="form-control" id="contenu" name="contenu" rows="6" required></textarea>
        </div>

        <div class="mb-3">
            <label for="category_id" class="form-label">Catégorie</label>
            <select class="form-control" id="category_id" name="category_id" required>
                <option value="">Choisir une catégorie</option>
                @foreach(\App\Models\Category::all() as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Image</label>
            <input class="form-control" type="file" id="image" name="image">
        </div>

        <button type="submit" class="btn btn-primary">Publier</button>
    </form>
</div>
@endsection
