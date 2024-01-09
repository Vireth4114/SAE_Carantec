@extends('template')

@section('content')
    <h1 class="center-title">Bienvenue sur notre site</h1>
    @if(!Auth::check())
        <h1 class="center-title">Vous n'êtes pas connecté</h1>
    @endif
@endsection
