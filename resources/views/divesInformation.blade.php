@extends("template")

@section("content")
    @php
        $date = date_Format(DateTime::createFromFormat('Y-m-d',$dives[0]->DIV_DATE), 'l j F Y');
        $heureStart = date_Format(DateTime::createFromFormat('H:i:s',$dives[0]->PER_START_TIME), 'G');
        $heureFin = date_Format(DateTime::createFromFormat('H:i:s',$dives[0]->PER_END_TIME), 'G');
    @endphp
    <p>Plongée du {{$date}} de {{$heureStart}}h à {{$heureFin}}h.</p>
    <p>Niveau de la plongée: {{$dives[0]->PRE_LABEL}}</p>
    <p>Site de {{$dives[0]->SIT_NAME}} ({{$dives[0]->SIT_DESCRIPTION}})</p>
    <p>Directeur de plongée : {{$dives[0]->MEM_NAME}} {{$dives[0]->MEM_SURNAME}}</p>
    <p>Sécurité surface : {{$dives_secur[0]->MEM_NAME}} {{$dives_secur[0]->MEM_SURNAME}}</p>
    <p>Pilote : {{$dives_pilot[0]->MEM_NAME}} {{$dives_pilot[0]->MEM_SURNAME}}</p>
    <p>Nom du bateau: {{$dives[0]->BOA_NAME}}</p>

    <div>
        <h3>Liste des Membres Inscrit</h3>
        @foreach($dives_register as $dive)
            <p>{{$dive->MEM_NAME}} {{$dive->MEM_SURNAME}}</p>
        @endforeach
    </div>
@endsection









