@extends('layouts.app')

@section('title', 'LÚMINA | Joyería contemporánea')

@section('content')
<section class="hero">
    <div class="container hero__grid">
        <div class="hero__copy reveal">
            <p class="eyebrow">Nueva colección · Aura</p>
            <h1>El lujo de llevar<br><em>tu propia luz.</em></h1>
            <p class="hero__lead">Piezas contemporáneas de elegancia serena, pensadas para celebrar lo cotidiano y permanecer más allá del momento.</p>
            <div class="hero__actions">
                <a class="button button--primary" href="#colecciones">Descubrir colección <x-icon name="arrow-right" size="18" /></a>
                <a class="text-link" href="#historia">Conoce nuestra esencia</a>
            </div>
            <div class="hero__trust" aria-label="Valores de la marca"><span><x-icon name="gem" />Diseño atemporal</span><span><x-icon name="check" />Selección cuidada</span></div>
        </div>
        <div class="hero__visual reveal reveal--delay" aria-label="Composición decorativa de joyería">
            <div class="jewel-scene jewel-scene--photo">
                <img src="{{ asset('images/lumina/hero-aura.jpg') }}" alt="Collar Aura con gema champaña sobre piedra marfil" width="1800" height="1004" fetchpriority="high">
                <p>01 <span></span> Aura</p>
            </div>
        </div>
    </div>
    <a class="hero__scroll" href="#colecciones"><span></span>Desliza para explorar</a>
</section>

<section class="section collections" id="colecciones">
    <div class="container">
        <div class="section-heading"><div><p class="eyebrow">Curaduría LÚMINA</p><h2>Piezas para cada historia</h2></div><p>Una selección esencial donde la forma, el brillo y los detalles encuentran su equilibrio.</p></div>
        <div class="collection-grid">
            <article class="collection-card collection-card--large"><img class="collection-art collection-art--photo" src="{{ asset('images/lumina/collection-necklaces.jpg') }}" alt="Collares dorados de la colección LÚMINA" loading="lazy"><div class="collection-card__content"><p>Colección 01</p><h3>Collares</h3><span>Delicados puntos de luz <x-icon name="arrow-right" size="16" /></span></div></article>
            <article class="collection-card"><img class="collection-art collection-art--photo" src="{{ asset('images/lumina/collection-rings.jpg') }}" alt="Anillos dorados sobre piedra natural" loading="lazy"><div class="collection-card__content"><p>Colección 02</p><h3>Anillos</h3><span>Formas que permanecen <x-icon name="arrow-right" size="16" /></span></div></article>
            <article class="collection-card"><img class="collection-art collection-art--photo" src="{{ asset('images/lumina/collection-earrings.jpg') }}" alt="Aretes dorados con gemas champaña" loading="lazy"><div class="collection-card__content"><p>Colección 03</p><h3>Aretes</h3><span>Un brillo junto al rostro <x-icon name="arrow-right" size="16" /></span></div></article>
        </div>
        <p class="integration-note"><x-icon name="info" size="18" /> El catálogo se conectará aquí cuando Zahid habilite su ruta pública y entregue la colección de productos.</p>
    </div>
</section>

<section class="story" id="historia"><div class="container story__grid"><div class="story__visual"><img src="{{ asset('images/lumina/story-essence.jpg') }}" alt="Anillo y cadena LÚMINA sobre seda y piedra natural" loading="lazy"><span>Detalles que hablan de ti</span></div><div class="story__copy"><p class="eyebrow">Nuestra esencia</p><h2>Menos exceso.<br>Más significado.</h2><p>Creemos en joyas que no compiten con quien las lleva. Diseñamos desde la sutileza: proporciones honestas, acabados luminosos y una estética que acompaña cada versión de ti.</p><blockquote>“Una joya se vuelve única cuando forma parte de una historia.”</blockquote><a class="text-link" href="#servicios">Cómo cuidamos cada detalle <x-icon name="arrow-right" size="16" /></a></div></div></section>

<section class="section services" id="servicios"><div class="container"><div class="section-heading section-heading--center"><div><p class="eyebrow">La experiencia LÚMINA</p><h2>Cuidado en cada paso</h2></div></div><div class="service-grid"><article><span><x-icon name="gem" size="26" /></span><h3>Selección especial</h3><p>Cada pieza se elige por su equilibrio, presencia y versatilidad.</p></article><article><span><x-icon name="box" size="26" /></span><h3>Empaque de cortesía</h3><p>Una presentación sobria y delicada, lista para regalar o atesorar.</p></article><article><span><x-icon name="check" size="26" /></span><h3>Compra acompañada</h3><p>Asesoría cercana antes, durante y después de elegir tu pieza.</p></article></div></div></section>

<section class="newsletter"><div class="container newsletter__inner"><div><p class="eyebrow">Círculo LÚMINA</p><h2>Historias, cuidados y nuevos destellos.</h2></div><div class="newsletter__placeholder"><span>La suscripción estará disponible próximamente</span><x-icon name="arrow-right" /></div></div></section>
@endsection
