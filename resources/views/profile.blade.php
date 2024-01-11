@extends('template')
@section('content')

@php
use app\Models\web\AcnMember;

$members = AcnMember::all();
// AcnMember::checkStatus();    //Need to be finished

@endphp
        <div id='profileCard'>
            @if($member->MEM_PRICING == 'enfant')
                <img class='profilePicture' src="{{URL::asset('images/babyDiver.avif')}}" alt="baby diver">
            @else
                <img class='profilePicture' src="{{URL::asset('images/adultDiver.avif')}}" alt="adult diver">
            @endif
            <div id='profileInfos'>
                <p>Licence N°{{$member->MEM_NUM_LICENCE}}</p>
                <p>{{$member->MEM_NAME}} {{$member->MEM_SURNAME}}</p>
                <p>Date de certificat : {{$member->MEM_DATE_CERTIF}}</p>
                <p>Type d'abonnement : {{$member->MEM_PRICING}}</p>
                <p>{{$member->MEM_REMAINING_DIVES}} plongées restantes</p>
            </div>
        </div>
@endsection
