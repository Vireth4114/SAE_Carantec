@extends('template')

@section('content')
@php
    use Carbon\Carbon;
@endphp

<h2>Plongée n°{{$dive['DIV_NUM_DIVE']}} du {{Carbon::parse($dive['DIV_DATE'])->locale('fr_FR')->translatedFormat('l j F Y')." (".$period.")"}}</h2>

<p> Site : {{ $site }}</p>
<p>Directeur de plongée : {{$lead}}</p>
<p>Sécurité de surface : {{$security}}</p>
<p>Pilote : {{$pilot}}</p>

<h3>Membres inscrits ({{$nbMembers}}/{{$max_divers}}) :</h3>
<table>
    <thead>
        <tr>
            <th>Numéro de licence</th>
            <th>Prénom</th>
            <th>Nom</th>
            <th>Supprimer</th>
        </tr>
    </thead>
    <tbody>
@foreach($members as $member)
        <tr>
            <th>{{$member->MEM_NUM_LICENCE}}</th>
            <th>{{$member->MEM_NAME}}</th>
            <th>{{$member->MEM_SURNAME}}</th>
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
<p>Ajouter un adhérent ou vous inscrire : </p>
<a href="{{ route('addMember', $dive['DIV_NUM_DIVE'] ) }}"><img id="logoAddMember" src="{{URL::asset('images/plus.png')}}" alt="Logo plus"></a>

@endsection