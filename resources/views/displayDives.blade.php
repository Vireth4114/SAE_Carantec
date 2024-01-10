@extends("template")

@section("content")
@php $id=0; @endphp
    @foreach($months as $month)
        <h2>{{ $month->mois_mot }}</h2>
        
        @foreach($dives[$month->mois_mot] as $dive)
            @php
                $date = date_Format(DateTime::createFromFormat('Y-m-d',$dive->DIV_DATE), 'l j F Y');
                $heureStart = date_Format(DateTime::createFromFormat('H:i:s',$dive->PER_START_TIME), 'G');
                $heureFin = date_Format(DateTime::createFromFormat('H:i:s',$dive->PER_END_TIME), 'G');
            @endphp
            <form>
            <p>
                <a href="{{route('dives_informations',$dive->DIV_NUM_DIVE)}}">
                <input  type='checkbox'>
                {{ $date }}
                de {{ $heureStart }}h à {{ $heureFin }}h
                Site prevu : {{ $dive->SIT_NAME }}
                ( {{ $dive->SIT_DESCRIPTION }} )
                Niveau : {{$dive->PRE_LABEL}}
                </a>
                <i class="fa-solid fa-magnifying-glass"></i>
            </p>
            </form>
        @endforeach
    @endforeach
@endsection
