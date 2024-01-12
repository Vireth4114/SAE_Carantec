@extends('template')
@section('content')

<title>Modification d'un créneau</title>
</head>
<body>
    <form action="{{route("modify_member")}}" method="POST">
        @csrf
        <input type="number" hidden name="memberNum" value={{$member->MEM_NUM_MEMBER}}><br>

        <label>Nom du membre :</label>
        <input type="text" required id="memberName" name="memberName" value={{$member->MEM_NAME}} /><br>

        <label>Prénom du membre :</label>
        <input type="text" required id="memberSurname" name="memberSurname" value={{$member->MEM_SURNAME}} /><br>

        <label>Numéro de licence :</label>
        <input type="text" required disabled id="memberLicence" name="memberLicence" value={{$member->MEM_NUM_LICENCE}} /><br>

        <label>Date de certification :</label>
        <input type="date" required id="certifDate" name="certifDate" value={{$member->MEM_DATE_CERTIF}} /><br>

        <label>Type d'abonnement :</label>
            <select name="pricingType" id="pricingType">
            @foreach ($pricing as $price)
                @if($price->MEM_PRICING == $member->MEM_PRICING)
                    <option selected value='{{$member->MEM_PRICING}}'>{{$member->MEM_PRICING}}</option>
                @else
                    <option value='{{$price->MEM_PRICING}}'>{{$price->MEM_PRICING}}</option>
                @endif
            @endforeach
            </select><br>

        <label>Nombre de plongée restante :</label>
        <input type="number" required id="remainingDive" name="remainingDive" value={{$member->MEM_REMAINING_DIVES}} /><br>

        <label>Prérogative :</label>
            <select name="memberPrerogPriority" id="memberPrerogPriority">
            @foreach ($prerogatives as $prerogative)
                @if($memberPrerogative == $prerogative->PRE_LABEL)
                    <option selected value='{{$prerogative->PRE_PRIORITY}}'>{{$prerogative->PRE_LABEL}}</option>
                @else
                    <option value='{{$prerogative->PRE_PRIORITY}}'>{{$prerogative->PRE_LABEL}}</option>
                @endif
            @endforeach
            </select><br>

        <button type="submit">Modifier les informations</button>

    </form>
</body>
@endsection
