<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\Conta;
use Illuminate\Support\Str;


use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function home(){
        if(session()->has('id')){
            $notificacoes = DB::table('convite')
            ->where('convidado_id', '=', session()->all()['id'])
            ->join('conta', 'conta.id', '=', 'dono_id')
            ->join('racha', 'racha.id', '=', 'racha_id')
            ->get();
        
            return view('home')?->with('notificacoes', $notificacoes);
        }
        else{
            return view('home');
        }
    }

    public function criarConta(){
        return view('criarConta');
    }

    public function logar(){
        return view('logar');
    }

    public function criandoConta(Request $request){
        $verificacaoLogin = DB::table('Conta')->where('usuario', '=', $request->usuario)->get();
        $verificacaoEmail = DB::table('Conta')->where('email', '=', $request->email)->get();
        if($verificacaoLogin->isEmpty() == true && $verificacaoEmail->isEmpty() == true){
            $user = new Conta();
            $user->usuario = $request->usuario;
            $user->senha = $request->senha;
            $user->posicao = $request->posicao;
            $user->nome = $request->nome;
            $token = Str::random(32);
            $user->conta_token = $token;
            $user->email = $request->email;
            if ($request->hasFile('foto')) {
                $imagem = $request->file('foto');
                $extensao = $imagem->getClientOriginalExtension();
                $extensoesPermitidas = ['jpg', 'jpeg', 'png'];
            
                if (in_array(strtolower($extensao), $extensoesPermitidas)) {
                    // Diretório onde os arquivos são armazenados
                    $diretorio = public_path('fotos');
            
                    // Nome original do arquivo
                    $nomeArquivoOriginal = $imagem->getClientOriginalName();
            
                    // Verifica se o arquivo já existe na pasta
                    $contador = 1;
                    $nomeArquivo = $nomeArquivoOriginal;
                    while (file_exists($diretorio . '\\' . $nomeArquivo)) {
                        // O arquivo já existe, então adicionamos um contador ao nome do arquivo
                        $nomeArquivo = pathinfo($nomeArquivoOriginal, PATHINFO_FILENAME) . '_' . $contador . '.' . $extensao;
                        $contador++;
                    }
            
                    // Agora, $nomeArquivo contém um nome único para o arquivo
                    $caminho = $imagem->storeAs('fotos', $nomeArquivo, 'public');
                    
                    // Defina o caminho completo com barras invertidas
                    $caminhoCompleto = "C:\\Users\\Matheus\\Desktop\\PROJETO\\futart\\public\\{$caminho}";
            
                    $user->foto = $caminhoCompleto;
                } else {
                    // Extensão de arquivo não permitida
                    return redirect()->back()->with('error', 'Erro, formato de imagem não permitido.');
                }
            }
            $user->vip = 0;
            $user->created_at = now();
            $user->updated_at = now();
            $user->save();
            return redirect()->back()->with('success', 'Cadastrado feito com sucesso!');
        }
        else{
            return redirect()->back()->with('error', 'Erro, usuário ou email já existe!');
        };
    }    

    public function logando(Request $request){
        $verificacao = DB::table('Conta')->where('usuario', '=', $request->usuario)->where('senha', '=', $request->senha)->get();
        if($verificacao->isEmpty() == false){
            $fotonova = $verificacao[0]->foto;
            $info = pathinfo($fotonova);
            $nomeDoArquivo = $info['basename'];
            session(['id' => $verificacao[0]->id]);
            session(['login' => $verificacao[0]->usuario]);
            session(['foto' => $nomeDoArquivo]);
            session(['vip' => $verificacao[0]->vip]);
            session(['email' => $verificacao[0]->email]);
            session(['logado' => true]);
            $notificacoes = DB::table('convite')
            ->where('convidado_id', '=', session()->all()['id'])
            ->join('conta', 'conta.id', '=', 'dono_id')
            ->join('racha', 'racha.id', '=', 'racha_id')
            ->get();
            return view('logado')->with(['success' => 'Login feito com sucesso!', 'notificacoes' => $notificacoes]);
        }
        else{
            return redirect()->back()->with('error', 'Erro, conta não encontrada!');
        };
    }
    
    public function deslogar(Request $request){
        session()->remove('login');
        session()->remove('senha');
        session()->remove('vip');
        session()->remove('foto');
        session()->remove('email');
        session()->remove('logado');
        return view('home');
    }
}
