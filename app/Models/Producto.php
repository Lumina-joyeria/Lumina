<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}