@extends('home.base')

@section('title', 'Africa')

@section('content')
<style>
    .article-title {
        color: black; /* Définit la couleur du titre en noir */
    }
</style>

<div class="container">

</div>

<div class="container">
    <h1 class="my-4">Actualités d'Afrique</h1>

    {{-- Bouton pour créer un nouvel article - Visible seulement pour les administrateurs --}}
    @auth
        @if(auth()->user()->is_admin || auth()->user()->role === 'admin')
            <a href="{{ route('articles.create') }}" class="btn btn-success mb-4">+ Nouvel article</a>
        @endif
    @endauth

    <div class="row">
        @foreach ($articles as $article)
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <img src="{{ asset('storage/' . $article->image) }}" class="card-img-top" alt="{{ $article->titre }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $article->titre }}</h5>
                        <p class="card-text">{{ Str::limit($article->contenu, 120) }}</p>
                        <a href="{{ route('articles.show', $article->id) }}" class="btn btn-primary">Lire la suite</a>
                    </div>
                    <div class="card-footer text-muted">
                        Publié le {{ $article->created_at->format('d/m/Y') }}
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<h1 class="article-title">La grâce accordée à l'ancien président guinéen soulève de graves préoccupations</h1>

    <div class="row">
        <div class="col-md-8">
            <p>
                La décision d'accorder une grâce présidentielle à l'ancien président guinéen Moussa Dadis Camara,
                condamné à 20 ans de prison en lien avec le massacre du stade en 2009, soulève de graves
                préoccupations quant au respect, par les autorités de transition, du droit à un procès équitable
                et de l'état de droit, et méconnaît le droit des victimes à des recours effectifs. Cette décision
                devrait être annulée.
            </p>
            <p>
                Cette grâce sape les procédures judiciaires nationales et contrevient aux principes internationaux
                des droits de l'homme, qui insistent sur le droit des victimes à des recours effectifs ainsi que
                sur l'obligation des États à protéger et garantir les droits humains et de lutter contre l'impunité.
            </p>
            <p>
                M. Camara a été condamné l'année dernière pour crimes contre l'humanité en raison du massacre
                perpétré par les forces de sécurité guinéennes, qui avaient tué plus de 150 personnes lors d'un
                rassemblement de l'opposition dans un stade de la capitale, Conakry, en septembre 2009.
            </p>
        </div>

        <div class="col-md-4">
            <img src="{{ asset('storage/images/image_de_l_article.jpg') }}" alt="Image de l'article" class="img-fluid">
        </div>
    </div>

@endsection
