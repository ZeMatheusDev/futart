<?php

namespace App\Http\Controllers;
use App\Models\Racha;
use App\Models\Conta_racha;
use App\Models\Convite;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RachaController extends Controller
{
    public function cadastrar(){
        $notificacoes = DB::table('convite')
        ->where('convidado_id', '=', session()->all()['id'])
        ->join('conta', 'conta.id', '=', 'dono_id')
        ->join('racha', 'racha.id', '=', 'racha_id')
        ->get();
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
            
            return view('/listagem')->with('listagem', $listagem);
        }
        else{
            return redirect()->back()->with('error', 'Você já está cadastrado nesse racha!');
        }
    }

    public function recusarRacha(Request $request){
        DB::table('convite')->where('convidado_id', '=', session()->all()['id'])->where('racha_id', '=', $request->racha_id)->delete();
        $listagem = DB::table('racha')
        ->where('usuario_id', '=', session()->all()['id'])
        ->join('conta', 'conta.id', '=', 'usuario_id')
        ->get();
        return view('/listagem')->with('listagem', $listagem);

    }

    public function enviarConvite(Request $request){
        $verificacao = DB::table('conta')
        ->where('email', '=', $request->email)
        ->get();
        $verificarRachaDono = DB::table('racha')
        ->where('racha_token', '=', $request->rachaToken)
        ->join('conta', 'conta.id', '=', 'racha.usuario_id')
        ->get();
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

    public function telaInvite(Request $request, $racha_token)
    {
        $informacoes = DB::table('Conta')
        ->where('id', '=', session()->all()['id'])
        ->get();

        $informacoesRacha = DB::table('Racha')
        ->where('racha_token', '=', $racha_token)
        ->get();
            $dadosCombinados = [
            'usuario' => $informacoes[0],
            'racha' => $informacoesRacha[0],
        ];

        return view('telaInvite')->with('dados', $dadosCombinados);
    }

    public function cadastrando(Request $request){
        if(session()->all()['vip'] == 0){
            $verificacao = DB::table('racha')->where('usuario_id', '=', session()->all()['id'])->get();
            if($verificacao->isEmpty() == true){
                $racha = new Racha();
                $racha->nome_do_racha = $request->nome;
                $racha->descricao = $request->descricao;
                $racha->quantidade = 1;
                $token = Str::random(32);
                $racha->racha_token = $token;
                $racha->data_do_racha = $request->data;
                $racha->hora_do_racha = '08:30:00';
                $racha->final_do_racha = '10:30:00';
                $racha->usuario_id = session()->all()['id'];
                $racha->created_at = now();
                $racha->updated_at = now();
                $racha->save();
                $contaRacha = new Conta_racha();
                $contaRacha->usuario_id = session()->all()['id']; 
                $contaRacha->racha_id = $racha->id;
                $contaRacha->save();

                return redirect()->back()->with('success', 'Cadastrado feito com sucesso!');
            };
            return redirect()->back()->with('error', 'Voce ja possui um racha cadastrado como FREE ACCOUNT!');
        }
        else{
            $racha = new Racha();
            $racha->nome_do_racha = $request->nome;
            $racha->descricao = $request->descricao;
            $racha->quantidade = 1;
            $racha->data_do_racha = $request->data;
            $racha->hora_do_racha = $request->hora_inicio;
            $racha->final_do_racha = $request->hora_fim;
            $token = Str::random(32);
            $racha->racha_token = $token;
            $racha->usuario_id = session()->all()['id'];
            $racha->created_at = now();
            $racha->updated_at = now();
            $racha->save();
            $racha->refresh();
            $contaRacha = new Conta_racha();
            $contaRacha->usuario_id = session()->all()['id']; 
            $contaRacha->racha_id = $racha->id;
            $contaRacha->save();
            return redirect()->back()->with('success', 'Cadastrado feito com sucesso!');
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
        return view('listagem')->with(['listagem' => $listagem, 'notificacoes' => $notificacoes]);
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
            ->where('id', '=', $jogador->usuario_id)
            ->get();
            $jogadoreSeparados[] = $jogadorSolo[0];
        }

        return view('listagemJogadores')->with(['jogadoresSeparados' => $jogadoreSeparados, 'notificacoes' => $notificacoes]);
    }
}
