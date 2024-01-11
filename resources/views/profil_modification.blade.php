@extends('template')
@section('content')

<title>Modification d'un créneau</title>
</head>
<body>
    <form action="{{route("modify_profil")}}" method="POST">
        @csrf

        <label>Nom du membre :</label>
        <input type="text" required id="member_name" name="member_name" value={{$member->MEM_NAME}} /><br>

        <label>Prénom du membre :</label>
        <input type="text" required id="member_surname" name="member_surname" value={{$member->MEM_SURNAME}} /><br>

        <p>Numéro de licence : {{$member->MEM_NUM_LICENCE}}</p>

        <p>Date de certification : {{$member->MEM_DATE_CERTIF}}</p>

        <p>Type d'abonnement : {{$member->MEM_PRICING}}</p>

        <p>Nombre de plongée restante : {{$member->MEM_REMAINING_DIVES}}</p>

        <p>Prérogative :
            @foreach($prerogation as $prerog)
                @if($prerog->PRE_NUM_PREROG <= $prerogation_member_level)
                    {{$prerog->PRE_LEVEL}},
                @endif
            @endforeach
        </p>

        <button type="submit">Modifier les informations</button>

    </form>
</body>
@endsection
