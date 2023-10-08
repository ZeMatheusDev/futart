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
        <button id="listagemJogadores" type="submit">Lista dos jogadores do racha</button> <br><br>
        </form>
        @if ($list->usuario_id == session()->all()['id']) 
        <button id="linkConvite" class="btn btn-primary">Criar link de convite para o racha</button>
        @endif
</div>
@endforeach
<script>
    var linkConvite = document.getElementById('linkConvite');
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
</script>
</body>
</html>