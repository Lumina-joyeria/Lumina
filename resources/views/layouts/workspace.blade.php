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
        <aside class="sidebar" data-sidebar>
            <div class="sidebar__head">
                <a href="{{ url('/panel') }}" aria-label="LÚMINA, panel"><x-brand compact light /></a>
                <button class="sidebar__close" type="button" aria-label="Cerrar menú" data-sidebar-close><x-icon name="close" /></button>
            </div>
            <nav class="sidebar__nav" aria-label="Navegación del panel">
                <p class="sidebar__label">Espacio de trabajo</p>
                <a class="{{ request()->is('panel') ? 'is-active' : '' }}" href="{{ url('/panel') }}"><x-icon name="grid" />Resumen</a>
                @if((int) auth()->user()->id_rol === 1)
                    <p class="sidebar__label">Administración</p>
                    <a class="{{ request()->routeIs('productos.*') ? 'is-active' : '' }}" href="{{ route('productos.index') }}"><x-icon name="gem" />Productos</a>
                    <a class="{{ request()->routeIs('categorias.*') ? 'is-active' : '' }}" href="{{ route('categorias.index') }}"><x-icon name="tag" />Categorías</a>
                    <a class="{{ request()->routeIs('clientes.*') ? 'is-active' : '' }}" href="{{ route('clientes.index') }}"><x-icon name="users" />Clientes</a>
                    <span class="sidebar__pending" title="Pendiente de integración"><x-icon name="box" />Inventario <small>Próximamente</small></span>
                @else
                    <p class="sidebar__label">Operaciones</p>
                    <span class="sidebar__pending"><x-icon name="cart" />Pedidos <small>Próximamente</small></span>
                    <span class="sidebar__pending"><x-icon name="box" />Inventario <small>Próximamente</small></span>
                @endif
            </nav>
            <div class="sidebar__foot">
                <div class="sidebar__profile"><span>{{ mb_strtoupper(mb_substr(auth()->user()->nombre, 0, 1)) }}</span><div><strong>{{ auth()->user()->nombre }}</strong><small>{{ (int) auth()->user()->id_rol === 1 ? 'Administrador' : 'Equipo LÚMINA' }}</small></div></div>
                <form action="{{ route('logout') }}" method="POST">@csrf<button type="submit"><x-icon name="logout" />Cerrar sesión</button></form>
            </div>
        </aside>
        <div class="workspace__main">
            <header class="workspace-header">
                <button class="workspace-header__menu" type="button" aria-label="Abrir menú" data-sidebar-open><x-icon name="menu" /></button>
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
