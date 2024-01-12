@extends('template')
@section('content')

<title>Modification d'un créneau</title>
</head>
<body>
    <form action="{{route("modify_profil")}}" method="POST">
        @csrf

        <label>Nom du membre :</label>
        <input type="text" required id="memberName" name="memberName" value={{$member->MEM_NAME}} /><br>

        <label>Prénom du membre :</label>
        <input type="text" required id="memberSurname" name="memberSurname" value={{$member->MEM_SURNAME}} /><br>

        <p>Numéro de licence : {{$member->MEM_NUM_LICENCE}}</p>

        <p>Date de certification : {{$member->MEM_DATE_CERTIF}}</p>

        <p>Type d'abonnement : {{$member->MEM_PRICING}}</p>

        <p>Nombre de plongée restante : {{$member->MEM_REMAINING_DIVES}}</p>

        <p>Prérogative : {{$prerogative}}</p>

        <button type="submit">Modifier les informations</button>

    </form>
</body>
@endsection
