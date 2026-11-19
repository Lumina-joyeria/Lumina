<?php

namespace Tests;

use App\Models\Usuario;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

abstract class LuminaFeatureTestCase extends TestCase
{
    protected const CLAVE_PRUEBA = 'ClaveLocalParaPruebas123!';

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();

        config([
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => ':memory:',
            'database.connections.sqlite.url' => null,
            'database.connections.sqlite.foreign_key_constraints' => true,
            'cache.default' => 'array',
            'session.driver' => 'array',
        ]);

        DB::purge('sqlite');

        Schema::create('roles', function (Blueprint $table): void {
            $table->id();
            $table->string('nombre', 30)->unique();
        });

        Schema::create('usuarios', function (Blueprint $table): void {
            $table->id();
            $table->string('nombre', 100);
            $table->string('correo', 100)->unique();
            $table->string('contrasena');
            $table->foreignId('id_rol')->constrained('roles');
        });

        Schema::create('clientes', function (Blueprint $table): void {
            $table->id();
            $table->string('nombre', 100);
            $table->string('correo', 100);
            $table->string('telefono', 20)->nullable();
            $table->timestamp('fecha_registro')->useCurrent();
        });

        Schema::create('pedidos', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('id_cliente')->constrained('clientes');
            $table->timestamp('fecha_pedido')->useCurrent();
            $table->string('estado')->default('Pendiente');
        });

        DB::table('roles')->insert([
            ['id' => 1, 'nombre' => 'Administrador'],
            ['id' => 2, 'nombre' => 'Empleado'],
        ]);
    }

    protected function crearUsuario(int $rol = 1): Usuario
    {
        $id = DB::table('usuarios')->insertGetId([
            'nombre' => $rol === 1 ? 'Administradora de prueba' : 'Empleado de prueba',
            'correo' => $rol === 1 ? 'admin@example.com' : 'empleado@example.com',
            'contrasena' => Hash::make(self::CLAVE_PRUEBA),
            'id_rol' => $rol,
        ]);

        return Usuario::findOrFail($id);
    }
}
