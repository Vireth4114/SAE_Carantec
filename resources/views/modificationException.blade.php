@extends("template")

@section("content")

    @foreach($error_msg as $msg)
        <p>{{$msg}}</p><br>
    @endforeach

    <a href="{{Route('member_modification',$member_num)}}">Retourner à la modification</a>
@endsection
