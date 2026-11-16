@props(['compact' => false, 'light' => false])

<span {{ $attributes->class(['brand', 'brand--compact' => $compact, 'brand--light' => $light]) }}>
    <span class="brand__mark" aria-hidden="true">
        <svg viewBox="0 0 40 40" fill="none">
            <path d="M20 3.5 24.7 15 36.5 20l-11.8 5L20 36.5 15.3 25 3.5 20l11.8-5L20 3.5Z" stroke="currentColor" stroke-width="1.4"/>
            <circle cx="20" cy="20" r="4.2" fill="currentColor"/>
        </svg>
    </span>
    <span class="brand__word">LÚMINA</span>
    @unless($compact)
        <span class="brand__tagline">Joyería contemporánea</span>
    @endunless
</span>
