@extends('layouts.app')
@section('title', 'Finalizar pedido | LÚMINA')

@section('content')
    <header class="store-hero store-hero--compact">
        <div class="container">
            <h1>Finaliza tu pedido</h1>
        </div>
    </header>

    <section class="store-section">
        <div class="container checkout-layout">
            <form class="editor-card" action="{{ route('checkout.store') }}" method="POST">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="editor-card__head">
                    <span><x-icon name="user" /></span>
                    <div>
                        <h2>Datos de contacto</h2>
                        <p>El pedido se registrará como pendiente de confirmación.</p>
                    </div>
                </div>

                @error('precios')
                    <div class="alert alert--error" role="alert">
                        <span class="alert__icon"><x-icon name="alert" /></span>
                        <p>{{ $message }}</p>
                    </div>
                @enderror

                <div class="form-grid">
                    <div class="field field--wide">
                        <label for="checkout-nombre">Nombre completo</label>
                        <input
                            class="input"
                            id="checkout-nombre"
                            name="nombre"
                            value="{{ is_string(old('nombre')) ? old('nombre') : '' }}"
                            maxlength="100"
                            autocomplete="name"
                            required
                            @error('nombre') aria-invalid="true" aria-describedby="checkout-nombre-error" @enderror
                        >
                        @error('nombre')
                            <p class="field__error" id="checkout-nombre-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="checkout-correo">Correo electrónico</label>
                        <input
                            class="input"
                            type="email"
                            id="checkout-correo"
                            name="correo"
                            value="{{ is_string(old('correo')) ? old('correo') : '' }}"
                            maxlength="100"
                            autocomplete="email"
                            required
                            @error('correo') aria-invalid="true" aria-describedby="checkout-correo-error" @enderror
                        >
                        @error('correo')
                            <p class="field__error" id="checkout-correo-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="checkout-telefono">Teléfono (opcional)</label>
                        <input
                            class="input"
                            type="tel"
                            id="checkout-telefono"
                            name="telefono"
                            value="{{ is_string(old('telefono')) ? old('telefono') : '' }}"
                            maxlength="20"
                            autocomplete="tel"
                            @error('telefono') aria-invalid="true" aria-describedby="checkout-telefono-error" @enderror
                        >
                        @error('telefono')
                            <p class="field__error" id="checkout-telefono-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="form-actions">
                    <a class="button button--ghost" href="{{ route('carrito.index') }}">Volver al carrito</a>
                    <button class="button button--primary" type="submit">Registrar pedido</button>
                </div>
            </form>

            <aside class="order-summary">
                <h2>Resumen del pedido</h2>
                <dl>
                    @foreach ($items as $item)
                        <div>
                            <dt>{{ $item['producto']->nombre }} × {{ $item['cantidad'] }}</dt>
                            <dd>${{ number_format($item['subtotal'], 2) }}</dd>
                        </div>
                    @endforeach
                    <div><dt>Total</dt><dd>${{ number_format($total, 2) }}</dd></div>
                </dl>
            </aside>
        </div>
    </section>
@endsection
