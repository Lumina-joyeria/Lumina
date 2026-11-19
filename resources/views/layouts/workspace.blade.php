<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#17221d">
    <title>@yield('title', 'Panel') | LÚMINA</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="workspace-body">
    <a class="skip-link" href="#contenido">Saltar al contenido</a>
    <div class="workspace">
        <aside class="sidebar" id="menu-panel" data-sidebar>
            <div class="sidebar__head">
                <a href="{{ url('/panel') }}" aria-label="LÚMINA, panel"><x-brand compact light /></a>
                <button class="sidebar__close" type="button" aria-label="Cerrar menú" data-sidebar-close><x-icon name="close" /></button>
            </div>
            <nav class="sidebar__nav" aria-label="Navegación del panel">
                <p class="sidebar__label">Espacio de trabajo</p>
                <a
                    @class(['is-active' => request()->is('panel')])
                    href="{{ url('/panel') }}"
                    @if (request()->is('panel')) aria-current="page" @endif
                >
                    <x-icon name="grid" />
                    Resumen
                </a>

                @if ((int) auth()->user()->id_rol === 1)
                    @php
                        $enlaces = [
                            ['titulo' => 'Productos', 'ruta' => 'productos.index', 'grupo' => 'productos.*', 'icono' => 'gem'],
                            ['titulo' => 'Categorías', 'ruta' => 'categorias.index', 'grupo' => 'categorias.*', 'icono' => 'tag'],
                            ['titulo' => 'Clientes', 'ruta' => 'clientes.index', 'grupo' => 'clientes.*', 'icono' => 'users'],
                            ['titulo' => 'Pedidos', 'ruta' => 'pedidos.index', 'grupo' => 'pedidos.*', 'icono' => 'cart'],
                            ['titulo' => 'Compras de inventario', 'ruta' => 'compras.create', 'grupo' => 'compras.*', 'icono' => 'box'],
                            ['titulo' => 'Finanzas', 'ruta' => 'finanzas.index', 'grupo' => 'finanzas.*', 'icono' => 'finance'],
                        ];
                    @endphp

                    <p class="sidebar__label">Administración</p>
                    @foreach ($enlaces as $enlace)
                        <a
                            @class(['is-active' => request()->routeIs($enlace['grupo'])])
                            href="{{ route($enlace['ruta']) }}"
                            @if (request()->routeIs($enlace['ruta'])) aria-current="page" @endif
                        >
                            <x-icon :name="$enlace['icono']" />
                            {{ $enlace['titulo'] }}
                        </a>
                    @endforeach
                @endif
            </nav>
            <div class="sidebar__foot">
                <div class="sidebar__profile"><span>{{ mb_strtoupper(mb_substr(auth()->user()->nombre, 0, 1)) }}</span><div><strong>{{ auth()->user()->nombre }}</strong><small>{{ (int) auth()->user()->id_rol === 1 ? 'Administrador' : 'Equipo LÚMINA' }}</small></div></div>
                <form action="{{ route('logout') }}" method="POST">@csrf<button type="submit"><x-icon name="logout" />Cerrar sesión</button></form>
            </div>
        </aside>
        <div class="workspace__main">
            <header class="workspace-header">
                <button class="workspace-header__menu" type="button" aria-label="Abrir menú" aria-controls="menu-panel" data-sidebar-open><x-icon name="menu" /></button>
                <div><p>@yield('eyebrow', 'LÚMINA')</p><h1>@yield('page_title', 'Panel')</h1></div>
                <a class="workspace-header__store" href="{{ url('/') }}"><x-icon name="home" />Ver tienda</a>
            </header>
            <main class="workspace-content" id="contenido">
                <x-flash />
                @yield('content')
            </main>
        </div>
    </div>
    <div class="sidebar-backdrop" data-sidebar-backdrop></div>
</body>
</html>
