<?php

namespace Tests;

use App\Models\Producto;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

abstract class LuminaPedidosTestCase extends LuminaFeatureTestCase
{
    private int $categoria;

    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('categorias', function (Blueprint $table): void {
            $table->id();
            $table->string('nombre', 50)->unique();
        });

        Schema::create('productos', function (Blueprint $table): void {
            $table->id();
            $table->string('nombre', 100);
            $table->decimal('precio', 10, 2);
            $table->integer('stock');
            $table->string('imagen')->nullable();
            $table->foreignId('id_categoria')->constrained('categorias');
        });

        Schema::create('detalle_pedidos', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('id_pedido')->constrained('pedidos');
            $table->foreignId('id_producto')->constrained('productos');
            $table->integer('cantidad');
            $table->decimal('precio_unitario', 10, 2);
            $table->unique(['id_pedido', 'id_producto']);
        });

        Schema::create('movimientos_financieros', function (Blueprint $table): void {
            $table->id();
            $table->string('tipo');
            $table->string('concepto');
            $table->decimal('monto', 10, 2);
            $table->foreignId('id_pedido')->nullable()->constrained('pedidos');
        });

        $this->categoria = DB::table('categorias')->insertGetId(['nombre' => 'Colección de prueba']);
    }

    protected function crearProducto(string $nombre, int $stock, string $precio): Producto
    {
        return Producto::create([
            'nombre' => $nombre,
            'stock' => $stock,
            'precio' => $precio,
            'id_categoria' => $this->categoria,
        ]);
    }
}
