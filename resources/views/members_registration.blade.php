@extends('template')
@section('content')


</head>
<body>
    <form action="{{route("register_member")}}" method="POST">
        @csrf
        <input type="number" hidden name="member_num" ><br>

        <label>Nom du membre :</label>
        <input type="text" required id="member_name" name="member_name"  /><br>

        <label>Prénom du membre :</label>
        <input type="text" required id="member_surname" name="member_surname"  /><br>

        <label>Numéro de licence :</label>
        <input type="text" required id="member_licence" name="member_licence" /><br>

        <label>Mot de passe</label>
        <input type="password" required id="member_password" name="member_password" /><br>

        <label>Date de certification :</label>
        <input type="date" required id="certif_date" name="certif_date" /><br>

        <label>Type d'abonnement :</label>
            <select name="pricing_type" id="pricing_type">
            @foreach ($pricing as $price)
                    <option value='{{$price->MEM_PRICING}}'>{{$price->MEM_PRICING}}</option>
            @endforeach
        </select><br>

        <label>Prérogative :</label>
            <select name="member_prerog" id="member_prerog">
                @foreach ($prerogation as $prerog)
                    <option value='{{$prerog->PRE_PRIORITY}}'>{{$prerog->PRE_LEVEL}}</option>
                @endforeach
            </select><br>

        <button type="submit">Inscrire l'adhérent</button>

    </form>
</body>
@endsection
