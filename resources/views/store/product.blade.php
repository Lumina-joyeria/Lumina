
@extends('layouts.app')

@section('title', $producto->nombre . ' | LÚMINA')

@section('content')

    @php
        // Imágenes alternativas para productos sin fotografía
        $fallbacks = [
            'product-necklace.jpg',
            'product-ring.jpg',
            'product-earrings.jpg',
            'product-bracelet.jpg',
        ];

        $fallbackImage = $fallbacks[
            ((int) $producto->id - 1) % count($fallbacks)
        ];
    @endphp

    {{-- Detalle del producto --}}
    <section class="store-section product-detail">

        <div class="container product-detail__grid">

            {{-- Fotografía --}}
            <div class="product-detail__media">

                <img
                    src="{{ $producto->imagen
                        ? asset('storage/' . $producto->imagen)
                        : asset('images/lumina/' . $fallbackImage) }}"
                    alt="{{ $producto->nombre }}"
                >

            </div>

            {{-- Información del producto --}}
            <div class="product-detail__copy">

                {{-- Categoría --}}
                <p class="eyebrow">
                    {{ $producto->categoria?->nombre ?? 'Colección LÚMINA' }}
                </p>

                {{-- Nombre --}}
                <h1>
                    {{ $producto->nombre }}
                </h1>

                {{-- Precio --}}
                <strong class="product-detail__price">
                    ${{ number_format($producto->precio, 2) }}
                </strong>

                {{-- Descripción --}}
                <p>
                    {{ $producto->description
                        ?: 'Una pieza de presencia sutil, seleccionada para acompañarte todos los días.' }}
                </p>

                {{-- Disponibilidad --}}
                <div
                    class="availability {{ $producto->stock < 1 ? 'availability--out' : '' }}"
                >
                    <i></i>

                    @if ($producto->stock > 0)

                        {{ $producto->stock }} unidades disponibles

                    @else

                        Producto agotado

                    @endif

                </div>

                {{-- Agregar al carrito --}}
                @if ($cartAction ?? false)

                    <form
                        action="{{ $cartAction }}"
                        method="POST"
                        class="add-cart"
                    >
                        @csrf

                        <input
                            type="hidden"
                            name="id_producto"
                            value="{{ $producto->id }}"
                        >

                        <label for="cantidad">
                            Cantidad
                        </label>

                        <input
                            class="input"
                            id="cantidad"
                            name="cantidad"
                            type="number"
                            min="1"
                            max="{{ $producto->stock }}"
                            value="1"
                            required
                        >

                        <button
                            class="button button--primary"
                            type="submit"
                            @disabled($producto->stock < 1)
                        >
                            <x-icon name="cart" />

                            Agregar al carrito
                        </button>

                    </form>

                @else

                    <p class="integration-note">

                        <x-icon name="info" size="18" />

                        La acción del carrito se habilitará
                        cuando exista su ruta de backend.

                    </p>

                @endif

                {{-- Beneficios de LÚMINA --}}
                <div class="product-detail__benefits">

                    <span>
                        <x-icon name="box" />
                        Empaque de cortesía
                    </span>

                    <span>
                        <x-icon name="check" />
                        Compra protegida
                    </span>

                </div>

            </div>

        </div>

    </section>

@endsection
