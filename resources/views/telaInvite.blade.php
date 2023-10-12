<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<link rel="stylesheet" type="text/css" href="/invite.css">

</head>
<body>
    @if(session('logado') == false)

    <div id="links">
        <a href="/criarConta">Criar Conta</a>
        <a href="/logar">Logar</a>
    </div>
@endif
@if(session('logado') == true)
<div id="links">
<form style="display: inline;" action="{{ route('deslogar') }}" method="POST">
        @csrf
        <img class="foto-circular" src="{{ asset('fotos/' . session()->get('foto')) }}" alt="Sua Imagem">

        <button type="submit">Logout</button>
        <a href="/">Home</a>

    <a href="/cadastrar">Cadastrar Racha</a>
    <a href="/listagem">Listagem de Rachas</a>
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

      <div id="invite">
        <div id="texto">
            <br><br>
            <img src="{{asset('../invite.png')}}" alt="">
            <br><br>
            Esse é um convite do <strong style="font-size: 25px">{{$dados['dono']->nome}}</strong> para o racha <strong style="font-size: 25px">{{$dados['racha']->nome_do_racha}}</strong>
        </div>
        <br>
        <form action="{{asset('../aceitar')}}" style="display: inline" method="POST">
            @csrf
        <input type="hidden" name="racha_id" id="racha_id" value="{{$dados['racha']->id}} ">
        <input type="hidden" name="usuario_id" id="usuario_id" value="{{session()->all()['id']}}">
        <button class="btn btn-primary" style="display: inline-block;">Aceitar</button>
        </form>
        <form action="{{asset('../recusarRacha')}}" style="display: inline" method="POST">
        @csrf
        <input type="hidden" name="racha_id" id="racha_id" value="{{$dados['racha']->id}} ">
        <input type="hidden" name="usuario_id" id="usuario_id" value="{{session()->all()['id']}}">
        <button class="btn btn-danger" style="display: inline-block;">Recusar</button>
        </form>
    </div>

</body>
</html>