@extends('template')
@section('content')

@php
use app\Models\web\AcnMember;

$members = AcnMember::all();
// AcnMember::checkStatus();    //Need to be finished

@endphp
<a type="button" class="btn btn-success" href={{route('member_registration')}}>Ajouter un adhérent</a>
<h3>Liste des adhérents :</h3>
    <table id='listMembers'>
        <tr>
            <th>Numéro d'adhérent</th>
            <th>Numéro de licence </th>
            <th>Nom </th>
            <th>Prénom </th>
            <th>Date de certification </th>
            <th>Type d'abonnement </th>
            <th>Nombre de plongée restante</th>
            <th>Statut</th>
        </tr>

        @foreach($members as $member)

            <tr>
                <td>{{$member->MEM_NUM_MEMBER}}</td>
                <td>{{$member->MEM_NUM_LICENCE}} </td>
                <td>{{$member->MEM_SURNAME}} </td>
                <td>{{$member->MEM_NAME}} </td>
                <td>{{$member->MEM_DATE_CERTIF}} </td>
                <td>{{$member->MEM_PRICING}} </td>
                <td>{{$member->MEM_REMAINING_DIVES}}</td>
                <td>@if($member->MEM_STATUS)
                        <span class="active_member">actif</span>
                    @else
                        <span class="inactive_member">inactif</span>
                    @endif
                </td>
                <td><a href={{route("member_modification",$member->MEM_NUM_MEMBER)}}>Modifier</a></td>
                <td><a href={{route("member_status",$member->MEM_NUM_MEMBER)}}>Changer son statut</a></td>
            </tr>

@endforeach
    </table>
@endsection
