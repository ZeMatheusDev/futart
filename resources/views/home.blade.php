<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<link rel="stylesheet" type="text/css" href="/home.css">

</head>
<body>

    @if(session('logado') == false)

    <div id="linksDeslogado">
        <a href="/criarConta">Criar Conta</a>
        <a href="/logar">Login</a>
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
@if(session('logado') == true)
<div id="header">
    <div class="notificacao-wrapper">
        @if ($notificacoes->isEmpty() == true)
            
        <img class="notificacao" src="{{ asset('../icone_vazio-removebg-preview.png') }}" alt="Sua Imagem">
        <div class="notificacao-menu">
            <a>Nenhuma solicitação</a>           
        </div>
        @endif
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
    </div>

<div id="links">
    <form style="display: inline;" action="{{ route('deslogar') }}" method="POST">
        @csrf
        <img class="foto-circular" src="{{ asset('fotos/' . session()->get('foto')) }}" alt="Sua Imagem">
    <a href="/cadastrar">Cadastrar Racha</a>
    <a href="/listagem">Listagem de Rachas</a>
    <a href="/listagemRachasAndamento">Rachas em andamento</a>
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