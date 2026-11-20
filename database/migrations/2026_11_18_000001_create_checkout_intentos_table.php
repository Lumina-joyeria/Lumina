<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('checkout_intentos', function (Blueprint $table): void {
            $table->uuid('token')->primary();
            $table->integer('id_pedido')->nullable();

            $table->foreign('id_pedido')->references('id')->on('pedidos');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('checkout_intentos');
    }
};
