@extends('template')
@section('content')

<title>Modification d'un créneau</title>
</head>
<body>
    <form id='profilModifForm' action="{{route("modify_profil")}}" method="POST">
        @csrf

        <div class='profilModifField'>
            <label>Prénom du membre :</label>
            <input type="text" required id="member_name" name="member_name" value={{$member->MEM_NAME}} />
        </div>

        <div class='profilModifField'>
            <label>Nom du membre :</label>
            <input type="text" required id="member_surname" name="member_surname" value={{$member->MEM_SURNAME}} />
        </div>

        <p>Numéro de licence : <b>{{$member->MEM_NUM_LICENCE}}</b></p>

        <p>Date de certification : <b>{{$member->MEM_DATE_CERTIF}}</b></p>

        <p>Type d'abonnement : <b>{{$member->MEM_PRICING}}</b></p>

        <p><b>{{$member->MEM_REMAINING_DIVES}}</b> plongées restantes</p>

        <p>Prérogatives :<b>
            @foreach($prerogation as $prerog)
                @if($prerog->PRE_NUM_PREROG <= $prerogation_member_level)
                    {{$prerog->PRE_LEVEL}},
                @endif
            @endforeach
            </b>
        </p>

        <button class='btn btn-secondary' type="submit">Modifier les informations</button>

    </form>
</body>
@endsection
