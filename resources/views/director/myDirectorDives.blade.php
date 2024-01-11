@extends('template')

@section('content')
@php
    use Carbon\Carbon;
    $increment = 0;
@endphp
@foreach ($dives as $dive)
    @php
        $date = Carbon::parse($dive->DIV_DATE)->locale('fr_FR')->translatedFormat('l j F Y');
        $startTime = $periods[$increment]->PER_START_TIME->format('H');
        $endTime = $periods[$increment]->PER_END_TIME->format('H');
    @endphp
    <a href="{{route('diveInformation', $dive->DIV_NUM_DIVE)}}">
        <p>
        plongée du {{ $date }}
        de {{ $startTime }}h à {{ $endTime }}h
        Site prevu : {{ $sites[$increment] }}
        Niveau : {{$prerogatives[$increment]}}
        </p>
    </a>
@endforeach
@endsection