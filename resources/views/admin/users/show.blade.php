@extends('layouts.admin')

@section('title', 'Détail utilisateur')

@section('content')
<h1>Détail de {{ $user->name }}</h1>

<ul>
    <li><strong>Nom :</strong> {{ $user->name }}</li>
    <li><strong>Email :</strong> {{ $user->email }}</li>
    <li><strong>Status :</strong> {{ ucfirst($user->status) }}</li>
    <li><strong>Inscrit le :</strong> {{ $user->created_at->format('d/m/Y') }}</li>
</ul>

<a href="{{ route('admin.users.edit', $user) }}">Modifier</a> |
<a href="{{ route('admin.users.index') }}">Retour à la liste</a>
@endsection
