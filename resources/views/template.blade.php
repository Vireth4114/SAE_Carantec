<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{asset('/css/app.css')}}" rel="stylesheet">
    <title>Document</title>
</head>
<body>
    <header>
        <img id='logo' src="{{asset('images/logo.png')}}" alt="Logo Carentec Nautisme">
        <div id='headerLinks'>
            @if(!Auth::check())
                <a href="" id="connexion">Connexion</a>
            @else
                @if(/*ajouter si secrétaire ou responsable*/true)
                    <a href="">Adhérent</a>
                    @if(/*retester responsable*/true)
                        <a href="">Archives</a>
                    @endif
                @elseif(/*verifier directeur*/true)
                    <a href="">Mes séances</a>
                @endif
                    <a href="">Plongée</a>
                    <a href="">Bilan</a>
                    <a href="">Profil</a>
                    <a href="">Deconnexion</button>
            @endif
        </div>
    </header>

    @yield('content')
</body>
</html>
