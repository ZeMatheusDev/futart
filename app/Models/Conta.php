<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conta extends Model
{
    use HasFactory;
    protected $table = 'Conta';

    protected $fillable = [
        'usuario',
        'senha',
        'nome',
        'email',
        'vip',
        'posicao',
        'conta_token',
        'foto',
        'created_at',
        'updated_at',
    ];
    protected $hidden = [
        'senha',
    ];
}
