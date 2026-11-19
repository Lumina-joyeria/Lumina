@extends('layouts.workspace')

@section('title', 'Compra de inventario')
@section('eyebrow', 'Inventario / Compras')
@section('page_title', 'Registrar compra de inventario')

@section('content')

    <div class="editor-grid">

        {{-- Formulario de compra --}}
        <form
            class="editor-card"
            action="{{ route('compras.store') }}"
            method="POST"
        >
            @csrf

            <div class="editor-card__head">
                <span>
                    <x-icon name="package" />
                </span>

                <div>
                    <h2>Abastecimiento de productos</h2>

                    <p>
                        Registra las unidades adquiridas
                        para aumentar las existencias.
                    </p>
                </div>
            </div>

            <div class="form-grid">

                {{-- Selección del producto --}}
                <div class="field field--wide">
                    <label for="id_producto">
                        Producto
                    </label>

                    <select
                        class="input"
                        name="id_producto"
                        id="id_producto"
                        required
                    >
                        <option value="">
                            Selecciona un producto
                        </option>

                        @foreach ($productos as $producto)
                            <option
                                value="{{ $producto->id }}"
                                @selected(
                                    old('id_producto') == $producto->id
                                )
                            >
                                {{ $producto->nombre }}
                                — Stock actual: {{ $producto->stock }}
                            </option>
                        @endforeach
                    </select>

                    @error('id_producto')
                        <p class="field__error">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Cantidad --}}
                <div class="field">
                    <label for="cantidad">
                        Cantidad comprada
                    </label>

                    <input
                        class="input"
                        type="number"
                        name="cantidad"
                        id="cantidad"
                        min="1"
                        max="1000"
                        step="1"
                        value="{{ old('cantidad', 1) }}"
                        required
                    >

                    @error('cantidad')
                        <p class="field__error">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Costo unitario --}}
                <div class="field">
                    <label for="costo_unitario">
                        Costo por unidad ($)
                    </label>

                    <div class="input-prefix">
                        <span>$</span>

                        <input
                            class="input"
                            type="number"
                            name="costo_unitario"
                            id="costo_unitario"
                            min="0.01"
                            max="99999"
                            step="0.01"
                            value="{{ old('costo_unitario') }}"
                            placeholder="0.00"
                            required
                        >
                    </div>

                    @error('costo_unitario')
                        <p class="field__error">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Total estimado --}}
                <div class="field field--wide">
                    <label for="total_estimado">
                        Total estimado de la compra
                    </label>

                    <div class="input-prefix">
                        <span>$</span>

                        <input
                            class="input"
                            type="text"
                            id="total_estimado"
                            value="0.00"
                            readonly
                        >
                    </div>

                    <p class="field__hint">
                        Este valor es informativo.
                        El servidor calculará el costo definitivo.
                    </p>
                </div>

            </div>

            {{-- Acciones --}}
            <div class="form-actions">
                <a
                    class="button button--ghost"
                    href="{{ url('/panel') }}"
                >
                    Cancelar
                </a>

                <button
                    class="button button--primary"
                    type="submit"
                >
                    <x-icon name="check" size="18" />
                    Registrar compra
                </button>
            </div>

        </form>

        {{-- Información adicional --}}
        <aside class="help-card">
            <span>
                <x-icon name="info" />
            </span>

            <h2>Control de inventario</h2>

            <p>
                Cada compra registrada aumenta
                automáticamente las existencias
                del producto seleccionado.
            </p>

            <p>
                El costo total de la compra también
                se registra como un gasto
                en los movimientos financieros.
            </p>

            <p>
                Esta operación no modifica
                el precio de venta del producto.
            </p>
        </aside>

    </div>

    {{-- Cálculo visual del total --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            const cantidad = document.getElementById('cantidad');
            const costo = document.getElementById('costo_unitario');
            const total = document.getElementById('total_estimado');

            function actualizarTotal() {
                const unidades = Number(cantidad.value) || 0;
                const precio = Number(costo.value) || 0;

                total.value = (unidades * precio).toFixed(2);
            }

            cantidad.addEventListener('input', actualizarTotal);
            costo.addEventListener('input', actualizarTotal);

            actualizarTotal();
        });
    </script>

@endsection