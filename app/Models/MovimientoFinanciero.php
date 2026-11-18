<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovimientoFinanciero extends Model
{
    protected $table = 'movimientos_financieros';
    
    public $timestamps = false;
    
    protected $fillable = [
        'tipo',
        'concepto',
        'monto',
        'id_pedido',
    ];
}