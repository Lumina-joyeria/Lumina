<h1>Listado de productos</h1>

@if (session('success'))
    <p>{{ session('success') }}</p>
@endif

<a href="{{ route('productos.create') }}">Registrar producto</a>

@forelse ($productos as $producto)
    <div>
        <h3>{{ $producto->nombre }}</h3>
        
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