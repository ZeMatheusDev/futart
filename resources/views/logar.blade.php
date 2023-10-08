<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<link rel="stylesheet" type="text/css" href="/login.css">
</head>
<body>
    @if(session('logado') == false)

    <div id="links">
        <a href="/">Home</a>
        <a href="/criarConta">Criar Conta</a>
    </div>
@endif
@if(session('logado') == true)
<div id="links">
    <form style="display: inline;" action="{{ route('deslogar') }}" method="POST">
        @csrf
        <button type="submit">Logout</button>
    </form></div>
@endif

@if(session('error'))
<div class="alert alert-danger" style="text-align: center">
    {{ session('error') }}
</div>
@endif
    <audio id="myAudio" controls>
        <source src="../music.mp3" type="audio/mpeg">
        Seu navegador não suporta o elemento de áudio.
      </audio>
    <form method="POST" action="{{ route('logando') }}">
        @csrf
    <div id="cadastro">
        <div id="titulo">Fazer login</div>
        <input type="text" required placeholder="usuario" id="usuario" name="usuario">
        <input type="text" required placeholder="senha" id="senha" name="senha">
        <button class="btn btn-secondary" type="submit">
            Logar
        </button>
    </div>

    </form>
    
<script>
    function playAudio() {
      var audio = document.getElementById("myAudio");
      audio.play();
    }
    </script>
</body>
</html>