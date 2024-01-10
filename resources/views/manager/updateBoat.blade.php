@extends("template")

@section("content")
    <h1>Mise à jour de {{ $boat->BOA_NAME }}</h1>
    @if(!empty(session('errors')))
        @foreach (session('errors')->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    @endif
    <form action="{{ route('boatUpdateForm', ['boatId' => $boat->BOA_NUM_BOAT]) }}" method="POST">
        @csrf
        @method("patch")
        <label for="boa_name">Nom</label>
        <input type="text" id="boa_name" name="boa_name" size="30" value="{{ $boat->BOA_NAME }}" />
        <label for="boa_capacity">Capacité</label>
        <input type="number" id="boa_capacity" name="boa_capacity" size="30" value="{{ $boat->BOA_CAPACITY }}" />
        <input type="submit" value="Mettre à jour" />
    </form>
    <a href="{{ route('managerPanel') }}">Retour au panel d'administration</a>
@endsection