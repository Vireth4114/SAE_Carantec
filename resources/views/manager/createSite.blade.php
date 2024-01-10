@extends("template")

@section("content")
    <h1>Création d'un site</h1>
    @if(!empty(session('errors')))
        @foreach (session('errors')->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    @endif
    <form action="{{ route('siteCreateForm') }}" method="POST">
        @csrf
        @method("post")
        <label for="sit_name">Nom</label>
        <input type="text" id="sit_name" name="sit_name" size="30" />
        <label for="sit_coord">Coordonnées</label>
        <input type="text" id="sit_coord" name="sit_coord" size="30" />
        <label for="sit_depth">Profondeur</label>
        <input type="number" id="sit_depth" name="sit_depth" size="30" />
        <label for="sit_description">Description</label>
        <input type="textarea" id="sit_description" name="sit_description" size="30" />
        <input type="submit" value="Créer le site" />
    </form>
    <a href="{{ route('managerPanel') }}">Retour au panel d'administration</a>
@endsection