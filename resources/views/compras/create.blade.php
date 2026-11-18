<h1>Registrar compra de inventario</h1>

@if (session('success'))
    <p style="color: #090;">
        {{ session('success') }}
    </p>
@endif

@if ($errors->any())
    <ul style="color: #D00;">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

<form action="{{ route('compras.store') }}" method="POST">
    @csrf

    <div>
        <label for="id_producto">Producto:</label>

        <select name="id_producto" id="id_producto" required>
            <option value="">Seleccione un producto</option>

            @foreach ($productos as $producto)
                <option
                    value="{{ $producto->id }}"
                    @selected(old('id_producto') == $producto->id)
                >
                    {{ $producto->nombre }}
                    — Stock actual: {{ $producto->stock }}
                </option>
            @endforeach
        </select>
    </div>

    <br>

    <div>
        <label for="cantidad">Cantidad comprada:</label>

        <input
            type="number"
            name="cantidad"
            id="cantidad"
            min="1"
            max="1000"
            value="{{ old('cantidad', 1) }}"
            required
        >
    </div>

    <br>

    <div>
        <label for="costo_unitario">Costo por unidad ($):</label>

        <input
            type="number"
            name="costo_unitario"
            id="costo_unitario"
            min="0.01"
            max="99999"
            step="0.01"
            value="{{ old('costo_unitario') }}"
            required
        >
    </div>

    <br>

    <button type="submit">Registrar compra</button>
</form>