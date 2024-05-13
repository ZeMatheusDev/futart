<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<link rel="stylesheet" type="text/css" href="/listagem.css">
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
        <img class="foto-circular" src="../fotos/{{session()->all()['foto']}}" alt="Sua Imagem">


        <a href="/">Home</a>
        <a href="/cadastrar">Cadastrar Racha</a>
        <a href="">Rachas em andamento</a>
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
@foreach ($listagem as $list)
@if($list->ativo == null || $list->ativo == 1)
<div class="back">
    
    @if (isset($list->tipoDeConfirmacao))
    @if ($list->tipoDeConfirmacao == 'cancelado')
        <p class="btn btn-danger">
        O racha hoje foi <strong style="text-transform: uppercase;">{{$list->tipoDeConfirmacao}}</strong>
    </p>
    <form action="{{asset('../alterarConfirmacao')}}" method="POST">
        @csrf
    <input type="hidden" name="racha_id_secreto" id="" value='{{$list->racha_token}}'>
    <button class="btn btn-success" style="margin: 0 auto">Alterar para confirmado</button>
    </form>
    <br>    @endif
    
    @endif
    <input type="hidden" id="inpSecreto" value='{{$list->racha_token}}'>
    @if (isset($list->passouDaHora))
    @if ($list->passouDaHora == false)
    @if (isset($list->espera))
    @if ($list->espera == true)
    @if ($list->preferencia == 1)
    <p class="btn btn-warning" style="color:black">Você está na fila para o racha...
        <a href="{{ route('duvidaDiarista') }}">
            <img src="{{ asset('quest.png') }}" style="height: 40px; width: 50px; margin-top: -2px;" class="float-right">
        </a>
    </p> 
    @endif
    @if ($list->preferencia == 0)
    <p class="btn btn-warning" style="color:black">Você está na fila para o racha...
        <a href="{{ route('duvidaRachaSemMensalista') }}">
            <img src="{{ asset('quest.png') }}" style="height: 40px; width: 50px; margin-top: -2px;" class="float-right">
        </a>
    </p> 
    @endif
    @endif
    @endif
    @endif
    @endif
    @if (isset($list->passouDaHora))
    @if ($list->passouDaHora == false)
    @if (isset($list->confirmado))
    @if ($list->confirmado == true)
        <p class="btn btn-success" style="color:black">Você está CONFIRMADO no racha!</p>
    @endif
    @endif
    @endif
    @endif
        <p>Racha: {{$list->nome_do_racha}}</p>
        <p>Quantidade de jogadores no racha: {{$list->quantidade}}</p>
        <p>Dia da semana do racha: {{$list->data_do_racha}}</p>
        <p>Dono do racha: {{$list->nome}}</p>
        <form action="{{asset('/listagemJogadores')}}" method="POST">
        @csrf
        <input type="hidden" name="racha_id_secreto" id="racha_id_secreto" value="{{$list->racha_id}}">
        <button id="listagemJogadores" type="submit">Lista dos jogadores do racha</button>
        </form>
        <br>
        <form action="{{asset('sairDoRacha')}}" method="POST">
            @csrf
            <input type="hidden" name="racha_id_secreto" id="racha_id_secreto" value="{{$list->racha_id}}">
            <input type="hidden" name="usuario_id" id="usuario_id" value="{{$list->usuario_id}}">
            <button class="btn btn-danger" type="submit">Sair do racha</button>
            <br><br>    
            </form>
        @if (isset($list->passouDaHora))
        @if ($list->passouDaHora == false)
        @if (isset($list->confirmar))
            @if ($list->confirmar == true)
            <form action="{{asset('../confirmarPresenca')}}" method="POST">
                @csrf
                <input type="hidden" name="racha_id_secreto" id="inpSecreto" value='{{$list->racha_token}}'>
                <button class="btn btn-primary">Confirmar presença</button>
            </form>
            @endif
            @if ($list->confirmar == false)
            <form action="{{asset('../cancelarPresenca')}}" method="POST">
                @csrf
                <input type="hidden" name="racha_id_secreto" id="inpSecreto" value='{{$list->racha_token}}'>
                <button class="btn btn-danger">Cancelar presença</button>
            </form>
            @endif
        @endif
        @endif
        @endif
        <br><br>
        @if ($list->usuario_id == session()->all()['id']) 
        <button class="linkConvite" id="linkConvite" data-racha-token="{{$list->racha_token}}">Criar link de convite para o racha</button>
        <form action="{{asset('/enviarConvite')}}" method="POST">
            @csrf
        <div id="envio">
            <input type="hidden" id="rachaToken" name="rachaToken" value='{{$list->racha_token}}'>
            Enviar solicitação por email: <input id="email" name="email" type="email" required>
        </div>
        <button class="btn btn-success">Enviar solicitação</button>
        </form>
        @endif
</div>
@endif
@endforeach
<script>
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