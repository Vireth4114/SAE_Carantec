@php
    use App\Models\web\AcnMember;
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{URL::asset('/css/app.css')}}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Document</title>
    <script src="https://kit.fontawesome.com/70f23b7858.js" crossorigin="anonymous"></script>
</head>
<body>
    <header>
        <nav>
            <a href="{{ route("welcome") }}"> <img src="{{URL::asset('images/logo.png')}}" alt="Logo Carentec Nautisme"></a>
            <div id="headerLinks">
                @if(Auth::check())
                    @php
                        $isUserSecretary = AcnMember::isUserSecretary(auth()->user()->MEM_NUM_MEMBER);
                        $isUserManager = AcnMember::isUserManager(auth()->user()->MEM_NUM_MEMBER);
                        $isUserDirector = AcnMember::isUserDirector(auth()->user()->MEM_NUM_MEMBER);
                    @endphp
                    @if($isUserSecretary)
                        <a class="no-deco" href="{{ route("members") }}">Liste d'adhérent</a>
                    @elseif($isUserManager)
                        <a class="no-deco" href="{{ route("managerPanel") }}">Partie Responsable</a>
                    @endif
                    @if($isUserManager)
                        <a class="no-deco" href="{{ route("diveCreation") }}">Création de plongée</a>
                    @endif
                    {{-- @if($isUserManager)
                            <a class="no-deco" href="">Archives</a> --}}
                    {{--@else--}}@if($isUserDirector)
                        <a class="no-deco" href="{{route('myDirectorDives')}}">Mes séances</a>
                    @endif
                        <a class="no-deco" href="{{ route('dives') }}">S'inscrire</a>
                        <a class="no-deco" href="{{route('diveReport')}}">Historique</a>
                        <a class="no-deco" href="{{route('profil_page')}}">Profil</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <a class="no-deco" id="logOutButton" :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            {{ __('Déconnexion') }}
                        </a>
                    </form>
                @elseif(!Route::is('login'))
                    <a class="no-deco" href="{{ route('login') }}">Connexion</a>
                @endif
            </div>
            @if(Auth::check())
                <label>{{auth()->user()->MEM_REMAINING_DIVES}} plongée restante</label>
            @endif
        </nav>
    </header>

    @yield('content')
</body>
</html>
