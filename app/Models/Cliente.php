<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cliente extends Model
{
    protected $table = 'clientes';
    
    public $timestamps = false;
    
    protected $fillable = [
        'nombre',
        'correo',
        'telefono',
    ];
    
    public function pedidos(): HasMany
    {
        return $this->hasMany(Pedido::class, 'id_cliente', 'id');
    }
}
