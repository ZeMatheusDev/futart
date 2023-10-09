<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<link rel="stylesheet" type="text/css" href="/cadastro.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/lazysizes/5.3.2/lazysizes.min.js"></script>

</head>
<body>
<audio id="meuAudio" src="../gol.mp3" style="display: none"></audio>
@if(session('logado') == true)
<div id="header">
    <div class="notificacao-wrapper">
        @if ($notificacoes->isEmpty() == true)
            
        <img class="notificacao" src="{{ asset('../icone_vazio-removebg-preview.png') }}" alt="Sua Imagem">
        <div class="notificacao-menu">
            <a>Nenhuma solicitação</a>           
        </div>
        @endif
    </div>
    @if ($notificacoes->isEmpty() == false)
            
        <img class="notificacao-cheia" src="{{ asset('../icone_cheio-removebg-preview.png') }}" alt="Sua Imagem">
        <div class="notificacao-menu">
            @php
            $contador = 1;
            @endphp
            @foreach ($notificacoes as $notificacao)
                <form action="{{asset('../telaInvite/'.$notificacao->racha_token)}}" method="HEAD">
                    @csrf
                    <input type="hidden" id="racha_token" name="racha_token" value="{{$notificacao->racha_token}}">
                    <button type="submit">{{$contador}} - convite feito por {{$notificacao->nome}}</button>
                </form>
                <hr> <!-- Adiciona um traço entre os botões -->
                @php
                $contador++;
                @endphp
            @endforeach          
        </div>
        @endif

<div id="links">
    <form style="display: inline;" action="{{ route('deslogar') }}" method="POST">
        @csrf
        <img class="foto-circular" src="../fotos/{{session()->all()['foto']}}" alt="Sua Imagem">



        <a href="/">Home</a>
        <a href="/cadastrar">Cadastrar Racha</a>
        <button type="submit">Logout</button>
    </form></div>
</div>
@endif
    @if(session('error'))
    <div style="text-align: center" class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
@if(session('success'))
<div style="text-align: center" class="alert alert-success">
    {{ session('success') }}
</div>
@endif
<audio id="myAudio" controls>
    <source src="../music.mp3" type="audio/mpeg">
    Seu navegador não suporta o elemento de áudio.
</audio>

<div class="container">
    @foreach ($jogadoresSeparados as $jogador)
        <div class="detalhes">
            <img src="../fotos/{{basename($jogador->foto)}}" alt="Sua Imagem"><br>
            <p> {{$jogador->nome}} </p>
            <p>Posição: {{$jogador->posicao}}</p>
            <br>
        <form action="" class="btn btn-success">Detalhes</form>

        </div>
    @endforeach
</div>

<script>
            document.addEventListener('DOMContentLoaded', function () {
            var notificacaoWrapper = document.querySelector('.notificacao-wrapper');
            var notificacaoMenu = document.querySelector('.notificacao-menu');
    
            notificacaoWrapper.addEventListener('click', function (event) {
                event.stopPropagation(); // Impede que o evento se propague para outros elementos
    
                if (notificacaoMenu.style.display === 'block') {
                    notificacaoMenu.style.display = 'none';
                } else {
                    notificacaoMenu.style.display = 'block';
                }
            });
    
            // Fechar o menu se clicar em qualquer lugar fora dele
            document.addEventListener('click', function (event) {
                if (event.target !== notificacaoWrapper && event.target !== notificacaoMenu) {
                    notificacaoMenu.style.display = 'none';
                }
            });
        });
</script>
</body>
</html>