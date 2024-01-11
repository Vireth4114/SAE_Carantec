@extends('template')

@section('content')
<section id='groupsSection'>
<h2>{{$message}}</h2>
<div id='automaticContainer'>
@foreach ($members as $key=>$group)
    @if ($key != null)
        <div class='automaticBox'>
            @foreach ($group[0] as $member)
                <div class='memberInGroup'>
                    <p>{{$member->MEM_NAME.' '.$member->MEM_SURNAME.' '.$member->PRE_LABEL}}</p>
                    <a href='{{Route('removeFromGroup', ['dive' => $dive, 'member' => $member->MEM_NUM_MEMBER])}}'>Supprimer</a>
                </div>
            @endforeach
            <div class='addBox'>
                @if (($group[1] == 1 ? 2 : 3) != sizeof($group[0]))
                    @if (array_key_exists(null, $members))
                        <form action='{{Route('addMemberToGroup')}}' method='POST'>
                            @csrf
                            <label class='addLabel' for='member'>Ajouter un membre   : </label>
                            <select class='addElement' name='member'>
                                @foreach ($members[null][0] as $member)
                                    <option value='{{$member->MEM_NUM_MEMBER}}'>{{$member->MEM_NAME.' '.$member->MEM_SURNAME.' '.$member->PRE_LABEL}}</option>
                                @endforeach
                            </select>
                            <input type='hidden' name='dive' value='{{$dive}}'>
                            <input type='hidden' name='group' value='{{$key}}'>
                            <button class='addButton' type="submit">Ajouter</button>
                        </form>
                        <br/>
                    @endif
                    @if (array_key_exists(null, $members) && sizeof($supervisors) != 0)
                        <form action='{{Route('addMemberToGroup')}}' method='POST'>
                            @csrf
                            <label class='addLabel' for='member'>Ajouter un encadrant : </label>
                            <select class='addElement' name='member'>
                                @foreach ($supervisors as $member)
                                    <option value='{{$member->MEM_NUM_MEMBER}}'>{{$member->MEM_NAME.' '.$member->MEM_SURNAME.' '.$member->PRE_LABEL}}</option>
                                @endforeach
                            </select>
                            <input type='hidden' name='dive' value='{{$dive}}'>
                            <input type='hidden' name='group' value='{{$key}}'>
                            <button class='addButton' type="submit">Ajouter</button>
                        </form>
                        <br/>
                    @endif
                @endif
            </div>
        </div>
    @endif
@endforeach
</div>

@if (array_key_exists(null, $members))
    <form id='addToNew' action='{{Route('addGroup')}}' method='POST'>
        @csrf
        <select class='addElement' name='member'>
            @foreach ($members[null][0] as $member)
                <option value='{{$member->MEM_NUM_MEMBER}}'>{{$member->MEM_NAME.' '.$member->MEM_SURNAME.' '.$member->PRE_LABEL}}</option>
            @endforeach
        </select>
        <input type='hidden' name='dive' value='{{$dive}}'>
        <button class='addButton' type="submit">Ajouter à une nouvelle palanquée</button>
    </form>
@else
    <a href='{{Route('validateGroup', ["diveId" => $dive])}}'>Valider</a>
@endif
<a href='{{Route('automaticGroup')}}'>Arrangement aléatoire</a>
</section>
@endsection
