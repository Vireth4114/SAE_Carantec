@extends('template')


@php
    use Carbon\Carbon;
@endphp

<h3>Plongée n°{{$dive['DIV_NUM_DIVE']}} du {{Carbon::parse($dive['DIV_DATE'])->locale('fr_FR')->translatedFormat('l j F Y')." (".$period.")"}}</h3>

    @if (!is_null($dive['DIV_NUM_SITE']))
        <p> Site : {{ $site }}</p>
    @endif
    <p>Directeur de plongée : {{$selectedLead}}</p>
    <p>Sécurité de surface : {{$selectedSecurity}}</p>
    <p>Pilote : {{$selectedPilot}}</p>