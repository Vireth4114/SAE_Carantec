@extends('template')

@section('content')
    @php
        setlocale(LC_TIME, ['fr', 'fra', 'fr_FR']);
        $timestamp = strtotime(date_Format($dives->DIV_DATE, 'l j F Y'));
        $date = strftime('%A %d %B %Y', $timestamp);
        $startTimestamp = strtotime($period->PER_START_TIME);
        $startTime = strftime('%H', $startTimestamp);
        $endTimestamp = strtotime($period->PER_END_TIME);
        $endTime = strftime('%H', $endTimestamp);
        $immertionTimestamp = strtotime($palanquing->GRP_TIME_OF_IMMERSION);
        $immertionTime = strftime('%H:%M', $immertionTimestamp);
        $emertionTimestamp = strtotime($palanquing->GRP_TIME_OF_EMERSION);
        $emertionTime = strftime('%H:%M', $emertionTimestamp);
    @endphp
<div id="safetySheet">
    <table id="firstTable">
        <thead>
            <tr>
                <th colspan="4">Fiche de sécurité</th>
            </tr>
        </thead>
        <tr>
            <td colspan="1">Date</td>
            <td colspan="1">{{$date}} / {{$startTime.'h - '.$endTime.'h'}}</td>
            <td id="imageTD" colspan="2" rowspan="4"><img src="/images/logoSafetySheet.png" alt="carantec nautism" width="600px"></td>
        </tr>
        <tr>
            <td colspan="1">Directeur de plongée</td>
            <td colspan="1">{{$lead->MEM_NAME.' '.$lead->MEM_SURNAME}}</td>
        </tr>
        <tr>
            <td colspan="1">Site de plongée</td>
            <td colspan="1">{{$site->SIT_NAME}}</td>
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

    <table id="palanquing">
        <thead>
            <tr>
                <th colspan="6">Palanquée n° {{$palanquing->GRP_NUM_GROUPS}}</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Heure de départ</td>
                <td>{{$immertionTime}}</td>
                <td>Heure de retour</td>
                <td colspan="3">{{$emertionTime}}</td>
            </tr>
            <tr>
                <td>Temps Prévu</td>
                <td>{{$palanquing->GRP_EXPECTED_DURATION}} min</td>
                <td>Profondeur Prévu</td>
                <td colspan="3">{{$palanquing->GRP_EXPECTED_DEPTH}} m</td>
            </tr>
            <tr>
                <td>Temps Réalisé</td>
                <td>{{$palanquing->GRP_DIVING_TIME}} min</td>
                <td>Profondeur Réalisé</td>
                <td colspan="3">{{$palanquing->GRP_REACHED_DEPTH}} m</td>
            </tr>
            <tr>
                <td id="nameSurname" colspan="3">Nom Prénom</td>
                <td>Aptitudes</td>
                <td>Formation vers</td>
                <td>Fonction</td>
            </tr>
            @foreach ($members as $member)
            <tr>
                <td colspan="3">
                    {{$member->MEM_SURNAME.' '.$member->MEM_NAME}}
                </td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
