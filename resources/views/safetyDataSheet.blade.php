@extends('template')

@section('content')
<div>
    <button name='dataSheet' onclick="window.location.href = '/pdfConverter'">Imprimer la fiche de sécurité</button>
</div>

@include('safetySheet')

@endsection
