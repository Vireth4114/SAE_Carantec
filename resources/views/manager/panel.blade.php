@extends("template")

@section("content")
    <h2>Bateaux</h2>
    <a href="{{ route('boatCreate') }}">Créer un bateau</a>
    @foreach ($boats as $boat)
        <p>{{ $boat->BOA_NAME }} ({{ $boat->BOA_CAPACITY }})</p>
        <form action="{{ route('boatUpdate', ['boatId' => $boat->BOA_NUM_BOAT]) }}" method="GET">
            @method("get")
            <input type="submit" value="Modifier le bateau" />
        </form>
        <form action="{{ route('boatDelete', ['boatId' => $boat->BOA_NUM_BOAT]) }}" method="POST">
            @csrf
            @method("delete")
            <input type="submit" value="Supprimer le bateau" />
        </form>
    @endforeach

    <h2>Sites</h2>
    <a href="{{ route('siteCreate') }}">Créer un site</a>
    @foreach ($sites as $site)
        <h3>{{ $site->SIT_NAME }} ({{ $site->SIT_COORD }})</h3>
        <p>Profondeur : {{ $site->SIT_DEPTH }}</p>
        @if (!empty($site->SIT_DESCRIPTION))
            <p> {{ $site->SIT_DESCRIPTION }}</p>
        @endif
        <form action="{{ route('siteUpdate', ['siteId' => $site->SIT_NUM_SITE]) }}" method="GET">
            @method("get")
            <input type="submit" value="Modifier le site" />
        </form>
        <form action="{{ route('siteDelete', ['siteId' => $site->SIT_NUM_SITE]) }}" method="POST">
            @csrf
            @method("delete")
            <input type="submit" value="Supprimer le site" />
        </form>
    @endforeach

    <h2>Adhérents</h2>
    @foreach ($members as $member)
        <h3>{{ $member->MEM_NAME }} {{ $member->MEM_SURNAME }}
            (@if ($member->MEM_STATUS === 1)
                ACTIF
            @else
                INACTIF
            @endif)
            </a>
        </h3>
        <p>Il reste {{ $member->MEM_REMAINING_DIVES }} plongées à ce membre.</p>
        @php
            $memberFunction = $member->functions;
            $f
        @endphp
        <form action="{{ route('userRolesUpdate', ['userId' => $member->MEM_NUM_MEMBER]) }}" method="POST">
            @csrf
            @method("patch")
            <label for="security">Sécurité de surface</label>
            <input type="checkbox" id="security" name="security"
            @if (!$memberFunction->where("FUN_LABEL", "=", "Sécurité de surface")->isEmpty())
                checked
            @endif
            />
            <label for="pilot">Pilote</label>
            <input type="checkbox" id="pilot" name="pilot" @if (!$memberFunction->where("FUN_LABEL", "=", "Pilote")->isEmpty())
                checked
                @elseif
            @endif
            />
            <label for="secretary">Secrétaire</label>
            <input type="checkbox" id="secretary" name="secretary" 
            @if (!$memberFunction->where("FUN_LABEL", "=", "Secrétaire")->isEmpty())
                checked
            @endif
            />
            <input type="submit" value="Mettre à jour le membre" />
        </form>
    @endforeach
@endsection