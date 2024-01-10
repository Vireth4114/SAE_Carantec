@extends('template')

@section('content')
<h1>Formulaire pour la fiche de sécurité</h1>

<div>
    @php
        setlocale(LC_TIME, ['fr', 'fra', 'fr_FR']);
        $timestamp = strtotime(date_Format($dives->DIV_DATE, 'l j F Y'));
        $date = strftime('%A %d %B %Y', $timestamp);
    @endphp
    <p>Date :  {{$date}} </p>
</div>
<div>
    <p>Période : {{$period->PER_LABEL}}</p>
</div>
<div>
    <p>Site de plongée : {{$site->SIT_NAME}} </p>
</div>
<div>
    <p>Embarcation : {{$boat->BOA_NAME}}</p>
</div>
<div>
    <p>Effectif : {{$registered->REGISTERED}}</p>
</div>
<div>
    <p>Directeur de plongée : {{$lead->MEM_NAME.' '.$lead->MEM_SURNAME}}</p>
</div>
<div>
    <p>Sécurité de surface : {{$secure->MEM_NAME.' '.$secure->MEM_SURNAME}}</p>
</div>
<div>
    <p>Pilote : {{$pilote->MEM_NAME.' '.$pilote->MEM_SURNAME}}</p>
</div>
<div>
    <p>Observation</p>
    <input type="text" name="observation"></i>
</div>
@endsection
