@extends('template')

@section("content")
@php
    use Carbon\Carbon;
@endphp

<h3>Ajouter des membres pour la plongée n°{{$dive['DIV_NUM_DIVE']}} du {{Carbon::parse($dive['DIV_DATE'])->locale('fr_FR')->translatedFormat('l j F Y')}}</h3>
<a href="{{ route('diveInformation', $dive['DIV_NUM_DIVE'] ) }}">Revenir aux informations de la plongée</a>
@if ($directorRegistered)
    <p>Vous êtes inscrit à cette plongée. Voulez-vous vous désinscrire de la plongée : </p>
    <form action="{{ route('removeDirectorFromDiveForm') }}" method="POST">
        @csrf
        @method('post')
        <input type="hidden" name="numMember" value="{{ $dive['DIV_NUM_MEMBER_LEAD'] }}">
        <input type="hidden" name="numDive" value="{{ $dive['DIV_NUM_DIVE'] }}">
        <button type="submit">se désinscrire</button>
    </form>
@else
    <p>Vous n'êtes pas inscrit à cette plongée. Voulez-vous vous inscrire à la plongée : </p>
    <form action="{{ route('addMemberToDiveForm') }}" method="POST">
        @csrf
        @method('post')
        <input type="hidden" name="numMember" value="{{ $dive['DIV_NUM_MEMBER_LEAD'] }}">
        <input type="hidden" name="numDive" value="{{ $dive['DIV_NUM_DIVE'] }}">
        <button type="submit">S'inscrire</button>
    </form>
@endif
@if ($maxReached)
    <p class="userError">Le nombre d'inscrit maximum a été atteint</p>
@endif
<table>
    <thead>
        <tr>
            <th>Numéro de licence</th>
            <th>Prénom</th>
            <th>Nom</th>
            <th>Niveau</th>
            <th>Ajouter</th>
        </tr>
    </thead>
    <tbody>
        @php
            $increment = 0;
        @endphp
        @foreach($members as $member)
            <tr>
                <th>{{$member->MEM_NUM_LICENCE}}</th>
                <th>{{$member->MEM_NAME}}</th>
                <th>{{$member->MEM_SURNAME}}</th>
                <th>{{$levels[$increment++]}}
                <th>
                    <form action="{{ route('addMemberToDiveForm') }}" method="POST">
                        @csrf
                        @method('post')
                        <input type="hidden" name="numMember" value="{{ $member->MEM_NUM_MEMBER }}">
                        <input type="hidden" name="numDive" value="{{ $dive['DIV_NUM_DIVE'] }}">
                        @if ($maxReached)
                            <button disabled type="submit">ajouter</button>
                        @else
                            <button type="submit">ajouter</button>
                        @endif
                    </form>
                </th>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection