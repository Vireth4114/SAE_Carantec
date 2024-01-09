@extends("template")
@php
    use Carbon\Carbon;
@endphp

@section("content")
    @foreach($months as $month)
        <h2>{{ $month->mois_mot }}</h2>

        @foreach($dives[$month->mois_mot] as $dive)
            @php
                //$newDate = new Carbon($dive->DIV_DATE);
                $date = Carbon::parse($dive->DIV_DATE)->locale('fr_FR')->translatedFormat('l j F Y');
                $heureStart = date_Format(DateTime::createFromFormat('H:i:s',$dive->PER_START_TIME), 'G');
                $heureFin = date_Format(DateTime::createFromFormat('H:i:s',$dive->PER_END_TIME), 'G');
            @endphp
            <form>
            <p>
                <input  type='checkbox'>
                {{ $date }}
                de {{ $heureStart }}h à {{ $heureFin }} h
                Site prevu : {{ $dive->SIT_NAME }}
                ( {{ $dive->SIT_DESCRIPTION }} )
            </p>
            </form>
        @endforeach
    @endforeach
@endsection
