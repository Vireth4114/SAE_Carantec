@extends('template')

@php
    use Carbon\Carbon;
@endphp

<h3>Ajouter des membres pour la plongée n°{{$dive['DIV_NUM_DIVE']}} du {{Carbon::parse($dive['DIV_DATE'])->locale('fr_FR')->translatedFormat('l j F Y')}}</h3>

<table>
    <thead>
        <tr>
            <th>Numéro de licence</th>
            <th>Prénom</th>
            <th>Nom</th>
            <th>Forfait</th>
            <th>Plongées restantes</th>
        </tr>
    </thead>
    <tbody>
@foreach($members as $member) 
        <tr>
            <th>{{$member['MEM_NUM_LICENCE']}}</th>
            <th>{{$member['MEM_NAME']}}</th>
            <th>{{$member['MEM_SURNAME']}}</th>
            <th>{{$member['MEM_PRICING']}}</th>
            <th>{{$member['MEM_REMAINING_DIVES']}}</th>
            <th><a href="https://www.codeur.com">ajouter</a></th>
        </tr>
@endforeach
    </tbody>
</table>