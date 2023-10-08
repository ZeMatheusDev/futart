<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conta_racha extends Model
{
    use HasFactory;
    protected $table = 'Conta_racha';

    protected $fillable = [
        'usuario_id',
        'racha_id',
    ];

}
