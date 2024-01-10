@extends('template')

@section("content")
@php
    use Carbon\Carbon;
@endphp

<h3>Ajouter des membres pour la plongée n°{{$dive['DIV_NUM_DIVE']}} du {{Carbon::parse($dive['DIV_DATE'])->locale('fr_FR')->translatedFormat('l j F Y')}}</h3>
<a href="{{ route('diveInformation', $dive['DIV_NUM_DIVE'] ) }}">Revenir aux informations de la plongée</a>
<table>
    <thead>
        <tr>
            <th>Numéro de licence</th>
            <th>Prénom</th>
            <th>Nom</th>
            <th>Ajouter</th>
        </tr>
    </thead>
    <tbody>
@foreach($members as $member)
        <tr>
            <th>{{$member->MEM_NUM_LICENCE}}</th>
            <th>{{$member->MEM_NAME}}</th>
            <th>{{$member->MEM_SURNAME}}</th>
            <th>
                <form action="{{ route('addMemberToDiveForm') }}" method="POST">
                    @csrf
                    @method('post')
                    <input type="hidden" name="numMember" value="{{ $member->MEM_NUM_MEMBER }}">
                    <input type="hidden" name="numDive" value="{{ $dive['DIV_NUM_DIVE'] }}">
                    <button type="submit">ajouter</button>
                </form>
            </th>
        </tr>
@endforeach
    </tbody>
</table>
@endsection