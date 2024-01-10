@extends('template')
@section('content')

<title>Modification d'un créneau</title>
</head>
<body>
    <form action="{{route("modify_member")}}" method="POST">
        @csrf
        <input type="number" hidden name="member_num" value={{$member->MEM_NUM_MEMBER}}><br>

        <label>Nom du membre :</label>
        <input type="text" required id="member_name" name="member_name" value={{$member->MEM_NAME}} /><br>

        <label>Prénom du membre :</label>
        <input type="text" required id="member_surname" name="member_surname" value={{$member->MEM_SURNAME}} /><br>

        <label>Numéro de licence :</label>
        <input type="text" required disabled id="member_licence" name="member_licence" value={{$member->MEM_NUM_LICENCE}} /><br>
        
        <label>Date de certification :</label>
        <input type="date" required id="certif_date" name="certif_date" value={{$member->MEM_DATE_CERTIF}} /><br>

        <label>Type d'abonnement :</label>
            <select name="pricing_type" id="pricing_type">
            @foreach ($pricing as $price)
                @if($price->MEM_PRICING == $member->MEM_PRICING)
                    <option selected value='{{$member->MEM_PRICING}}'>{{$member->MEM_PRICING}}</option>
                @else
                    <option value='{{$price->MEM_PRICING}}'>{{$price->MEM_PRICING}}</option>
                @endif
            @endforeach
            </select><br>

        <label>Nombre de plongée restante :</label>
        <input type="number" required id="remaining_dive" name="remaining_dive" value={{$member->MEM_REMAINING_DIVES}} /><br>

        <label>Prérogations :</label>
            <select name="member_prerog" id="member_prerog">
            @foreach ($prerogation as $prerog)
                @if($prerogation_member_level == $prerog->PRE_PRIORITY)
                    <option selected value='{{$prerog->PRE_PRIORITY}}'>{{$prerog->PRE_LEVEL}}</option>
                @else
                    <option value='{{$prerog->PRE_PRIORITY}}'>{{$prerog->PRE_LEVEL}}</option>
                @endif
            @endforeach
            </select><br>

        <label>Niveau formateur :</label>
            <select name="member_tutor_lvl" id="member_tutor_lvl">
                <option value=0>Aucun</option>
            @foreach ($tutorlvl as $tutor)
                @if($tutor_member_level == $tutor->PRE_PRIORITY)
                    <option selected value='{{$tutor->PRE_PRIORITY}}'>{{$tutor->PRE_LEVEL}}</option>
                @else
                    <option value='{{$tutor->PRE_PRIORITY}}'>{{$tutor->PRE_LEVEL}}</option>
                @endif
            @endforeach
            </select><br>

        <button type="submit">Modifier les informations</button>

    </form>
</body>
@endsection
