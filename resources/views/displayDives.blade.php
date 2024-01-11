@extends("template")
@php use App\Models\web\AcnMember; @endphp

@section("content")
@php
    $id=0;
    use Carbon\Carbon;
    $user = auth()->user();
@endphp
    <div class="center_display">
    @if(!empty(session('errors')))
        @foreach (session('errors')->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    @endif
    @foreach($months as $month)
        <h2>{{ ucfirst(Carbon::parse($month->mois_nb."/01/2000")->locale('fr_FR')->translatedFormat('F')) }}</h2>

        @foreach($dives[$month->mois_mot] as $dive)
            @php
                $date = Carbon::parse($dive->DIV_DATE)->locale('fr_FR')->translatedFormat('l j F Y');
                $heureStart = date_Format(DateTime::createFromFormat('H:i:s',$dive->PER_START_TIME), 'G');
                $heureFin = date_Format(DateTime::createFromFormat('H:i:s',$dive->PER_END_TIME), 'G');
                $buttonText = "S'inscrire"
            @endphp
            <div id="divesDisplayed">
            <form
            @if ($user->dives->contains("DIV_NUM_DIVE", $dive->DIV_NUM_DIVE))
                action="{{ route('membersDivesUnregister') }}"
                @php
                    $buttonText = "Se désinscrire"
                @endphp
            @else
                action="{{ route('membersDivesRegister') }}"
            @endif
            method="POST">
                @csrf
                @method('post')
                <p >
                    <a  class="hyperlink-no_style"  href="{{route('dives_informations',$dive->DIV_NUM_DIVE)}}">
                        <input
                        type='hidden'
                        name='dive'
                        value={{$dive->DIV_NUM_DIVE}}
                        >
                        {{ $date }}
                        de {{ $heureStart }}h à {{ $heureFin }}h
                        Site prevu : {{ $dive->SIT_NAME }}
                        ( {{ $dive->SIT_DESCRIPTION }} )
                        Niveau : {{$dive->PRE_LABEL}}
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </a>
                    <button class="btn btn-primary" type="submit" value="" @if ($dive->PRE_PRIORITY > $user->prerogatives->max("PRE_PRIORITY"))
                        disabled
                    @endif>{{ $buttonText }}</button>
                    @if(AcnMember::isUserManager(auth()->user()->MEM_NUM_MEMBER))
                        <a href="{{route('diveModify',$dive->DIV_NUM_DIVE)}}">Modifier</a>
                        {{-- <a href="{{route('diveModify',$dive->DIV_NUM_DIVE)}}">Modifier</a> //TO DO to delete a dive --}}
                    @endif
                </p>
            </form>
        </div>
        @endforeach
    @endforeach
    </div>
@endsection
