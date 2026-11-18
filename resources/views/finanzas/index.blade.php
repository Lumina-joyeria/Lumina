<h1>Panel financiero de LÚMINA</h1>

@if (session('success'))
    <p style="color: #090;">{{ session('success') }}</p>
@endif

@if ($errors->any())
    <ul style="color: #D00;">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

<h2>Resumen financiero</h2>

<p>Ingresos: ${{ number_format($ingresos, 2) }}</p>
<p>Gastos: ${{ number_format($gastos, 2) }}</p>
<p><strong>Balance: ${{ number_format($balance, 2) }}</strong></p>

<hr>

<h2>Registrar gasto</h2>

<form action="{{ route('finanzas.store') }}" method="POST">
    @csrf
    
    <label for="concepto">Concepto:</label>
    <input
        type="text"
        name="concepto"
        id="concepto"
        value="{{ old('concepto') }}"
        maxlength="255"
        required
    >
    
    <label for="monto">Monto:</label>
    <input
        type="number"
        name="monto"
        id="monto"
        value="{{ old('monto') }}"
        min="0.01"
        step="0.01"
        required
    >
    
    <button type="submit">Registrar gasto</button>
</form>

<hr>

<h2>Historial de movimientos</h2>

<table border="1" cellpadding="8">
    <thead>
        <tr>
            <th>Tipo</th>
            <th>Concepto</th>
            <th>Monto</th>
            <th>Fecha</th>
            <th>Pedido</th>
        </tr>
    </thead>
    
    <tbody>
        @forelse ($movimientos as $movimiento)
            <tr>
                <td>{{ $movimiento->tipo }}</td>
                <td>{{ $movimiento->concepto }}</td>
                <td>${{ number_format($movimiento->monto, 2) }}</td>
                <td>{{ $movimiento->fecha_movimiento }}</td>
                <td>{{ $movimiento->id_pedido ?? '—' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="5">No hay movimientos registrados.</td>
            </tr>
        @endforelse
    </tbody>
</table>