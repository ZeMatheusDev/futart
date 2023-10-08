<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<link rel="stylesheet" type="text/css" href="/home.css">

</head>
<body>

    @if(session('logado') == false)

    <div id="links">
        <a href="/criarConta">Criar Conta</a>
        <a href="/logar">Logar</a>
    </div>
@endif
@if(session('logado') == true)
<img class="notificacao" src="{{ asset('../icone_vazio-removebg-preview.png') }}" alt="Sua Imagem">
<div id="header">
<div id="links">
    <form style="display: inline;" action="{{ route('deslogar') }}" method="POST">
        @csrf
        <img class="foto-circular" src="{{ asset('fotos/' . session()->get('foto')) }}" alt="Sua Imagem">
    <a href="/cadastrar">Cadastrar Racha</a>
    <a href="/listagem">Listagem de Rachas</a>
    <button type="submit">Logout</button>

    </form></div>
</div>

@endif

    <audio id="myAudio" controls>
        <source src="../music.mp3" type="audio/mpeg">
        Seu navegador não suporta o elemento de áudio.
      </audio>
    <div id="top">

    </div>

</body>
</html>