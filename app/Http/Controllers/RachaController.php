<?php

namespace App\Http\Controllers;
use App\Models\Racha;
use App\Models\Conta_racha;
use App\Models\Convite;
use App\Models\Fila_racha;
use App\Models\Jogadores_racha_dia;
use App\Models\RachaConfirmacao;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class RachaController extends Controller
{
    public function cadastrar(){
        $notificacoes = DB::table('convite')
        ->where('convidado_id', '=', session()->all()['id'])
        ->join('conta', 'conta.id', '=', 'dono_id')
        ->join('racha', 'racha.id', '=', 'racha_id')
        ->get();
        $verificarRachaHoje = DB::table('racha')
            ->where('usuario_id', '=', session()->all()['id'])
            ->get();
            if($verificarRachaHoje->isEmpty() == false){
                foreach($verificarRachaHoje as $verificar){
                    $diaHoje = Carbon::now()->format('d/m/Y');
                    $verificarSeJaConfirmou = DB::table('racha_confirmacao')
                    ->where('racha_id', '=', $verificar->id)
                    ->where('confirmacao', '=', 1)
                    ->where('data_dia_racha', '=', $diaHoje)
                    ->get();
                    if($verificarRachaHoje->isEmpty() == false){
                        foreach($verificarRachaHoje as $verificar){
                            $diaHoje = Carbon::now()->format('d/m/Y');
                            $verificarSeJaConfirmou = DB::table('racha_confirmacao')
                            ->where('racha_id', '=', $verificar->id)
                            ->where('data_dia_racha', '=', $diaHoje)
                            ->get();
                            if($verificarSeJaConfirmou->isEmpty() == true){
                                $hoje = date('N'); 
                                switch($hoje){
                                    case 1:
                                        $diaSemana = 'segunda';
                                        break;
                                    case 2:
                                        $diaSemana = 'terca';
                                        break;
                                    case 3:
                                        $diaSemana = 'quarta';
                                        break;
                                    case 4:
                                        $diaSemana = 'quinta';
                                        break;
                                    case 5:
                                        $diaSemana = 'sexta';
                                        break;
                                    case 6:
                                        $diaSemana = 'sabado';
                                        break;
                                    case 7:
                                        $diaSemana = 'domingo';
                                        break;
                                    default:
                                        $diaSemana = 'desconhecido';   
                                    }
                                $diaDoRacha = $verificar->data_do_racha;
                                if($diaDoRacha == $diaSemana && $verificar->ativo == 1){
                                    $verificacaoDosRachasHoje[] = $verificar;
                                }
                                else{
                                }
                            }
                            else{
        
                            }
                        }
                        if(isset($verificacaoDosRachasHoje)){
                            return view('/confirmarRacha')->with('verificacaoDosRachasHoje', $verificacaoDosRachasHoje);
                        }
                        else{
                            return view('cadastrarRacha')->with('notificacoes', $notificacoes);
        
                        }
                    }
                }
            }

        return view('cadastrarRacha')->with('notificacoes', $notificacoes);
    }

    public function aceitar(Request $request){
        $verificacao = DB::table('Conta_racha')
        ->where('usuario_id', '=', $request->usuario_id)
        ->where('racha_id', '=', $request->racha_id)
        ->get();
        $verificarConvite = DB::table('convite')
        ->where('convidado_id', '=', session()->all()['id'])
        ->get();
        if($verificacao->isEmpty() == true){
            $contaRacha = new Conta_racha();
            $contaRacha->usuario_id = session()->all()['id']; 
            $contaRacha->racha_id = $request->racha_id;
            $contaRacha->mensalista = false;
            $contaRacha->save();
            if($verificarConvite->isEmpty() == false){
                DB::table('convite')->where('convidado_id', '=', session()->all()['id'])->where('racha_id', '=', $request->racha_id)->delete();
            }
            DB::table('racha')
            ->where('id', $request->racha_id)
            ->increment('quantidade', 1);
            $listagem = DB::table('racha')
            ->where('usuario_id', '=', session()->all()['id'])
            ->join('conta', 'conta.id', '=', 'usuario_id')
            ->get();
            $notificacoes = DB::table('convite')
            ->where('convidado_id', '=', session()->all()['id'])
            ->join('conta', 'conta.id', '=', 'dono_id')
            ->join('racha', 'racha.id', '=', 'racha_id')
            ->get();
            return view('home')?->with(['notificacoes' => $notificacoes, 'success' => 'Você entrou no racha!']);

        }
        else{
            $notificacoes = DB::table('convite')
            ->where('convidado_id', '=', session()->all()['id'])
            ->join('conta', 'conta.id', '=', 'dono_id')
            ->join('racha', 'racha.id', '=', 'racha_id')
            ->get();
            return redirect()->back()->with(['error' => 'Você já está cadastrado nesse racha!', 'notificacoes' => $notificacoes]);
        }
    }

    public function confirmarRacha(Request $request){
        $confirmarRacha = new RachaConfirmacao();
        $confirmarRacha->data_dia_racha = Carbon::now()->format('d/m/Y');
        $confirmarRacha->racha_id = $request->racha_id;
        $confirmarRacha->confirmacao = true;
        $confirmarRacha->created_at = now();
        $confirmarRacha->updated_at = now();
        $confirmarRacha->save();
        return redirect()->back()->with('success', 'Confirmado!');
    }

    public function cancelarRacha(Request $request){
        $confirmarRacha = new RachaConfirmacao();
        $confirmarRacha->data_dia_racha = Carbon::now()->format('d/m/Y');
        $confirmarRacha->racha_id = $request->racha_id;
        $confirmarRacha->confirmacao = false;
        $confirmarRacha->created_at = now();
        $confirmarRacha->updated_at = now();
        $confirmarRacha->save();
        return redirect()->back()->with('success', 'cancelado!');

    }

    public function recusarRacha(Request $request){
        DB::table('convite')->where('convidado_id', '=', session()->all()['id'])->where('racha_id', '=', $request->racha_id)->delete();
        $listagem = DB::table('racha')
        ->where('usuario_id', '=', session()->all()['id'])
        ->join('conta', 'conta.id', '=', 'usuario_id')
        ->get();
        $notificacoes = DB::table('convite')
        ->where('convidado_id', '=', session()->all()['id'])
        ->join('conta', 'conta.id', '=', 'dono_id')
        ->join('racha', 'racha.id', '=', 'racha_id')
        ->get();
        return view('/listagem')->with(['listagem' => $listagem, 'notificacoes' => $notificacoes]);
    }

    public function enviarConvite(Request $request){
        $verificacao = DB::table('conta')
        ->where('email', '=', $request->email)
        ->get();
        $verificarRachaDono = DB::table('racha')
        ->where('racha_token', '=', $request->rachaToken)
        ->join('conta', 'conta.id', '=', 'racha.usuario_id')
        ->get();
        $verificarRacha = DB::table('racha')
        ->where('racha_token', '=', $request->rachaToken)
        ->first();
        $verificarSeJaEstaNoRacha = DB::table('conta_racha')
        ->where('racha_id', '=', $verificarRacha->id)
        ->where('usuario_id', '=', $verificacao[0]->id)
        ->first();
        if(isset($verificarSeJaEstaNoRacha)){
            return redirect()->back()->with('error', 'Esse jogador já está no racha!');
        }
        else{
            if($verificarRachaDono[0]->email != $request->email){
                if($verificacao->isEmpty() == false){
                    $verificarSolicitacoes = DB::table('convite')
                    ->where('convidado_id', '=', $verificacao[0]->id)
                    ->get();
                    if($verificarSolicitacoes->isEmpty()){
                        $rachaId = DB::table('racha')
                        ->where('racha_token', '=', $request->rachaToken)
                        ->select('id', 'usuario_id')
                        ->get();
                        $convite = new Convite();
                        $convite->racha_id = $rachaId[0]->id;
                        $convite->dono_id = $rachaId[0]->usuario_id;
                        $convite->convidado_id = $verificacao[0]->id;
                        $convite->save();
                        return redirect()->back()->with('success', 'Solicitação enviada com sucesso para o ' . $verificacao[0]->nome. '!');
                    }
                    else{
                        return redirect()->back()->with('error', 'Esse usuário ja foi convidado para o racha!');
                    }
                }
                else{
                    return redirect()->back()->with('error', 'Email não encontrado no banco de dados!');
                }
            }
            else{
                return redirect()->back()->with('error', 'O dono do racha não pode ser convidado!');
            }
        }

    }

    public function telaInvite(Request $request, $racha_token)
    {
        $informacoes = DB::table('Conta')
        ->where('id', '=', session()->all()['id'])
        ->get();

        $donoDoRacha = DB::table('racha')
        ->where('racha_token', '=', $racha_token)
        ->join('conta', 'conta.id', '=', 'racha.usuario_id')
        ->first();

        $informacoesRacha = DB::table('Racha')
        ->where('racha_token', '=', $racha_token)
        ->get();
            $dadosCombinados = [
            'usuario' => $informacoes[0],
            'racha' => $informacoesRacha[0],
            'dono' => $donoDoRacha,
        ];
        $verificarRachaHoje = DB::table('racha')
            ->where('usuario_id', '=', session()->all()['id'])
            ->get();
            if($verificarRachaHoje->isEmpty() == false){
                foreach($verificarRachaHoje as $verificar){
                    $diaHoje = Carbon::now()->format('d/m/Y');
                    $verificarSeJaConfirmou = DB::table('racha_confirmacao')
                    ->where('racha_id', '=', $verificar->id)
                    ->where('confirmacao', '=', 1)
                    ->where('data_dia_racha', '=', $diaHoje)
                    ->get();
                    if($verificarRachaHoje->isEmpty() == false){
                        foreach($verificarRachaHoje as $verificar){
                            $diaHoje = Carbon::now()->format('d/m/Y');
                            $verificarSeJaConfirmou = DB::table('racha_confirmacao')
                            ->where('racha_id', '=', $verificar->id)
                            ->where('data_dia_racha', '=', $diaHoje)
                            ->get();
                            if($verificarSeJaConfirmou->isEmpty() == true){
                                $hoje = date('N'); 
                                switch($hoje){
                                    case 1:
                                        $diaSemana = 'segunda';
                                        break;
                                    case 2:
                                        $diaSemana = 'terca';
                                        break;
                                    case 3:
                                        $diaSemana = 'quarta';
                                        break;
                                    case 4:
                                        $diaSemana = 'quinta';
                                        break;
                                    case 5:
                                        $diaSemana = 'sexta';
                                        break;
                                    case 6:
                                        $diaSemana = 'sabado';
                                        break;
                                    case 7:
                                        $diaSemana = 'domingo';
                                        break;
                                    default:
                                        $diaSemana = 'desconhecido';   
                                    }
                                $diaDoRacha = $verificar->data_do_racha;
                                if($diaDoRacha == $diaSemana && $verificar->ativo == 1){
                                    $verificacaoDosRachasHoje[] = $verificar;
                                }
                                else{
                                }
                            }
                            else{
        
                            }
                        }
                        if(isset($verificacaoDosRachasHoje)){
                            return view('/confirmarRacha')->with('verificacaoDosRachasHoje', $verificacaoDosRachasHoje);
                        }
                        else{
                            return view('telaInvite')->with('dados', $dadosCombinados);        
                        }
                    }
                }
            }
        return view('telaInvite')->with('dados', $dadosCombinados);
    }

    public function cadastrando(Request $request){
        $vip = DB::table('conta')
        ->where('id', session()->all()['id'])
        ->pluck('vip')
        ->first();
        if($vip == 0){
            $verificacao = DB::table('racha')->where('usuario_id', '=', session()->all()['id'])->get();
            if($verificacao->isEmpty() == true){
                $racha = new Racha();
                $racha->nome_do_racha = $request->nome;
                if($request->mensalista_preferencia == null){
                    $racha->mensalista_preferencia = 0;
                }
                elseif($request->mensalista_preferencia == 1){
                    $racha->mensalista_preferencia = 1;
                }
                $racha->descricao = $request->descricao;
                $racha->quantidade = 1;
                $token = Str::random(32);
                $racha->racha_token = $token;
                $racha->data_do_racha = $request->data;
                $racha->hora_do_racha = $request->hora_inicio;
                $racha->final_do_racha = $request->hora_fim;
                $racha->quantidade_maxima_jogo = $request->quantidade_maxima_jogo;
                $racha->ativo = 1;
                $racha->usuario_id = session()->all()['id'];
                $racha->save();
                $contaRacha = new Conta_racha();
                $contaRacha->usuario_id = session()->all()['id']; 
                $contaRacha->racha_id = $racha->id;
                if($request->mensalista_preferencia == null){
                    $contaRacha->mensalista = 0;
                }
                elseif($request->mensalista_preferencia == 1){
                    $contaRacha->mensalista = 1;
                }
                $contaRacha->save();

                return redirect()->back()->with('success', 'Cadastrado feito com sucesso!');
            };
            return redirect()->back()->with('error', 'Voce ja possui um racha cadastrado como FREE ACCOUNT!');
        }
        else{
            $verificarQuantidade = DB::table('racha')
            ->where('usuario_id', session()->all()['id'])
            ->get();
            if(count($verificarQuantidade) < 5){
            $racha = new Racha();
            $racha->nome_do_racha = $request->nome;
            if($request->mensalista_preferencia == null){
                $racha->mensalista_preferencia = 0;
            }
            elseif($request->mensalista_preferencia == 1){
                $racha->mensalista_preferencia = 1;
            }
            $racha->descricao = $request->descricao;
            $racha->quantidade = 1;
            $racha->data_do_racha = $request->data;
            $racha->hora_do_racha = $request->hora_inicio;
            $racha->final_do_racha = $request->hora_fim;
            $token = Str::random(32);
            $racha->racha_token = $token;
            $racha->quantidade_maxima_jogo = $request->quantidade_maxima_jogo;  
            $racha->usuario_id = session()->all()['id'];
            $racha->ativo = 1;
            $racha->created_at = now();
            $racha->updated_at = now();
            
            $racha->save();
            $racha->refresh();
            $contaRacha = new Conta_racha();
            $contaRacha->usuario_id = session()->all()['id']; 
            $contaRacha->racha_id = $racha->id;
            if($request->mensalista_preferencia == null){
                $contaRacha->mensalista = 0;
            }
            elseif($request->mensalista_preferencia == 1){
                $contaRacha->mensalista = 1;
            }
            $contaRacha->save();
            return redirect()->back()->with('success', 'Cadastrado feito com sucesso!');
        }
        else{
            return redirect()->back()->with('error', 'Voce ja possui o limite de rachas cadastrados!');

        }
    }
    }

    public function listagem(){
        $listagem = DB::table('Conta_racha')
        ->where('Conta_racha.usuario_id', '=', session()->all()['id'])
        ->join('racha', 'racha.id', '=', 'racha_id')
        ->join('conta', 'conta.id', '=', 'racha.usuario_id')
        ->get();
    $notificacoes = DB::table('convite')
        ->where('convidado_id', '=', session()->all()['id'])
        ->join('conta', 'conta.id', '=', 'dono_id')
        ->join('racha', 'racha.id', '=', 'racha_id')
        ->get();
        foreach($listagem as $list){
            $hoje = date('N');
            switch($hoje){
                case 1:
                    $diaSemana = 'segunda';
                    break;
                case 2:
                    $diaSemana = 'terca';
                    break;
                case 3:
                    $diaSemana = 'quarta';
                    break;
                case 4:
                    $diaSemana = 'quinta';
                    break;
                case 5:
                    $diaSemana = 'sexta';
                    break;
                case 6:
                    $diaSemana = 'sabado';
                    break;
                case 7:
                    $diaSemana = 'domingo';
                    break;
                default:
                    $diaSemana = 'desconhecido';   
                }
                if($list->data_do_racha == $diaSemana){
                    $diaHoje = Carbon::now()->format('d/m/Y');
                    $verificarSeJaConfirmouRacha = DB::table('racha_confirmacao')
                    ->where('racha_id', '=', $list->racha_id)
                    ->where('data_dia_racha', '=', $diaHoje)
                    ->get();
                    if($verificarSeJaConfirmouRacha->isEmpty() == false && $verificarSeJaConfirmouRacha[0]->confirmacao == 1){
                        $verificarJogadorConfirmadoRacha = DB::table('jogadores_racha_dia')
                        ->where('jogador_id', '=', session()->all()['id'])
                        ->where('racha_id', '=', $list->racha_id)
                        ->where('racha_dia', '=', $diaHoje)
                        ->get();
                        $verificarJodaresFila = DB::table('fila_racha')
                        ->where('jogador_id', '=', session()->all()['id'])
                        ->where('racha_id', '=', $list->racha_id)
                        ->where('racha_dia', '=', $diaHoje)
                        ->get();
                        $verificarSeRachaTemPreferencia = DB::table('racha')
                        ->where('id', $list->racha_id)
                        ->first();
                        $horaAtual = Carbon::now();
                        $horaFormatada = $horaAtual->format('H:i:s.u');

                        if($list->hora_do_racha < $horaFormatada){
                            $list->passouDaHora = true;
                        }
                        else{
                            $list->passouDaHora = false;
                        }
                        if($verificarSeRachaTemPreferencia->mensalista_preferencia == 0){
                            $list->preferencia = 0;
                        }
                        elseif($verificarSeRachaTemPreferencia->mensalista_preferencia == 1){
                            $list->preferencia = 1;
                        }
                        if($verificarJogadorConfirmadoRacha->isEmpty() == false){
                            $list->confirmado = true;
                        }
                        else{
                            $list->confirmado = false;
                        }
                        if($verificarJodaresFila->isEmpty() == false){
                            $list->espera = true;
                        }
                        else{
                            $list->espera = false;
                        }
                        if($verificarJogadorConfirmadoRacha->isEmpty() && $verificarJodaresFila->isEmpty()){
                            $list->confirmar = true;
                            $list->tipoDeConfirmacao = 'confirmado';
                        }
                        else{
                            $list->confirmar = false;
                            $list->tipoDeConfirmacao = 'confirmado';
                        }
                    }
                    elseif($verificarSeJaConfirmouRacha->isEmpty() == false && $verificarSeJaConfirmouRacha[0]->confirmacao == 0){
                            $list->tipoDeConfirmacao = 'cancelado';
                    }
                }
        }

        $verificarRachaHoje = DB::table('racha')
            ->where('usuario_id', '=', session()->all()['id'])
            ->get();
            if($verificarRachaHoje->isEmpty() == false){
                foreach($verificarRachaHoje as $verificar){
                    $diaHoje = Carbon::now()->format('d/m/Y');
                    $verificarSeJaConfirmou = DB::table('racha_confirmacao')
                    ->where('racha_id', '=', $verificar->id)
                    ->where('confirmacao', '=', 1)
                    ->where('data_dia_racha', '=', $diaHoje)
                    ->get();
                    if($verificarRachaHoje->isEmpty() == false){
                        foreach($verificarRachaHoje as $verificar){
                            $diaHoje = Carbon::now()->format('d/m/Y');
                            $verificarSeJaConfirmou = DB::table('racha_confirmacao')
                            ->where('racha_id', '=', $verificar->id)
                            ->where('data_dia_racha', '=', $diaHoje)
                            ->get();
                            if($verificarSeJaConfirmou->isEmpty() == true){
                                $hoje = date('N'); 
                                switch($hoje){
                                    case 1:
                                        $diaSemana = 'segunda';
                                        break;
                                    case 2:
                                        $diaSemana = 'terca';
                                        break;
                                    case 3:
                                        $diaSemana = 'quarta';
                                        break;
                                    case 4:
                                        $diaSemana = 'quinta';
                                        break;
                                    case 5:
                                        $diaSemana = 'sexta';
                                        break;
                                    case 6:
                                        $diaSemana = 'sabado';
                                        break;
                                    case 7:
                                        $diaSemana = 'domingo';
                                        break;
                                    default:
                                        $diaSemana = 'desconhecido';   
                                    }
                                $diaDoRacha = $verificar->data_do_racha;
                                if($diaDoRacha == $diaSemana && $verificar->ativo == 1){
                                    $verificacaoDosRachasHoje[] = $verificar;
                                }
                                else{
                                }
                            }
                            else{
        
                            }
                        }
                        if(isset($verificacaoDosRachasHoje)){
                            return view('/confirmarRacha')->with('verificacaoDosRachasHoje', $verificacaoDosRachasHoje);
                        }
                        else{
                    return view('listagem')->with(['listagem' => $listagem, 'notificacoes' => $notificacoes]);
        
                        }
                    }
                }
            }
        return view('listagem')->with(['listagem' => $listagem, 'notificacoes' => $notificacoes]);
    }

    public function alterarConfirmacao(Request $request){
        $verificarQualRacha = DB::table('racha')
        ->where('racha_token', '=', $request->racha_id_secreto)
        ->first();
        if($verificarQualRacha){
            RachaConfirmacao::where('racha_id', '=', $verificarQualRacha->id)->update(['confirmacao' => 1]);
            return redirect()->back()->with('success', 'Racha confirmado!');
        }
        return redirect()->back();
    }

    public function duvidaDiarista(){
        $notificacoes = DB::table('convite')
        ->where('convidado_id', '=', session()->all()['id'])
        ->join('conta', 'conta.id', '=', 'dono_id')
        ->join('racha', 'racha.id', '=', 'racha_id')
        ->get();
        return view('duvidaDiarista')->with('notificacoes', $notificacoes);
    }

    protected function duvidaRachaSemMensalista(){
        $notificacoes = DB::table('convite')
        ->where('convidado_id', '=', session()->all()['id'])
        ->join('conta', 'conta.id', '=', 'dono_id')
        ->join('racha', 'racha.id', '=', 'racha_id')
        ->get();
        return view('duvidaRachaSemMensalista')->with('notificacoes', $notificacoes);
    }

    public function confirmarPresenca(Request $request){
        $verificarQualRacha = DB::table('racha')
        ->where('racha_token', '=', $request->racha_id_secreto)
        ->first();
        $diaHoje = Carbon::now()->format('d/m/Y');
        $verificarMensalistaRacha = DB::table('conta_racha')
        ->where('racha_id', '=', $verificarQualRacha->id)
        ->where('usuario_id', '=', session()->all()['id'])
        ->first();
        if($verificarQualRacha->mensalista_preferencia == 1){
            if($verificarMensalistaRacha->mensalista == 1){
                $listaRacha = new Jogadores_racha_dia();
                $listaRacha->jogador_id = session()->all()['id'];
                $listaRacha->racha_id = $verificarQualRacha->id;
                $listaRacha->racha_dia = $diaHoje;
                $listaRacha->created_at = now();
                $listaRacha->updated_at = now();
                $listaRacha->save();
                return redirect()->route('listagem')->with('success', 'Você confirmou sua presença!');

            }
            else{
                $listaRacha = new Fila_racha();
                $listaRacha->jogador_id = session()->all()['id'];
                $listaRacha->racha_id = $verificarQualRacha->id;
                $listaRacha->racha_dia = $diaHoje;
                $listaRacha->mensalista = $verificarMensalistaRacha->mensalista;
                $listaRacha->created_at = now();
                $listaRacha->updated_at = now();
                $listaRacha->save();
                return redirect()->route('listagem')->with('success', 'Você confirmou sua presença!');
            }
        }
        else{
            $listaRacha = new Fila_racha();
            $listaRacha->jogador_id = session()->all()['id'];
            $listaRacha->racha_id = $verificarQualRacha->id;
            $listaRacha->racha_dia = $diaHoje;
            $listaRacha->mensalista = $verificarMensalistaRacha->mensalista;
            $listaRacha->created_at = now();
            $listaRacha->updated_at = now();
            $listaRacha->save();
            return redirect()->route('listagem')->with('success', 'Você confirmou sua presença!');

        }
        

    }

    public function cancelarPresenca(Request $request){
        $verificarQualRacha = DB::table('racha')
        ->where('racha_token', '=', $request->racha_id_secreto)
        ->first();
        $diaHoje = Carbon::now()->format('d/m/Y');
        $testeFila = DB::table('fila_racha')
        ->where('racha_dia', '=', $diaHoje)
        ->where('jogador_id', '=', session()->all()['id'])
        ->where('racha_id', '=', $verificarQualRacha->id)
        ->get();
        $testeConfirmacao = DB::table('jogadores_racha_dia')
        ->where('racha_dia', '=', $diaHoje)
        ->where('jogador_id', '=', session()->all()['id'])
        ->where('racha_id', '=', $verificarQualRacha->id)
        ->get();
        if($testeFila->isEmpty() == false) {
            Fila_racha::find($testeFila[0]->id)->delete();
        }
        if($testeConfirmacao->isEmpty() == false) {
            Jogadores_racha_dia::find($testeConfirmacao[0]->id)->delete();
        }
        return redirect()->route('listagem')->with('success', 'Voce saiu da lista do racha!');
    }

    public function listagemJogadores(Request $request){
        $jogadoresNoRacha = DB::table('conta_racha')
        ->where('racha_id', '=', $request->racha_id_secreto)
        ->get();
        $notificacoes = DB::table('convite')
        ->where('convidado_id', '=', session()->all()['id'])
        ->join('conta', 'conta.id', '=', 'dono_id')
        ->join('racha', 'racha.id', '=', 'racha_id')
        ->get();
        foreach($jogadoresNoRacha as $jogador){
            $jogadorSolo = DB::table('conta')
                ->where('conta.id', '=', $jogador->usuario_id)
                ->join('conta_racha', function ($join) use ($request) { 
                    $join->on('usuario_id', '=', 'conta.id')
                         ->where('racha_id', '=', $request->racha_id_secreto);
                })
                ->get();
                $verificarDonoDoRacha = DB::table('racha')
                ->where('id', $jogador->racha_id)
                ->first();
                if($verificarDonoDoRacha->usuario_id == session()->all()['id']){
                    $jogadorSolo[0]->donoDoRacha = true;
                }
                else{
                    $jogadorSolo[0]->donoDoRacha = false;
                }
            $jogadoreSeparados[] = $jogadorSolo[0];
        }

        $verificarRachaHoje = DB::table('racha')
            ->where('usuario_id', '=', session()->all()['id'])
            ->get();
            if($verificarRachaHoje->isEmpty() == false){
                foreach($verificarRachaHoje as $verificar){
                    $diaHoje = Carbon::now()->format('d/m/Y');
                    $verificarSeJaConfirmou = DB::table('racha_confirmacao')
                    ->where('racha_id', '=', $verificar->id)
                    ->where('confirmacao', '=', 1)
                    ->where('data_dia_racha', '=', $diaHoje)
                    ->get();
                    if($verificarRachaHoje->isEmpty() == false){
                        foreach($verificarRachaHoje as $verificar){
                            $diaHoje = Carbon::now()->format('d/m/Y');
                            $verificarSeJaConfirmou = DB::table('racha_confirmacao')
                            ->where('racha_id', '=', $verificar->id)
                            ->where('data_dia_racha', '=', $diaHoje)
                            ->get();
                            if($verificarSeJaConfirmou->isEmpty() == true){
                                $hoje = date('N'); 
                                switch($hoje){
                                    case 1:
                                        $diaSemana = 'segunda';
                                        break;
                                    case 2:
                                        $diaSemana = 'terca';
                                        break;
                                    case 3:
                                        $diaSemana = 'quarta';
                                        break;
                                    case 4:
                                        $diaSemana = 'quinta';
                                        break;
                                    case 5:
                                        $diaSemana = 'sexta';
                                        break;
                                    case 6:
                                        $diaSemana = 'sabado';
                                        break;
                                    case 7:
                                        $diaSemana = 'domingo';
                                        break;
                                    default:
                                        $diaSemana = 'desconhecido';   
                                    }
                                $diaDoRacha = $verificar->data_do_racha;
                                if($diaDoRacha == $diaSemana && $verificar->ativo == 1){
                                    $verificacaoDosRachasHoje[] = $verificar;
                                }
                                else{
                                }
                            }
                            else{
        
                            }
                        }
                        if(isset($verificacaoDosRachasHoje)){
                            return view('/confirmarRacha')->with('verificacaoDosRachasHoje', $verificacaoDosRachasHoje);
                        }
                        else{
                            if(isset($request->alterado)){
                                if($request->alterado == 'diarista'){
                                    return view('listagemJogadores')->with(['jogadoresSeparados' => $jogadoreSeparados, 'notificacoes' => $notificacoes, 'req' => $request->racha_id_secreto]);
                                }
                                elseif($request->alterado == 'mensalista'){
                                    return view('listagemJogadores')->with(['jogadoresSeparados' => $jogadoreSeparados, 'notificacoes' => $notificacoes, 'req' => $request->racha_id_secreto]);
                                }
                            }
                            else{


                                return view('listagemJogadores')->with(['jogadoresSeparados' => $jogadoreSeparados, 'notificacoes' => $notificacoes, 'req' => $request->racha_id_secreto]);
                                
                            }
                        }
                    }
                }
            }
            if(isset($request->alterado)){
                if($request->alterado == 'diarista'){
                    return view('listagemJogadores')->with(['jogadoresSeparados' => $jogadoreSeparados, 'notificacoes' => $notificacoes, 'req' => $request->racha_id_secreto]);
                }
                elseif($request->alterado == 'mensalista'){
                    return view('listagemJogadores')->with(['jogadoresSeparados' => $jogadoreSeparados, 'notificacoes' => $notificacoes, 'req' => $request->racha_id_secreto]);
                }
            }
            else{
                return view('listagemJogadores')->with(['jogadoresSeparados' => $jogadoreSeparados, 'notificacoes' => $notificacoes, 'req' => $request->racha_id_secreto]);
                
            }
    }

    public function alterarDiarista(Request $request){
        $consultaRacha = DB::table('conta_racha')->where('usuario_id', $request->jogador_id)->where('racha_id', $request->racha_id)->first();
        if($consultaRacha->mensalista == 0){
            Conta_racha::where('usuario_id', $request->jogador_id)->where('racha_id', $request->racha_id)->update(['mensalista' => 1]);
            return $this->listagemJogadores($request);


        }
        elseif($consultaRacha->mensalista == 1){
            Conta_racha::where('usuario_id', $request->jogador_id)->where('racha_id', $request->racha_id)->update(['mensalista' => 0]);
            return $this->listagemJogadores($request);


        }
    }

    public function removerJogador(Request $request){
        Conta_racha::where('usuario_id', $request->jogador_id)
        ->where('racha_id', $request->racha_id)
        ->delete();
        $verificarQuantidade = DB::table('racha')->where('id', $request->racha_id_secreto)->first();
        Racha::where('id', $request->racha_id_secreto)->update(['quantidade' => $verificarQuantidade->quantidade - 1]);
        return $this->listagemJogadores($request);
    }

    public function sairDoRacha(Request $request){
        if($request->usuario_id != session()->all()['id']){
            $verificarFila = DB::table('fila_racha')->where('jogador_id', session()->all()['id'])->where('racha_id', $request->racha_id_secreto)->first();
            if(isset($verificarFila)){
                Fila_racha::where('jogador_id', session()->all()['id'])->where('racha_id', $request->racha_id_secreto)->delete();
            }
            Conta_racha::where('usuario_id', session()->all()['id'])
            ->where('racha_id', $request->racha_id_secreto)
            ->delete();
            $verificarQuantidade = DB::table('racha')->where('id', $request->racha_id_secreto)->first();
            Racha::where('id', $request->racha_id_secreto)->update(['quantidade' => $verificarQuantidade->quantidade - 1]);
            $verificarQuantidade = DB::table('racha')->where('id', $request->racha_id_secreto)->first();
            if($verificarQuantidade->quantidade != 0){
                $verificarProximoAdm = DB::table('conta_racha')->where('racha_id', '=', $request->racha_id_secreto)->orderBy('created_at', 'asc')->first();
                Racha::where('id', $request->racha_id_secreto)->update(['usuario_id' => $verificarProximoAdm->usuario_id]);
                return redirect()->back()->with('success', 'Você saiu do racha');
            }
            elseif($verificarQuantidade->quantidade == 0){
                Racha::where('id', $request->racha_id_secreto)->delete();
            }
        }
        else{
            $verificarFila = DB::table('fila_racha')->where('jogador_id', session()->all()['id'])->where('racha_id', $request->racha_id_secreto)->first();
            if(isset($verificarFila)){
                Fila_racha::where('jogador_id', session()->all()['id'])->where('racha_id', $request->racha_id_secreto)->delete();
            }
            Conta_racha::where('usuario_id', session()->all()['id'])
            ->where('racha_id', $request->racha_id_secreto)
            ->delete();
            $verificarQuantidade = DB::table('racha')->where('id', $request->racha_id_secreto)->first();
            Racha::where('id', $request->racha_id_secreto)->update(['quantidade' => $verificarQuantidade->quantidade - 1]);
            $verificarQuantidade = DB::table('racha')->where('id', $request->racha_id_secreto)->first();
            if($verificarQuantidade->quantidade != 0){
                $verificarProximoAdm = DB::table('conta_racha')->where('racha_id', '=', $request->racha_id_secreto)->orderBy('created_at', 'asc')->first();
                Racha::where('id', $request->racha_id_secreto)->update(['usuario_id' => $verificarProximoAdm->usuario_id]);
                return redirect()->back()->with('success', 'Você saiu do racha');
            }
            elseif($verificarQuantidade->quantidade == 0){
                $verificarJogadoresRachaDia = DB::table('jogadores_racha_dia')->where('racha_id', $request->racha_id_secreto)->get();
                $verificarConvites = DB::table('convite')->where('racha_id', $request->racha_id_secreto)->get();
                $verificarConfirmacoesRacha = DB::table('racha_confirmacao')->where('racha_id', $request->racha_id_secreto)->get();
                if($verificarConfirmacoesRacha->isEmpty() == false){
                    foreach($verificarConfirmacoesRacha as $verCon){
                        RachaConfirmacao::where('id', $verCon->id)->delete();
                    }
                }
                if($verificarConvites->isEmpty() == false){
                    foreach($verificarConvites as $verConvite){
                        Convite::where('id', $verConvite->id)->delete();
                    }
                }
                if($verificarJogadoresRachaDia->isEmpty() == false){
                    foreach($verificarJogadoresRachaDia as $verJogadores){
                        Convite::where('id', $verJogadores->id)->delete();
                    }
                }
                Racha::where('id', $request->racha_id_secreto)->delete();
                return redirect()->back()->with('success', 'Você saiu do racha');
 
            }

        }

    }
}
