@extends('template')

@section('content')
<h2>Voici les palanquées générées:</h2>
@foreach ($members as $key=>$group)
    @if ($key != null)
        <div class='groupBox'>
            @foreach ($group as $member)
                <div class='memberInGroup'>
                    <p>{{$member->MEM_NAME.' '.$member->MEM_SURNAME.' '.$member->PRE_LABEL}}</p>
                </div>
            @endforeach
        </div>
    @endif
@endforeach
@endsection
