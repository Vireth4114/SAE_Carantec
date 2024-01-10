@extends("template")

@section("content")
    <h1>Mise à jour de {{ $site->SIT_NAME }}</h1>
    @if(!empty(session('errors')))
        @foreach (session('errors')->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    @endif
    <form action="{{ route('siteUpdateForm', ['siteId' => $site->SIT_NUM_SITE]) }}" method="POST">
        @csrf
        @method("patch")
        <label for="sit_name">Nom</label>
        <input type="text" id="sit_name" name="sit_name" size="30" value="{{ $site->SIT_NAME }}" />
        <label for="sit_coord">Coordonnées</label>
        <input type="text" id="sit_coord" name="sit_coord" size="30" value="{{ $site->SIT_COORD }}" />
        <label for="sit_depth">Profondeur</label>
        <input type="number" id="sit_depth" name="sit_depth" size="30" value="{{ $site->SIT_DEPTH }}" />
        <label for="sit_description">Description</label>
        <input type="textarea" id="sit_description" name="sit_description" size="30" value="{{ $site->SIT_DESCRIPTION }}" />
        <input type="submit" value="Mettre à jour" />
    </form>
    <a href="{{ route('managerPanel') }}">Retour au panel d'administration</a>
@endsection