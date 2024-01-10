@extends('template')

@section('content')

@foreach ($members as $key=>$group)
    @if ($key != null)
        <div class='groupBox'>
            @foreach ($group[0] as $member)
                <div class='memberInGroup'>
                    <p>{{$member->MEM_NAME.' '.$member->MEM_SURNAME.' '.$member->PRE_LEVEL}}</p>
                    <a href='{{Route('removeFromGroup', ['dive' => $dive, 'member' => $member->MEM_NUM_MEMBER])}}'>Supprimer</a>
                </div>
            @endforeach
            @if (($group[1] == 1 ? 2 : 3) != sizeof($group[0]) && array_key_exists(null, $members))
                <form action='{{Route('addMemberToGroup')}}' method='POST'>
                    @csrf
                    <select name='member'>
                        @foreach ($members[null][0] as $member)
                            <option value='{{$member->MEM_NUM_MEMBER}}'>{{$member->MEM_NAME.' '.$member->MEM_SURNAME.' '.$member->PRE_LEVEL}}</option>
                        @endforeach
                    </select>
                    <input type='hidden' name='dive' value='{{$dive}}'>
                    <input type='hidden' name='group' value='{{$key}}'>
                    <button type="submit">Ajouter</button>
                </form>
                <br/>
            @endif
        </div>
    @endif
@endforeach

@if (array_key_exists(null, $members))
    <form action='{{Route('addGroup')}}' method='POST'>
        @csrf
        <select name='member'>
            @foreach ($members[null][0] as $member)
                <option value='{{$member->MEM_NUM_MEMBER}}'>{{$member->MEM_NAME.' '.$member->MEM_SURNAME.' '.$member->PRE_LEVEL}}</option>
            @endforeach
        </select>
        <input type='hidden' name='dive' value='{{$dive}}'>
        <button type="submit">Ajouter à une nouvelle palanquée</button>
    </form>
@endif
@endsection
