<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Producto extends Model
{
    protected $table = 'productos';
    
    public $timestamps = false;
    
    protected $fillable = [
        'nombre',
        'description',
        'precio',
        'stock',
        'imagen',
        'id_categoria',
    ];
    
    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class, 'id_categoria', 'id');
    }
    
    public function detalles(): HasMany
    {
        return $this->hasMany(DetallePedido::class, 'id_producto', 'id');
    }
    
    public function comprasInventario(): HasMany
    {
        return $this->hasMany(CompraInventario::class, 'id_producto', 'id');
    }
}