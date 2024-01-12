@extends('template')
@section('content')
<section class='createSection'>
    <title>Modification d'un créneau</title>
    </head>
    <body>
        <form class='createForm' action="{{route("modify_member")}}" method="POST">
            @csrf
            <input type="number" hidden name="member_num" value={{$member->MEM_NUM_MEMBER}}>

            <div class='createFields'>
                <label>Nom du membre :</label>
                <input type="text" required id="member_name" name="member_name" value={{$member->MEM_NAME}} />
            </div>

            <div class='createFields'>
                <label>Prénom du membre :</label>
                <input type="text" required id="member_surname" name="member_surname" value={{$member->MEM_SURNAME}} />
            </div>

            <div class='createFields'>
                <label>Numéro de licence :</label>
                <input type="text" required disabled id="member_licence" name="member_licence" value={{$member->MEM_NUM_LICENCE}} />
            </div>

            <div class='createFields'>
                <label>Date de certification :</label>
                <input type="date" required id="certif_date" name="certif_date" value={{$member->MEM_DATE_CERTIF}} />
            </div>

            <div class='createFields'>
                <label>Type d'abonnement :</label>
                    <select name="pricing_type" id="pricing_type">
                    @foreach ($pricing as $price)
                        @if($price->MEM_PRICING == $member->MEM_PRICING)
                            <option selected value='{{$member->MEM_PRICING}}'>{{$member->MEM_PRICING}}</option>
                        @else
                            <option value='{{$price->MEM_PRICING}}'>{{$price->MEM_PRICING}}</option>
                        @endif
                    @endforeach
                    </select>
            </div>

            <div class='createFields'>
                <label>Nombre de plongée restante :</label>
                <input type="number" required id="remaining_dive" name="remaining_dive" value={{$member->MEM_REMAINING_DIVES}} />
            </div>

            <div class='createFields'>
                <label>Prérogative :</label>
                    <select name="member_prerog" id="member_prerog">
                    @foreach ($prerogation as $prerog)
                        @if($prerogation_member_level == $prerog->PRE_PRIORITY)
                            <option selected value='{{$prerog->PRE_PRIORITY}}'>{{$prerog->PRE_LEVEL}}</option>
                        @else
                            <option value='{{$prerog->PRE_PRIORITY}}'>{{$prerog->PRE_LEVEL}}</option>
                        @endif
                    @endforeach
                    </select>
            </div>

            <button class='btn btn-secondary' type="submit">Modifier les informations</button>

        </form>
    </body>
</section>
@endsection
