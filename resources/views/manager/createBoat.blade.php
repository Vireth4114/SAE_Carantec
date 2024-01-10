@extends("template")

@section("content")
    <h1>Création d'un bateau</h1>
    @if(!empty(session('errors')))
        @foreach (session('errors')->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    @endif
    <form action="{{ route('boatCreateForm') }}" method="POST">
        @csrf
        @method("post")
        <label for="boa_name">Nom</label>
        <input type="text" id="boa_name" name="boa_name" size="30" />
        <label for="boa_capacity">Capacité</label>
        <input type="number" id="boa_capacity" name="boa_capacity" size="30" />
        <input type="submit" value="Créer le bateau" />
    </form>
    <a href="{{ route('managerPanel') }}">Retour au panel d'administration</a>
@endsection