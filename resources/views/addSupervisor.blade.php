@extends('template')

@section('content')
<form action='{{Route('addMemberToGroup')}}' method='POST'>
    @csrf
    <select name='member'>
        @foreach ($members as $member)
            <option value='{{$member->MEM_NUM_MEMBER}}'>{{$member->MEM_NAME.' '.$member->MEM_SURNAME.' '.$member->PRE_LEVEL}}</option>
        @endforeach
    </select>
    <input type='hidden' name='dive' value='{{$dive}}'>
    <input type='hidden' name='group' value='{{$group}}'>
    <button type="submit">Ajouter</button>
</form>
<br/>
@endsection
