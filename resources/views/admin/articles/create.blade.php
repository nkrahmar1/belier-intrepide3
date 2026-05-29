@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    @php($formTitle = 'Créer un article')
    @include('admin.articles.form')
</div>
@endsection
