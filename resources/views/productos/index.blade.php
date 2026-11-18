<h1>Listado de productos</h1>

@if (session('success'))
    <p>{{ session('success') }}</p>
@endif

@if (session('error'))
    <p style="color: #D00;">{{ session('error') }}</p>
@endif

<a href="{{ route('productos.create') }}">Registrar producto</a>

@forelse ($productos as $producto)
    <div>
        <h3>{{ $producto->nombre }}</h3>
        
        @if ($producto->imagen)
            <img
                src="{{ asset('storage/' . $producto->imagen) }}"
                alt="{{ $producto->nombre }}"
                width="180"
            >
        @else
            <p>Producto sin fotografía.</p>
        @endif
        
        <p>Categoría: {{ $producto->categoria->nombre }}</p>
        <p>Precio: ${{ number_format($producto->precio, 2)}}</p>
        <p>Stock: {{ $producto->stock }}</p>
        <p>Descripción: {{ $producto->description }}</p>
        <a href="{{ route('productos.edit', $producto->id) }}">
            Editar producto
        </a>
        <form
            action="{{ route('productos.destroy', $producto->id) }}"
            method="POST"
            onsubmit="return confirm('Seguro que deseas eliminar este producto?');"
        >
            @csrf
            @method('DELETE')
            
            <button type="submit">Eliminar producto</button>
        </form>
    </div>
    <hr>
@empty
    <p>No hay productos registrados.</p>
@endforelse