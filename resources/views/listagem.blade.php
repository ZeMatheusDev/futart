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
<div class="back">
    <br><br> <br>
    <input type="hidden" id="inpSecreto" value='{{$list->racha_token}}'>
        <p>Racha: {{$list->nome_do_racha}}</p>
        <p>Quantidade de jogadores no racha: {{$list->quantidade}}</p>
        <p>Dia da semana do racha: {{$list->data_do_racha}}</p>
        <p>Dono do racha: {{$list->nome}}</p>
        <form action="{{asset('/listagemJogadores')}}" method="POST">
        @csrf
        <input type="hidden" name="racha_id_secreto" id="racha_id_secreto" value="{{$list->racha_id}}">
        <button id="listagemJogadores" type="submit">Lista dos jogadores do racha</button>
        </form>
        @if ($list->usuario_id == session()->all()['id']) 
        <button id="linkConvite">Criar link de convite para o racha</button>
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
    var linkConvite = document.getElementById('linkConvite');
    if(document.getElementById('inpSecreto')){
        var racha = document.getElementById('inpSecreto').value;
        var link = "localhost:8000/telaInvite/" + racha;

        linkConvite.addEventListener('click', function() {
            // Cria um elemento de input temporário
            var inputTemporario = document.createElement('input');
            inputTemporario.value = link;
            document.body.appendChild(inputTemporario);

            // Seleciona o conteúdo do input temporário
            inputTemporario.select();
            inputTemporario.setSelectionRange(0, 99999); // Para dispositivos móveis

            // Tenta copiar o conteúdo para a área de transferência
            document.execCommand('copy');

            // Remove o input temporário
            document.body.removeChild(inputTemporario);

            // Feedback opcional
            alert('O link de convite para o racha foi copiado com sucesso!');
        });
    }
    
        
</script>
</body>
</html>