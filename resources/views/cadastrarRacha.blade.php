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
    <a href="/listagem">Listagem de Rachas</a>
    <button type="submit">Logout</button>

    </form></div>
@endif
</div>
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
<form id="formulario" method="POST" action="{{ route('cadastrando') }}">
    @csrf
    <div id="cadastro">
        <div id="titulo">Cadastrar Racha</div>
        <div style="text-align: center; font-size:15px; font-weight: bold">Nome do racha:</div>
        <input type="text" required placeholder="nome do racha" id="nome" pattern="^[a-zA-Z0-9 ]+$" maxlength="50" name="nome">
        <div style="text-align: center; font-size:15px; font-weight: bold">Descrição do racha:</div>
        <textarea required placeholder="descricao" id="descricao" pattern="^[a-zA-Z0-9]+$" name="descricao" rows="4"></textarea><br>
        <div style="text-align: center; font-size:15px; font-weight: bold">Dia do racha:</div>
        
        <select required name="data" id="data">
            <option value="segunda">Segunda-feira</option>
            <option value="terca">Terça-feira</option>
            <option value="quarta">Quarta-feira</option>
            <option value="quinta">Quinta-feira</option>
            <option value="sexta">Sexta-feira</option>
            <option value="sabado">Sábado</option>
            <option value="domingo">Domingo</option>
        </select>      
        <br>
        <div style="text-align: center; font-size:15px; font-weight: bold">Hora de inicio do racha:</div>

        <input type="text" id="hora_inicio" name="hora_inicio" maxlength="5" placeholder="00:00" required>
        <div style="text-align: center; font-size:15px; font-weight: bold">Hora de fim do racha:</div>

        <input type="text" id="hora_fim" name="hora_fim" maxlength="5" placeholder="00:00" required>
        <button id="final" class="btn btn-secondary" type="submit">
            Cadastrar
        </button>
    </div>
</form>
<script>
      document.getElementById("hora_inicio").addEventListener("input", function () {
    formatarHora(this);
  });

  document.getElementById("hora_fim").addEventListener("input", function () {
    formatarHora(this);
  });

  document.getElementById("hora_fim").addEventListener("input", function () {
    verificacaoHora(this);
  });

function verificacaoHora(input){

}

  // Função para formatar a entrada como "HH:MM"
  function formatarHora(input) {
    var valor = input.value.replace(/\D/g, ""); // Remove caracteres não numéricos
    var inicio = (valor[0]+valor[1]);
    if(inicio > 23){
        input.value = (0+valor[0]+':'+valor[1])
    }
    else{
        if (valor.length > 2) {
        // Insere ":" após os dois primeiros caracteres
        valor = valor.slice(0, 2) + ":" + valor.slice(2);
        }

        // Limita a entrada a 5 caracteres
        valor = valor.slice(0, 5);

        input.value = valor;
        var fim = (valor[3]+valor[4]);
        if(fim > 59){
            var teste = input.value
            var separado = teste.split(':')
            separado[1] = '59';
            input.value = separado[0]+':'+separado[1]
        }
        document.addEventListener("click", function () {
        if(input.value.length < 5){
            if(input.value.length == 1){
                const valorHora = input.value[0];
                const valorCompleto = 0+valorHora+':'+0+0
                input.value = valorCompleto
            }
            if(input.value.length == 2){
                const valorCompleto = input.value[0]+input.value[1]+':'+0+0
                input.value = valorCompleto
            }
            if(input.value.length == 4){
                const valorCompleto = input.value[0]+input.value[1]+':'+0+input.value[3]
                input.value = valorCompleto
            }
        }
        });
        }
    
    }
    function reproduzirAudio() {
        var audio = document.getElementById('meuAudio');
        audio.play();
    }
    
    document.getElementById('formulario').addEventListener('submit', function (event) {
    // Verifica se o formulário é válido
    if (this.checkValidity()) {
        event.preventDefault(); // Impede o envio imediato do formulário
        reproduzirAudio(); // Chama a função para reproduzir o áudio

        setTimeout(function () {
            document.getElementById('formulario').submit();
        }, 1000); // Envia o formulário após 5 segundos
    }
}); 
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
</form>
</body>
</html>