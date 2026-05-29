@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    @php($formTitle = 'Modifier l\'article')
    @include('admin.articles.form')
</div>
@endsection
