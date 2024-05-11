<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<link rel="stylesheet" type="text/css" href="/home.css">

</head>
<body>

    @if(session('logado') == false)

    <div id="linksDeslogado">
        <a href="/">Home</a>
        <a href="/logar">Login</a>
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
    <div style="text-align: center" class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
@if(session('success'))
<div style="text-align: center" class="alert alert-success">
    {{ session('success') }}
</div>
@endif
<audio id="audioBrasil" style="display: none" controls>
    <source src="../brasil.mp3" type="audio/mpeg">
    Seu navegador não suporta o elemento de áudio.
  </audio>
<audio id="myAudio" controls>
    <source src="../music.mp3" type="audio/mpeg">
    Seu navegador não suporta o elemento de áudio.
  </audio>
  <form method="POST" action="{{ route('criandoConta') }}" enctype="multipart/form-data">
    @csrf
    <div id="cadastro">
        <div id="titulo">Criar conta</div>
        <div style="text-align: center; font-size:15px; font-weight: bold">Sua foto:</div>
        <input type="file" class="btn" required id="foto" onclick="reproduzirAudio()" style="display: none" name="foto" accept=".jpg, .jpeg, .png">
        <button type="button" class="btn btn-primary" onclick="selecionarFoto()">Upload de foto</button>
        <img id="preview" src="" alt="Preview da imagem" style="display: none; max-width: 200px; max-height: 200px;">
        <br>
        <div style="text-align: center; font-size:15px; font-weight: bold">Login:</div>
        <input type="text" required placeholder="usuario" id="usuario" pattern="^[a-zA-Z0-9]+$" maxlength="20" name="usuario">
        <div style="text-align: center; font-size:15px; font-weight: bold">Senha:</div>
        <input type="text" required placeholder="senha" id="senha" pattern="^[a-zA-Z0-9]+$" maxlength="20" name="senha">
        <div style="text-align: center; font-size:15px; font-weight: bold">Email:</div>
        <input type="email" required placeholder="email" id="email" name="email">
        <div style="text-align: center; font-size:15px; font-weight: bold">Sua posicao:</div>
        <select name="posicao" id="posicao">
            <option value="goleiro">Goleiro</option>
            <option value="zagueiro">Zagueiro</option>
            <option value="lateral">Lateral</option>
            <option value="volante">Volante</option>
            <option value="meio-campista">Meio-campista</option>
            <option value="atacante">Atacante</option>
        </select><br>
        <div style="text-align: center; font-size:15px; font-weight: bold">Seu nome:</div>
        <input type="text" required placeholder="Nome" id="nome" pattern="^[a-zA-Z0-9 ]+$" maxlength="50" name="nome">
        <button class="btn btn-secondary" type="submit">
            Criar
        </button>

    </div>

    </form>

    <script>
        function reproduzirAudio() {
            var audio = document.getElementById('audioBrasil');
            audio.play();
        }
        function exibirPreview() {
        var input = document.getElementById('foto');
        var preview = document.getElementById('preview');

        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                preview.style.display = 'flex';
                preview.src = e.target.result;
            };

            reader.readAsDataURL(input.files[0]);
        } else {
            preview.style.display = 'none';
            preview.src = '';
        }
    }

    // Adicione um event listener para chamar a função quando um arquivo for selecionado
    document.getElementById('foto').addEventListener('change', exibirPreview);

    function selecionarFoto(){
        var upload = document.getElementById('foto');
        upload.click();
    }
    </script>
</body>
</html>