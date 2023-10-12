<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="/telaEnvio.css">
</head>
<body>
@if(session('logado') == true)
<div id="links">
    <form style="display: inline;" action="{{ route('deslogar') }}" method="POST">
        @csrf
        <img class="foto-circular" src="{{ asset('fotos/' . session()->get('foto')) }}" alt="Sua Imagem">
        <a href="/">Home</a>
        <a href="/cadastrar">Cadastrar Racha</a>
        <a href="/listagem">Listagem de Rachas</a>
        <button type="submit">Logout</button>
    </form>
</div>
@endif
@if(session('error'))
<div style="text-align: center" class="alert alert-danger">
    {{ session('error') }}
</div>
@endif
<audio id="myAudio" controls>
    <source src="{{asset('../music.mp3')}}" type="audio/mpeg">
    Seu navegador não suporta o elemento de áudio.
</audio>
<div id="envio">
    @for ($i = 0; $i < 20; $i++)
    <label for="jogador_{{ $i }}">Selecione um jogador confirmado:</label>
    <select name="jogador_{{ $i }}" id="jogador_{{ $i }}" class="select-jogador">
        <option value="" disabled hidden>Selecione um jogador</option>
        @foreach ($todosJogadoresDoRacha as $jogador)
            <option value="{{ $jogador->id }}">{{ $jogador->nome }}</option>
        @endforeach
        <option value="convidado">Jogador Convidado</option>
        <option value="" selected>Sem jogador</option>
    </select>
    <br><br>
    @endfor
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select-jogador').change(function() {
            var selectedValues = [];
            $('.select-jogador').find('option').prop('disabled', false);
            $('.select-jogador').each(function() {
                var selectedValue = $(this).val();
                if (selectedValue) {
                    selectedValues.push(selectedValue);
                }
            });
            $('.select-jogador').find('option[value="convidado"]').prop('disabled', false);
            $('.select-jogador').find('option').each(function() {
                var optionValue = $(this).val();
                if (optionValue !== "convidado" && selectedValues.indexOf(optionValue) !== -1) {
                    $(this).prop('disabled', true);
                }
            });
        });
    });
</script>
</body>
</html>