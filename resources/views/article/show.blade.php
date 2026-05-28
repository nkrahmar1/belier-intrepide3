@extends('home.base')

@section('title', $article->titre)

@section('content')
    <h1>{{ $article->titre }}</h1>
    <p>{{ $article->contenu }}</p>

    @if(isset($canDownload) && $canDownload)
        <a href="{{ route('articles.download', $article->id) }}" class="btn btn-success">📥 Télécharger l'article</a>
    @else
        <div class="alert alert-warning">
            🔒 Cet article est réservé aux abonnés.
            <a href="{{ route('subscriptions.index') }}" class="btn btn-primary">S’abonner</a>
        </div>
    @endif
@endsection
