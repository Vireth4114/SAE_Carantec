@extends('template')

@section('content')
<h1>Fiche de sécurité</h1>
    @php
        setlocale(LC_TIME, ['fr', 'fra', 'fr_FR']);
        $timestamp = strtotime(date_Format($dives->DIV_DATE, 'l j F Y'));
        $date = strftime('%A %d %B %Y', $timestamp);
        $startTimestamp = strtotime($period->PER_START_TIME);
        $startTime = strftime('%H', $startTimestamp);
        $endTimestamp = strtotime($period->PER_END_TIME);
        $endTime = strftime('%H', $endTimestamp);
    @endphp

<table id="firstTable">
    <thead>

    </thead>
    <tr>
        <td class="firstRow">Date</td>
        <td class="secondRow">{{$date}} / {{$startTime.'h - '.$endTime.'h'}}</td>
    </tr>
    <tr>
        <td class="firstRow">Directeur de plongée</td>
        <td class="secondRow">{{$lead->MEM_NAME.' '.$lead->MEM_SURNAME}}</td>
    </tr>
    <tr>
        <td class="firstRow">Site de plongée</td>
        <td class="secondRow">{{$site->SIT_NAME}}</td>
    </tr>
    <tr>
        <td class="firstRow">Effectif</td>
        <td class="secondRow">{{$registered->REGISTERED}}</td>
    </tr>
</table>

<table id="secondTable">
    <tr>
        <td class="firstRow">Embarcation</td>
        <td class="secondRow">{{$boat->BOA_NAME}}</td>
    </tr>
    <tr>
        <td class="firstRow">Sécurité de surface</td>
        <td class="secondRow">{{$secure->MEM_NAME.' '.$secure->MEM_SURNAME}}</td>
    </tr>
    <tr>
        <td class="firstRow">Pilote</td>
        <td class="secondRow">{{$pilote->MEM_NAME.' '.$pilote->MEM_SURNAME}}</td>
    </tr>
    <tr>
        <td class="firstRow">Observation</td>
        <td class="secondRow" rowspan="3"></td>
    </tr>
</table>


@endsection
