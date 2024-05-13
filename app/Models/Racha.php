<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Racha extends Model
{
    use HasFactory;
    protected $table = 'Racha';

    protected $fillable = [
        'nome_do_racha',
        'descricao',
        'quantidade',
        'hora_do_racha',
        'final_do_racha',
        'mensalista_preferencia',
        'data_do_racha',
        'racha_token',
        'quantidade_maxima_jogo',
        'usuario_id',
        'ativo',
        'created_at',
        'updated_at',
    ];

}
