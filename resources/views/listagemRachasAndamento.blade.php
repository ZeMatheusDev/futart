<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<link rel="stylesheet" type="text/css" href="/listagemRachasAndamento.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/lazysizes/5.3.2/lazysizes.min.js"></script>

</head>
<body>
<audio id="meuAudio" src="../gol.mp3" style="display: none"></audio>
@if(session('logado') == true)
<div id="header">
    <div class="notificacao-wrapper">

    </div>
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
                <hr>
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
        <img class="foto-circular" src="../fotos/{{session()->all()['foto']}}" alt="Sua Imagem">


        <a href="/">Home</a>
        <a href="/cadastrar">Cadastrar Racha</a>
        <a href="/listagem">Listagem de rachas</a>
        <button type="submit">Logout</button>
    </form></div>
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
@if(empty($todosJogadores) == false)
@foreach($todosJogadores as $index => $jogador)
<div id="tela">
    <div id="cronometro"><p>O racha acaba em:</p>&nbsp<p id="cronometro_{{$index}}">{{$jogador[0]->horaFormatada}}</p>
    <p id="nomeDoRacha">Racha: <strong style="text-transform:uppercase;">{{$jogador[0]->nome_do_racha}}</strong></p>
    </div>
    <div id="tituloJogadores">Jogadores:</div>
    <br>
        @foreach($jogador as $jog)
            <div id="jogador"><img src="../fotos/{{basename($jog->foto)}}" alt="Sua Imagem"><br>      
                <div id="nomeJogador">{{$jog->nome}}</div>
                <br>
                <div id="posicaoJogador">{{$jog->posicao}}</div>
                <br>
                <form action="">
                    <button id="botao">Remover jogador</button>
                </form>
            </div>
        @endforeach
    <p id="quantidade">{{$jogador[0]->quantidade}} jogadores de {{$jogador[0]->quantidade_maxima_jogo}}</p> 

    </div>
    
    @endforeach
@endif
<script>

function atualizarCronometros() {
    @foreach($todosJogadores as $index => $jogador)
        var cronometroElement = document.getElementById('cronometro_{{$index}}');
        var tempoAtual = cronometroElement.innerText;

        var partesTempo = tempoAtual.split(':');
        var horas = parseInt(partesTempo[0]);

        var minutos = parseInt(partesTempo[1]);
 
        var segundos = parseInt(partesTempo[2]);

        segundos--;
        if (horas == 0 && minutos == 0 && segundos == 0) {
            window.location.reload(true);
        }
        else{
            if (segundos < 0) {
            segundos = 59;
            minutos--;
            if (minutos < 0) {
                minutos = 59;
                horas--;

            }
        }
        }


        var tempoRestante = (horas < 10 ? '0' : '') + horas + ':' +
                            (minutos < 10 ? '0' : '') + minutos + ':' +
                            (segundos < 10 ? '0' : '') + segundos;


        cronometroElement.innerText = tempoRestante ;
    @endforeach


    setTimeout(atualizarCronometros, 1000);
}

atualizarCronometros();

                document.addEventListener('DOMContentLoaded', function () {
                    if(document.querySelector('.notificacao')){
                        var notificacaoWrapper = document.querySelector('.notificacao');
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
                    }

        });
        document.addEventListener('DOMContentLoaded', function () {
            if(document.querySelector('.notificacao-cheia')){
                var notificacaoWrapper = document.querySelector('.notificacao-cheia');
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
            }
            
        });
        document.addEventListener('DOMContentLoaded', function () {
            var linkConvite = document.querySelectorAll('.linkConvite');
            linkConvite.forEach(function(element) {
        element.addEventListener('click', function() {
            var rachaToken = this.getAttribute('data-racha-token');
            var link = "localhost:8000/telaInvite/" + rachaToken;
            var inputTemporario = document.createElement('input');
            inputTemporario.value = link;
            document.body.appendChild(inputTemporario);
            inputTemporario.select();
            inputTemporario.setSelectionRange(0, 99999); // Para dispositivos móveis
            document.execCommand('copy');
            document.body.removeChild(inputTemporario);

            console.log(rachaToken);
            alert('O link de convite para o racha foi copiado com sucesso!');

            // Seu código para copiar o link para a área de transferência...
        });
    });
        })
    
        
</script>
</body>
</html>