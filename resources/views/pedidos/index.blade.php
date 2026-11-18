<h1>Pedidos registrados</h1>
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

<a href="{{ route('pedidos.create') }}">
    Registrar nuevo pedido
</a>

@forelse ($pedidos as $pedido)
    <hr>
    
    <h3>Pedido #{{ $pedido->id }}</h3>
    
    <p>Cliente: {{ $pedido->cliente->nombre }}</p>
    <p>Fecha: {{ $pedido->fecha_pedido }}</p>
    <p>Estado: {{ $pedido->estado }}</p>
    
    @if ($pedido->estado === 'Pendiente')
        <form action="{{ route('pedidos.confirmar', $pedido->id) }}" method="POST">
            @csrf
            @method('PATCH')
            
            <button type="submit">
                Confirmar pago
            </button>
        </form>
    @endif
    
    @if ($pedido->estado === 'Pendiente')
        <form action="{{ route('pedidos.cancelar', $pedido->id) }}" method="POST">
            @csrf
            @method('PATCH')
            
            <button type="submit">Cancelar pedido</button>
        </form>
    @endif
    
    @if ($pedido->estado === 'Confirmado')
        <form action="{{ route('pedidos.entregar', $pedido->id) }}" method="POST">
            @csrf
            @method('PATCH')
            
            <button type="submit">Marcar como entregado</button>
        </form>
    @endif
    
    <h4>Productos:</h4>
    
    <ul>
        @foreach ($pedido->detalles as $detalle)
            <li>
                {{ $detalle->producto->nombre }}
                — Cantidad: {{ $detalle->cantidad }}
                — Precio unitario: ${{ number_format($detalle->precio_unitario, 2) }}
                — Subtotal: ${{ number_format($detalle->cantidad * $detalle->precio_unitario, 2) }}
            </li>
        @endforeach
    </ul>
    
    @php
        $total = $pedido->detalles->sum(
            fn ($detalle) => $detalle->cantidad * $detalle->precio_unitario
        );
    @endphp
    
    <p>
        <strong>Total: ${{ number_format($total, 2) }}</strong>
    </p>

@empty
    <p>No hay pedidos registrados.</p>
@endforelse
