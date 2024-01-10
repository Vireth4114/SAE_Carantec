@extends('template')

@section('content')


@foreach ($members as $key=>$group)
    @if ($key != null)

        @if (sizeof($group[0]) == 0)
            <p>Palanquée Vide</p>
        @else
            @foreach ($group[0] as $member)
                <div class='memberInGroup'>
                    <p>{{$member->MEM_NAME.' '.$member->MEM_SURNAME.' '.$member->PRE_LEVEL}}</p>
                    <a href='{{Route('removeFromGroup', ['dive' => $dive, 'member' => $member->MEM_NUM_MEMBER])}}'>Supprimer</a>
                </div>
            @endforeach
        @endif


        @if ($group[1] != sizeof($group[0]))
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
        <br/>
    @endif
@endforeach
<a href='{{ route('addGroup') }}'>Nouvelle palanquée</a>
@endsection
