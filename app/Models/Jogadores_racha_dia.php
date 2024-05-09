<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jogadores_racha_dia extends Model
{
    use HasFactory;
    protected $table = 'jogadores_racha_dia';

    protected $fillable = [
        'racha_dia',
        'racha_id',
        'jogador_id',
        'created_at',
        'updated_at',
    ];
}
