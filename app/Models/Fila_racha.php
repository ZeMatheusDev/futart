<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fila_racha extends Model
{
    use HasFactory;
    protected $table = 'fila_racha';

    protected $fillable = [
        'racha_dia',
        'racha_id',
        'jogador_id',
        'created_at',
        'updated_at',
    ];
}
