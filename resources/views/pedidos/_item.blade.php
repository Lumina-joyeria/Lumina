@php
    $idProducto = $fila['id_producto'] ?? '';
    $cantidad = $fila['cantidad'] ?? 1;
    $productoSeleccionado = is_scalar($idProducto) ? (string) $idProducto : '';
    $cantidadIngresada = is_scalar($cantidad) ? $cantidad : '';
    $errorProducto = "items.{$indice}.id_producto";
    $errorCantidad = "items.{$indice}.cantidad";
@endphp

<fieldset class="rounded-md border border-[var(--line)] p-4" data-order-row data-index="{{ $indice }}">
    <legend class="px-2 font-semibold">Producto <span data-order-number></span></legend>
    <div class="form-grid">
        <div class="field">
            <label for="producto-{{ $indice }}">Joya</label>
            <select class="input" id="producto-{{ $indice }}" name="items[{{ $indice }}][id_producto]" data-order-product required
                @error($errorProducto) aria-invalid="true" aria-describedby="producto-error-{{ $indice }}" @enderror
            >
                <option value="">Selecciona un producto</option>
                @if ($productoSeleccionado !== '' && !$productos->contains('id', $productoSeleccionado))
                    <option value="" disabled selected>Producto anterior no disponible</option>
                @endif
                @foreach ($productos as $producto)
                    <option
                        value="{{ $producto->id }}"
                        data-price-cents="{{ (int) round($producto->precio * 100) }}"
                        data-stock="{{ $producto->stock }}"
                        @selected((string) $producto->id === $productoSeleccionado)
                    >
                        {{ $producto->nombre }} · {{ $producto->stock }} disponibles
                    </option>
                @endforeach
            </select>
            @error($errorProducto)
                <p class="field__error" id="producto-error-{{ $indice }}">{{ $message }}</p>
            @enderror
        </div>

        <div class="field">
            <label for="cantidad-{{ $indice }}">Cantidad</label>
            <input
                class="input"
                type="number"
                id="cantidad-{{ $indice }}"
                name="items[{{ $indice }}][cantidad]"
                value="{{ $cantidadIngresada }}"
                min="1"
                step="1"
                data-order-quantity
                required
                @error($errorCantidad) aria-invalid="true" aria-describedby="cantidad-error-{{ $indice }}" @enderror
            >
            @error($errorCantidad)
                <p class="field__error" id="cantidad-error-{{ $indice }}">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="mt-4 flex flex-wrap items-center gap-x-5 gap-y-2">
        <p class="m-0">Precio unitario: <output data-order-price>—</output></p>
        <p class="m-0">Subtotal: <output data-order-subtotal>—</output></p>
        <button class="button button--ghost" type="button" data-order-remove hidden>
            <x-icon name="trash" size="16" /> Quitar producto
        </button>
    </div>
</fieldset>
