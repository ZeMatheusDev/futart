<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RachaConfirmacao extends Model
{
    use HasFactory;
    protected $table = 'Racha_confirmacao';

    protected $fillable = [
        'data_dia_racha',
        'confirmacao',
        'racha_id',
        'created_at',
        'updated_at',
    ];
}
