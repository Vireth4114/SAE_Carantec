        <title>Création d'un créneau</title>
    </head>
    <body>
        <form action="" method="post"></form>
            <label>Insérer une date (obligatoire) :</label>
            <input type="date" required id="date" name="date"  min="{{date('Y')}}-03-01" max="{{date('Y')}}-11-31" />

            <label> Choisir une période :</label>
            <select name="period" id="period">
            @foreach ($periods as $period)
                <option value='{{$period->PER_NUM_PERIOD}}'>{{$period->PER_LABEL}}</option>
            @endforeach
            </select>

            <label>Choisir un site :</label>
            <select name="site" id="site">
            @foreach ($sites as $site)
                <option value='{{$site->SIT_NUM_SITE}}'>{{$site->SIT_NAME}}</option>
            @endforeach
            </select>

            <label>Choisir un bateau :</label>
            <select name="boat" id="boat">
            @foreach ($boats as $boat)
                <option value='{{$boat->BOA_NUM_BOAT}}'>{{$boat->BOA_NAME}}</option>
            @endforeach
            </select>

            <label>Choisir le niveau requis :</label>
            <select name="lvl_required" id="lvl_required">
            @foreach ($prerogatives as $prerogative)
                <option value='{{$prerogative->PRE_NUM_PREROG}}'>{{$prerogative->PRE_LEVEL}}</option>
            @endforeach
            </select>

            <label>Choisir un directeur de plongée :</label>
            <select name="lead" id="lead">
            @foreach ($leads as $lead)
                <option value='{{$lead->MEM_NUM_MEMBER}}'>{{$lead->MEM_NAME." ".$lead->MEM_SURNAME}}</option>
            @endforeach

            </select>

            <label>Choisir un pilote :</label>
            <select name="pilot" id="pilot">
            @foreach ($pilots as $pilot)
                <option value='{{$pilot->MEM_NUM_MEMBER}}'>{{$pilot->MEM_NAME." ".$pilot->MEM_SURNAME}}</option>
            @endforeach
            </select>

            <label>Choisir une sécurité de surface :</label>
            <select name="security" id="security">
            @foreach ($securitys as $security)
                <option value='{{$security->MEM_NUM_MEMBER}}'>{{$security->MEM_NAME." ".$security->MEM_SURNAME}}</option>
            @endforeach

            </select>

            <label>Effectif minimum :</label>
            <input type=number name="min_divers" id="min_divers" value=0>

            <label>Effectif maximum :</label>
            <input type=number name="max_divers" id="max_divers" value=0>

            <button type="submit">Créer le créneau</button>
        </form>
    </body>
</html>
