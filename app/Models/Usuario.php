<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Usuario extends Authenticatable
{
    protected $table = 'usuarios';
    
    protected $authPasswordName = 'contrasena';
    
    public $timestamps = false;
    
    protected $fillable = [
        'nombre',
        'correo'
    ];
    
    public function rol(): BelongsTo
    {
        return $this->belongsTo(Rol::class, 'id_rol', 'id');
    }
}
