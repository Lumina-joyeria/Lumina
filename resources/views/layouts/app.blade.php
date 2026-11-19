<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#f7f3ee">
    <title>@yield('title', 'LÚMINA')</title>
    <meta name="description" content="@yield('meta_description', 'Joyería contemporánea diseñada para acompañar tus momentos más luminosos.')">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="@yield('body_class')">
    <a class="skip-link" href="#contenido">Saltar al contenido</a>
    <header class="site-header" data-header>
        <div class="announcement">Envíos seguros a todo Ecuador <span aria-hidden="true">·</span> Empaque de cortesía</div>
        <div class="site-header__bar container">
            <a href="{{ url('/') }}" aria-label="LÚMINA, inicio"><x-brand compact /></a>
            <nav class="site-nav" aria-label="Navegación principal" data-mobile-nav>
                <a href="{{ url('/') }}#colecciones">Colecciones</a>
                <a href="{{ url('/') }}#historia">Nuestra esencia</a>
                <a href="{{ url('/') }}#servicios">Servicios</a>
            </nav>
            <div class="site-header__actions">
                @auth
                    <a class="header-action" href="{{ url('/panel') }}" aria-label="Ir al panel"><x-icon name="user" /><span>Panel</span></a>
                @else
                    <a class="header-action" href="{{ route('login') }}" aria-label="Iniciar sesión"><x-icon name="user" /><span>Ingresar</span></a>
                @endauth
                <button class="menu-toggle" type="button" aria-label="Abrir menú" aria-expanded="false" data-menu-toggle>
                    <x-icon name="menu" />
                </button>
            </div>
        </div>
    </header>
    <main id="contenido">@yield('content')</main>
    <footer class="site-footer">
        <div class="container site-footer__grid">
            <div><x-brand light /><p>Joyas sutiles, hechas para guardar historias.</p></div>
            <div><h2>Explora</h2><a href="{{ url('/') }}#colecciones">Colecciones</a><a href="{{ url('/') }}#historia">Nuestra esencia</a><a href="{{ route('login') }}">Acceso al equipo</a></div>
            <div><h2>Atención</h2><p>Lunes a sábado<br>09:00 – 18:00</p><p>Guayaquil, Ecuador</p></div>
            <div><h2>Confianza</h2><p>Pagos protegidos</p><p>Empaque cuidadoso</p><p>Asesoría personalizada</p></div>
        </div>
        <div class="container site-footer__bottom"><span>© {{ date('Y') }} LÚMINA</span><span>Diseñado para brillar contigo</span></div>
    </footer>
</body>
</html>
