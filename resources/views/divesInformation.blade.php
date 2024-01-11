@extends("template")

@section("content")
    @php
        use Carbon\Carbon;
        $date = Carbon::parse($dives->DIV_DATE)->locale('fr_FR')->translatedFormat('l j F Y');
        $heureStart = $period->PER_START_TIME->format('H');
        $heureFin = $period->PER_END_TIME->format('H');
    @endphp
    <p>Plongée du {{$date}} de {{$heureStart}}h à {{$heureFin}}h.</p>
    <p>Niveau de la plongée: {{$prerogative}}</p>
    <p>Site : {{$site}}</p>
    <p>Directeur de plongée : {{$dives_lead}}</p>
    <p>Sécurité surface : {{$dives_secur}}</p>
    <p>Pilote : {{$dives_pilot}}</p>
    <p>Nom du bateau : {{$boat}}</p>

    <div class=diveInfos>
        <h3>Liste des Membres Inscrit</h3>
        @foreach($dives_register as $member)
            <p>{{$member->MEM_NAME}} {{$member->MEM_SURNAME}}</p>
        @endforeach
    </div>
@endsection









