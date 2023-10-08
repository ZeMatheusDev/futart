<?php

namespace App\Http\Controllers;
use App\Models\Racha;
use App\Models\Conta_racha;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class RachaController extends Controller
{
    public function cadastrar(){
        return view('cadastrarRacha');
    }

    public function aceitar(Request $request){
        $verificacao = DB::table('Conta_racha')
        ->where('usuario_id', '=', $request->usuario_id)
        ->where('racha_id', '=', $request->racha_id)
        ->get();
        if($verificacao->isEmpty() == true){
            $contaRacha = new Conta_racha();
            $contaRacha->usuario_id = session()->all()['id']; 
            $contaRacha->racha_id = $request->racha_id;
            $contaRacha->save();
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

    public function telaInvite(Request $request, $racha_id)
    {
        $informacoes = DB::table('Conta')
        ->where('id', '=', session()->all()['id'])
        ->get();

        $informacoesRacha = DB::table('Racha')
        ->where('id', '=', $racha_id)
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
        $listagem = DB::table('racha')
        ->where('usuario_id', '=', session()->all()['id'])
        ->join('conta', 'conta.id', '=', 'usuario_id')
        ->get();
        return view('listagem')->with('listagem', $listagem);
    }

    public function listagemJogadores(){
        return view('listagemJogadores');
    }
}
