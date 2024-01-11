@extends('template')

@section('content')
@php
    use Carbon\Carbon;
@endphp

<h2>Plongée n°{{$dive['DIV_NUM_DIVE']}} du {{Carbon::parse($dive['DIV_DATE'])->locale('fr_FR')->translatedFormat('l j F Y')." (".$period.")"}}</h2>
<a href="{{route('myDirectorDives')}}"><p>Retour aux plongées</p></a>
<div>
    <p> Site : {{ $site }}</p>
    <p>Directeur de plongée : {{$lead}}</p>
    <p>Sécurité de surface : {{$security}}</p>
    <p>Pilote : {{$pilot}}</p>
</div>

<div>
    @if(!$updatable) 
        <a href={{ route('diveModify', $dive['DIV_NUM_DIVE'] ) }}><button>Modifier la plongée</button></a>
    @else
        <p>Vous ne pourrez modifier la plongée que le jour où elle aura lieu.</p>
        <button disabled>Modifier la plongée</button>
    @endif
</div>
<div>
    <h3>Membres inscrits ({{$nbMembers}}/{{$max_divers}}) :</h3>
    <table>
        <thead>
            <tr>
                <th>Numéro de licence</th>
                <th>Prénom</th>
                <th>Nom</th>
                <th>Niveau</th>
                <th>Supprimer</th>
            </tr>
        </thead>
        <tbody>
            @php
                $increment =0;
            @endphp
            @foreach($members as $member)
                    <tr>
                        <th>{{$member->MEM_NUM_LICENCE}}</th>
                        <th>{{$member->MEM_NAME}}</th>
                        <th>{{$member->MEM_SURNAME}}</th>
                        <th>{{$levels[$increment++]}}</th>
                        <th>
                            <form action="{{ route('removeMemberFromDiveForm') }}" method="POST">
                                @csrf
                                @method('post')
                                <input type="hidden" name="numMember" value="{{ $member->MEM_NUM_MEMBER }}">
                                <input type="hidden" name="numDive" value="{{ $dive['DIV_NUM_DIVE'] }}">
                                <button type="submit">supprimer </button>
                            </form>
                        </th>
                    </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div>
    <p>Ajouter un adhérent ou vous inscrire : </p>
    <a href="{{ route('addMember', $dive['DIV_NUM_DIVE'] ) }}"><img id="logoAddMember" src="{{URL::asset('images/plus.png')}}" alt="Logo plus"></a>
</div>
@endsection