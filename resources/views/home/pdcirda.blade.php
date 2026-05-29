@extends('home.base')

@section('title', 'Pdci-rda')

@section('content')

<div style="
    margin: 0;
    padding: 0;
    overflow: hidden;
">
    <img src="{{ asset('image/pdci.jpg') }}" alt="PDCI-RDA" style="
        width: 100px;
        height: 100px;
        object-fit: cover;
        display: block;
    ">
</div>

@endsection
