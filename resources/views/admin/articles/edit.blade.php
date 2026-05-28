@extends('layouts.admin')

@section('content')
    {{-- Reuse the create form for editing to keep UI consistent --}}
    @php($article = $article ?? null)
    @include('admin.articles.create')
@endsection
