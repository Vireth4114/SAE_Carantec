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
        <td>Diecteur de plongée </td>
        <td>Sécurité de surface</td>
        <td>Pilote </td>
        <td>Niveau </td>
    </tr>
    {{dd($archives)}}
    @foreach($archives as $archive)<tr>
        <td>{{$archive->MEM_NUM_MEMBER}}</td>
        <td>{{$archive->MEM_NUM_LICENCE}} </td>
        <td>{{$archive->MEM_SURNAME}} </td>
        <td>{{$archive->MEM_NAME}} </td>
        <td>{{$archive->PRE_LEVEL }}</td>
        <td>{{$archive->MEM_DATE_CERTIF}} </td>
        <td>{{$archive->MEM_PRICING}} </td>
        <td>{{$archive->MEM_REMAINING_DIVES}}</td>
    </tr>
    @endforeach
</table>
@endsection