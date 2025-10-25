@extends('layouts.admin')

@section('title', 'Abonnements')

@section('content')
    @include('admin.subscriptions-content', ['subscriptions' => $subscriptions])
@endsection
