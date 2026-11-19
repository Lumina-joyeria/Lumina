@extends('layouts.app')
@section('title', 'Tu carrito | LÚMINA')

@section('content')
    <header class="store-hero store-hero--compact">
        <div class="container">
            <h1>Tu carrito</h1>
        </div>
    </header>

    <section class="store-section">
        <div class="container">
            <x-flash />
            @if ($errors->any())
                <div class="alert alert--error" role="alert">
                    <span class="alert__icon"><x-icon name="alert" /></span>
                    <div>
                        @foreach ($errors->all() as $mensaje)
                            <p>{{ $mensaje }}</p>
                        @endforeach
                    </div>
                </div>
            @endif

            @if ($items->isEmpty())
                <x-empty-state
                    icon="cart"
                    title="Tu carrito está vacío"
                    description="Agrega una pieza para armar tu selección."
                    action="Volver a la tienda"
                    :href="url('/').'#colecciones'"
                />
            @else
                <div class="cart-layout">
                    <div class="cart-list">
                        @foreach ($items as $item)
                            @php($producto = $item['producto'])
                            <article class="cart-item">
                                <div class="product-thumb">
                                    @if ($producto?->imagen)
                                        <img class="h-full w-full object-cover" src="{{ asset('storage/'.$producto->imagen) }}" alt="{{ $producto->nombre }}">
                                    @else
                                        <x-icon name="gem" />
                                    @endif
                                </div>
                                <div>
                                    <h2>{{ $producto?->nombre ?? 'Producto no disponible' }}</h2>
                                    @if ($producto)
                                        <p>${{ number_format($producto->precio, 2) }} por unidad</p>
                                    @endif
                                    @if (!$item['disponible'])
                                        <p class="field__error">
                                            {{ $producto?->stock > 0 ? 'Ajusta la cantidad según el stock disponible.' : 'Retira este producto para continuar.' }}
                                        </p>
                                    @endif
                                    <form method="POST" action="{{ route('carrito.destroy', $item['id']) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="danger-action" type="submit">Quitar</button>
                                    </form>
                                </div>
                                @if ($producto && $producto->stock > 0)
                                    <form method="POST" action="{{ route('carrito.update', $item['id']) }}">
                                        @csrf
                                        @method('PATCH')
                                        <label for="cantidad-{{ $item['id'] }}">Cantidad</label>
                                        <input
                                            class="input"
                                            id="cantidad-{{ $item['id'] }}"
                                            type="number"
                                            name="cantidad"
                                            min="1"
                                            max="{{ $producto->stock }}"
                                            value="{{ $item['cantidad'] }}"
                                            required
                                        >
                                        <button class="mt-2 text-sm underline" type="submit">Actualizar</button>
                                    </form>
                                @else
                                    <p>Cantidad: {{ $item['cantidad'] }}</p>
                                @endif
                                <strong>${{ number_format($item['subtotal'], 2) }}</strong>
                            </article>
                        @endforeach
                    </div>

                    <aside class="order-summary">
                        <h2>Resumen del carrito</h2>
                        <dl>
                            <div><dt>Subtotal</dt><dd>${{ number_format($total, 2) }}</dd></div>
                            <div><dt>Total</dt><dd>${{ number_format($total, 2) }}</dd></div>
                        </dl>
                        @if ($disponible && \Illuminate\Support\Facades\Route::has('checkout.index'))
                            <a class="button button--primary button--full" href="{{ route('checkout.index') }}">
                                Continuar al checkout
                            </a>
                        @elseif (!$disponible)
                            <p class="field__error">Revisa la disponibilidad de los productos antes de continuar.</p>
                        @endif
                    </aside>
                </div>
            @endif
        </div>
    </section>
@endsection
