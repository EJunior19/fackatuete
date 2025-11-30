<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SifenLog extends Model
{
    use HasFactory;

    protected $table = 'sifen_logs';

    protected $fillable = [
        'accion',
        'resultado',
        'request',
        'response',
        'error',
    ];
}
