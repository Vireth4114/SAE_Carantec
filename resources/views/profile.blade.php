@extends('template')
@section('content')

@php
use app\Models\web\AcnMember;

$members = AcnMember::all();
// AcnMember::checkStatus();    //Need to be finished

@endphp
            @if($member->MEM_PRICING == 'enfant')
                <img src="{{URL::asset('images/babyDiver.avif')}}" alt="baby diver">
            @else
                <img src="{{URL::asset('images/adultDiver.avif')}}" alt="adult diver">
            @endif
            <p>Numéro d'adhérent : {{$member->MEM_NUM_MEMBER}}</p>
            <p>Numéro de licence : {{$member->MEM_NUM_LICENCE}}</p>
            <p>Nom : {{$member->MEM_NAME}}</p>
            <p>Prénom : {{$member->MEM_SURNAME}}</p>
            <p>Date de certificat : {{$member->MEM_DATE_CERTIF}}</p>
            <p>Type d'abonnement : {{$member->MEM_PRICING}}</p>
            <p>Nombre de plongée restante : {{$member->MEM_REMAINING_DIVES}}</p>
            <p>Statut : {{$member->MEM_STATUS}}</p>
            <a href={{route('profil_modification')}}>Modifier mes informations</a>
@endsection
