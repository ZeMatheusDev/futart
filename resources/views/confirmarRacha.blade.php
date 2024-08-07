<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<link rel="stylesheet" type="text/css" href="/duvida.css">

</head>
<body>
@if(session('logado') == true)
<div id="links">
<form style="display: inline;" action="{{ route('deslogar') }}" method="POST">
        @csrf
        <img class="foto-circular" src="{{ asset('fotos/' . session()->get('foto')) }}" alt="Sua Imagem">

        <button type="submit">Logout</button>
    </form></div>
@endif
@if(session('error'))
<div style="text-align: center" class="alert alert-danger">
    {{ session('error') }}
</div>
@endif
    <audio id="myAudio" controls>
        <source src="{{asset('../music.mp3')}}" type="audio/mpeg">
        Seu navegador não suporta o elemento de áudio.
      </audio>
      @foreach ($verificacaoDosRachasHoje as $racha)
      <div id="invite">
        <div id="texto">
            <br>
            <br>
            Confirme se haverá o racha "{{$racha->nome_do_racha}}" hoje
            <div id="botoesConfirmar">
                <form action="{{asset('../confirmarRacha')}}" method="POST" class="mr-2">
                    @csrf
                    <input type="hidden" value="{{$racha->id}}" name="racha_id" id="racha_id">

                    <br><br><br><br>
                    <button type="submit" class="btn btn-success botao-confirmar">Confirmar</button>
                </form>
                <form action="{{asset('../cancelarRacha')}}" method="POST" class="mr-2">
                    @csrf
                    <input type="hidden" value="{{$racha->id}}" name="racha_id" id="racha_id">

                    <br><br><br><br>
                    <button type="submit" class="btn btn-danger botao-cancelar">Cancelar</button>
                </form>
            </div>
        </div>
        <br>
      @endforeach
      
        <br>

    </div>

</body>
</html>