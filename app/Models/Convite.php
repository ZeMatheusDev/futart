<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Convite extends Model
{
    use HasFactory;
    protected $table = 'convite';

    protected $fillable = [
        'convidado_id',
        'dono_id',
        'racha_id',
        'created_at',
        'updated_at',
    ];
    protected $hidden = [
        'convidado_id',
        'dono_id',
        'racha_id',
    ];
}
