@extends("template")
@section("content")

@php
    use Carbon\Carbon;
@endphp

@if(count($archives) == 0) 
    <p>Aucune archive</p>
@endif
<table>
    <tr>
        <td>N° de la plongée </td>
        <td>Date de la plongée</td>
        <td>Nom du bateau </td>
        <td>Niveau de la plongée </td>
        <td>Directeur de plongée </td>
        <td>Sécurité de surface</td>
        <td>Pilote </td>
    </tr>
    @foreach($archives as $archive)<tr>
        <td>{{$archive->DIV_NUM_DIVE}}</td>
        <td>{{Carbon::parse($archive->DIV_DATE)->locale('fr_FR')->translatedFormat('l j F Y')}} </td>
        <td>{{$archive->BOAT_NAME}} </td>
        <td>{{$archive->LEVEL}} </td>
        <td>{{$archive->LEADER }}</td>
        <td>{{$archive->SECURITY}} </td>
        <td>{{$archive->PILOT}} </td>
    </tr>
    @endforeach
</table>
@endsection