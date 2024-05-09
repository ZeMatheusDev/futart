<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Models\Racha;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use App\Models\Fila_racha;
use Illuminate\Support\Facades\DB;
use App\Models\Jogadores_racha_dia;
use Illuminate\Support\Facades\Cache;
use Illuminate\Queue\SerializesModels;

class CheckRachaPlayers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
       
        $hoje = date('N');
        switch ($hoje) {
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
        $rachasProximos = Racha::where('hora_do_racha', '<=', now()->addHour())
            ->where('hora_do_racha', '>=', now())
            ->where('data_do_racha', '=', $diaSemana)
            ->get();
        foreach($rachasProximos as $racha){
            if($rachasProximos->isEmpty() == false){
                $consultaFilaRacha = DB::table('fila_racha')
                ->where('racha_id', '=', $racha->id)
                ->get();
                foreach($consultaFilaRacha as $consulta){
                    $jogadoresRachaDia = new Jogadores_racha_dia();
                    $jogadoresRachaDia->racha_dia = $consulta->racha_dia;
                    $jogadoresRachaDia->racha_id = $consulta->racha_id;
                    $jogadoresRachaDia->jogador_id = $consulta->jogador_id;
                    $jogadoresRachaDia->save();
                    Fila_racha::where('id', '=', $consulta->id)->delete();
                }
            }

        }
    }
}
