@extends('layouts.workspace')

@section('title', 'Resumen')
@section('eyebrow', (int) auth()->user()->id_rol === 1 ? 'Administración' : 'Operaciones')
@section('page_title', 'Hola, ' . $nombre)

@section('content')
    <section class="welcome-strip" aria-label="Bienvenida">
        <div>
            <p class="eyebrow">{{ now()->locale('es')->translatedFormat('l, d \d\e F') }}</p>
            <h2>{{ (int) auth()->user()->id_rol === 1 ? 'Tu tienda, de un vistazo.' : 'Bienvenido a LÚMINA' }}</h2>
            <p>{{ (int) auth()->user()->id_rol === 1 ? 'Elige un módulo para continuar.' : 'Tu cuenta tiene acceso al resumen y a la tienda.' }}</p>
        </div>
        <div class="welcome-strip__spark" aria-hidden="true">
            <span></span>
        </div>
    </section>

    @if ((int) auth()->user()->id_rol === 1)
        @php
            $modulos = [
                ['titulo' => 'Productos', 'ruta' => 'productos.index', 'icono' => 'gem'],
                ['titulo' => 'Categorías', 'ruta' => 'categorias.index', 'icono' => 'tag'],
                ['titulo' => 'Clientes', 'ruta' => 'clientes.index', 'icono' => 'users'],
                ['titulo' => 'Pedidos', 'ruta' => 'pedidos.index', 'icono' => 'cart'],
                ['titulo' => 'Compras de inventario', 'ruta' => 'compras.create', 'icono' => 'box'],
                ['titulo' => 'Finanzas', 'ruta' => 'finanzas.index', 'icono' => 'finance'],
            ];

            $acciones = [
                ['titulo' => 'Registrar producto', 'ruta' => 'productos.create', 'icono' => 'plus'],
                ['titulo' => 'Crear categoría', 'ruta' => 'categorias.create', 'icono' => 'tag'],
                ['titulo' => 'Registrar cliente', 'ruta' => 'clientes.create', 'icono' => 'users'],
                ['titulo' => 'Crear pedido', 'ruta' => 'pedidos.create', 'icono' => 'cart'],
            ];
        @endphp

        <section class="metric-grid" aria-label="Módulos de administración">
            @foreach ($modulos as $modulo)
                <a class="metric-card" href="{{ route($modulo['ruta']) }}">
                    <span class="metric-card__icon">
                        <x-icon :name="$modulo['icono']" />
                    </span>
                    <strong>{{ $modulo['titulo'] }}</strong>
                    <x-icon name="arrow-right" />
                </a>
            @endforeach
        </section>

        <section class="panel-card" aria-labelledby="accesos-rapidos">
            <div class="panel-card__head">
                <h2 id="accesos-rapidos">Accesos rápidos</h2>
            </div>

            <div class="quick-actions">
                @foreach ($acciones as $accion)
                    <a href="{{ route($accion['ruta']) }}">
                        <span>
                            <x-icon :name="$accion['icono']" />
                        </span>
                        <strong>{{ $accion['titulo'] }}</strong>
                        <x-icon name="arrow-right" />
                    </a>
                @endforeach
            </div>
        </section>
    @endif
@endsection
