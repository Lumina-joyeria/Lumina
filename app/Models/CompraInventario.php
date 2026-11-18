<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompraInventario extends Model
{
    protected $table = 'compras_inventario';

    public $timestamps = false;

    protected $fillable = [
        'id_producto',
        'id_movimiento',
        'cantidad',
        'costo_unitario',
        'total',
    ];
}
