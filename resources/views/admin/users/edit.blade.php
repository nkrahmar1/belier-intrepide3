@extends('layouts.admin')

@section('title', 'Modifier utilisateur')

@section('content')
<h1>Modifier {{ $user->name }}</h1>

@if ($errors->any())
    <div>
        <ul>
            @foreach ($errors->all() as $error)
                <li style="color:red;">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.users.update', $user) }}" method="POST">
    @csrf
    @method('PUT')

    <label for="name">Nom</label>
    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required>

    <label for="email">Email</label>
    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required>

    <label for="status">Statut</label>
    <select name="status" id="status" required>
        <option value="active" {{ old('status', $user->status) === 'active' ? 'selected' : '' }}>Actif</option>
        <option value="inactive" {{ old('status', $user->status) === 'inactive' ? 'selected' : '' }}>Inactif</option>
        <option value="pending" {{ old('status', $user->status) === 'pending' ? 'selected' : '' }}>En attente</option>
    </select>

    <button type="submit">Enregistrer</button>
</form>

<a href="{{ route('admin.users.index') }}">Retour à la liste</a>
@endsection
