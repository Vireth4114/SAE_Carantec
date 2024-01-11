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
    @if($dives[$month->mois_mot]->count() > 0)
        <h2>{{ucfirst(Carbon::parse($month->mois_nb."/01/2000")->locale('fr_FR')->translatedFormat('F')) }}</h2>
    @endif
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
                    <a  class="linkDisplayDives hyperlink-no_style"  href="{{route('dives_informations',$dive->DIV_NUM_DIVE)}}">
                        <input
                        type='hidden'
                        name='dive'
                        value={{$dive->DIV_NUM_DIVE}}
                        >
                        {{ $date }}
                        de {{ $heureStart }}h à {{ $heureFin }}h <br/>
                        Site prevu : {{ $dive->SIT_NAME }}
                        ({{ $dive->SIT_DESCRIPTION }}) <br/>
                        Niveau : {{$dive->PRE_LABEL}}
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </a>
                    <div id='buttonsDive'>
                        <button class="btn btn-primary" type="submit" value="" @if ($dive->PRE_PRIORITY > $user->prerogatives->max("PRE_PRIORITY"))
                            disabled
                        @endif>{{ $buttonText }}</button>
                        @if(AcnMember::isUserManager(auth()->user()->MEM_NUM_MEMBER))
                            <a class='btn btn-secondary' href="{{route('diveModify',$dive->DIV_NUM_DIVE)}}">Modifier</a>
                            {{-- <a href="{{route('diveModify',$dive->DIV_NUM_DIVE)}}">Modifier</a> //TO DO to delete a dive --}}
                        @endif
                    </div>
                </p>
            </form>
            @if(AcnMember::isUserManager(auth()->user()->MEM_NUM_MEMBER))
                <button onclick="document.getElementById('id01').style.display='block'">Supprimer</button>
                <div id="id01" class="modal">
                    <span onclick="document.getElementById('id01').style.display='none'" class="close" title="Close Modal">&times;</span>
                    <form class="modal-content" action="/action_page.php">
                        <div class="container">
                            <h1>Surpprimer la plongée</h1>
                            <p>êtes vous sure de vouloir supprimer cette plongée ?</p>
                    
                            <div class="clearfix">
                                <button type="button" onclick="document.getElementById('id01').style.display='none'" class="cancelbtn" >Annuler</button>
                                <a href ="{{ route('diveDeletion', $dive->DIV_NUM_DIVE) }}"><button type="button" class="deletebtn">Supprimer</button></a>
                            </div>
                         </div>
                    </form>
                </div>
                <script>
                    // Get the modal
                    var modal = document.getElementById('id01');
                    
                    // When the user clicks anywhere outside of the modal, close it
                    window.onclick = function(event) {
                        if (event.target == modal) {
                            modal.style.display = "none";
                        }
                    }
                </script>
                <a href="{{route('diveModify',$dive->DIV_NUM_DIVE)}}">Modifier</a>
                {{-- <a href="{{route('diveModify',$dive->DIV_NUM_DIVE)}}">Modifier</a> //TO DO to delete a dive --}}
            @endif
        </div>
        @endforeach
    @endforeach
    </div>
@endsection
