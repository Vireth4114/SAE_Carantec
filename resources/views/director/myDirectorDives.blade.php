@extends('template')

@section('content')
@php
    use Carbon\Carbon;
    $increment = 0;
@endphp

<div>
    @if ($dives->count()==0)
        <p>Vous n'êtes le directeur pour aucune plongée</p>
    @else
        <h3>Vos plongées :</h3>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>heure de début</th>
                    <th>heure de fin</th>
                    <th>Site</th>
                    <th>Niveau</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dives as $dive)
                    @php
                        $date = Carbon::parse($dive->DIV_DATE)->locale('fr_FR')->translatedFormat('l j F Y');
                        $startTime = $periods[$increment]->PER_START_TIME->format('H');
                        $endTime = $periods[$increment]->PER_END_TIME->format('H');
                    @endphp
                    <tr>
                        <th>{{$date}}</th>
                        <th>{{$startTime}}</th>
                        <th>{{$endTime}}</th>
                        <th>{{$sites[$increment]}}</th>
                        <th>{{$prerogatives[$increment++]}}</th>
                        <th>
                            <a href="{{route('diveInformation', $dive->DIV_NUM_DIVE)}}"><button>Gérer la plongée</button></a>
                        </th>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>




@endsection