@extends('layouts.workspace')

@section('title', 'Registrar pedido')
@section('eyebrow', 'Operaciones / Pedidos')
@section('page_title', 'Registrar pedido')

@section('content')
    <a class="back-link" href="{{ route('pedidos.index') }}">
        <x-icon name="arrow-left" size="18" />
        Volver a pedidos
    </a>

    @if ($clientes->isEmpty() || $productos->isEmpty())
        <x-empty-state
            icon="cart"
            title="Aún no puedes registrar un pedido"
            :description="$clientes->isEmpty() ? 'Registra un cliente para continuar.' : 'Necesitas al menos un producto con stock disponible.'"
            :action="$clientes->isEmpty() ? 'Registrar cliente' : null"
            :href="$clientes->isEmpty() ? route('clientes.create') : null"
        />
    @else
        @php
            $filas = old('items', [[]]);
            $filas = is_array($filas) && $filas !== [] ? $filas : [[]];
            $filas = array_filter($filas, fn ($indice) => is_int($indice) && $indice >= 0, ARRAY_FILTER_USE_KEY) ?: [[]];
        @endphp

        <form class="editor-card" action="{{ route('pedidos.store') }}" method="POST" data-order-form>
            @csrf
            <div class="editor-card__head">
                <span><x-icon name="cart" /></span>
                <div>
                    <h2>Cliente y productos</h2>
                    <p>El pedido quedará pendiente hasta que lo confirmes.</p>
                </div>
            </div>

            @if ($errors->has('items'))
                <div class="alert alert--error" role="alert">
                    <span class="alert__icon"><x-icon name="alert" /></span>
                    <p>{{ $errors->first('items') }}</p>
                </div>
            @endif

            <div class="field mb-6">
                <label for="id-cliente">Cliente</label>
                <select class="input" name="id_cliente" id="id-cliente" required
                    @error('id_cliente') aria-invalid="true" aria-describedby="cliente-error" @enderror
                >
                    <option value="">Selecciona un cliente</option>
                    @foreach ($clientes as $cliente)
                        <option value="{{ $cliente->id }}" @selected(old('id_cliente') == $cliente->id)>
                            {{ $cliente->nombre }} · {{ $cliente->correo }}
                        </option>
                    @endforeach
                </select>
                @error('id_cliente')
                    <p class="field__error" id="cliente-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid gap-3" data-order-items>
                @foreach ($filas as $indice => $fila)
                    @include('pedidos._item', ['indice' => $indice, 'fila' => is_array($fila) ? $fila : []])
                @endforeach
            </div>

            <template data-order-template>
                @include('pedidos._item', ['indice' => '__INDEX__', 'fila' => []])
            </template>

            <div class="mt-4 flex flex-wrap items-center justify-between gap-4">
                <button class="button button--ghost" type="button" data-order-add hidden>
                    <x-icon name="plus" size="18" /> Agregar producto
                </button>
                <p class="m-0 font-semibold">Total estimado: <output data-order-total>$0.00</output></p>
            </div>

            <div class="form-actions">
                <a class="button button--ghost" href="{{ route('pedidos.index') }}">Cancelar</a>
                <button class="button button--primary" type="submit">
                    <x-icon name="check" size="18" /> Registrar pedido
                </button>
            </div>
        </form>

        <script src="{{ asset('js/pedidos.js') }}" defer></script>
    @endif
@endsection
