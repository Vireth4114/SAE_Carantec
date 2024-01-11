@extends('template')
@section('content')

@php
use app\Models\web\AcnMember;

$members = AcnMember::all();
// AcnMember::checkStatus();    //Need to be finished

@endphp
<a type="button" class="btn btn-success" href={{route('managerPanel')}}>Ajouter un adhérent</a>
<h3>Liste des adhérents :</h3>
    <table>
        <tr>
            <td>Numéro d'adhérent</td>
            <td>Numéro de licence </td>
            <td>Nom </td>
            <td>Prénom </td>
            <td>Date de certification </td>
            <td>Type d'abonnement </td>
            <td>Nombre de plongée restante</td>
            <td>Statut</td>
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
                        <p class="active_member">actif</p>
                    @else
                        <p class="inactive_member">inactif</p>
                    @endif
                </td>
                <td><a href={{route("member_modification",$member->MEM_NUM_MEMBER)}}>Modifier</a></td>
                <td><a href={{route("member_status",$member->MEM_NUM_MEMBER)}}>Changer son statut</a></td>
            </tr>

@endforeach
    </table>
@endsection
