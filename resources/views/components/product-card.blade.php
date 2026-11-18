@props(['producto', 'href' => null, 'addAction' => null])
@php
    $fallbacks = ['product-necklace.jpg', 'product-ring.jpg', 'product-earrings.jpg', 'product-bracelet.jpg'];
    $fallbackImage = $fallbacks[((int) $producto->id - 1) % count($fallbacks)];
@endphp
<article class="product-card">
    <div class="product-card__media">
        <img src="{{ $producto->imagen ? asset('storage/' . $producto->imagen) : asset('images/lumina/' . $fallbackImage) }}" alt="{{ $producto->nombre }}" loading="lazy">
        @if($producto->stock <= 5 && $producto->stock > 0)<span class="product-card__badge">Últimas unidades</span>@endif
    </div>
    <div class="product-card__body"><p>{{ $producto->categoria?->nombre ?? 'Colección LÚMINA' }}</p><h2>{{ $producto->nombre }}</h2><div><strong>${{ number_format($producto->precio, 2) }}</strong><span>{{ $producto->stock > 0 ? 'Disponible' : 'Agotado' }}</span></div>
        @if($href)<a class="product-card__link" href="{{ $href }}" aria-label="Ver {{ $producto->nombre }}">Ver detalles <x-icon name="arrow-right" size="16" /></a>@else<span class="product-card__link product-card__link--disabled">Detalle pendiente de ruta</span>@endif
    </div>
</article>
