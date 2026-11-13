<h1>Categorías de LÚMINA</h1>

@if (session('success'))
    <p style="color: #090">
        {{ session('success')}}
    </p>
@endif

@if (session('error'))
    <p style="color: #D00;">
        {{ session('error') }}
    </p>
@endif

<ul>
    @forelse ($categorias as $categoria)
        <li>
            {{ $categoria->nombre }}
            
            <a href="{{ route('categorias.edit', $categoria) }}">
                Editar
            </a>
            
            <form
                method="POST"
                action="{{ route('categorias.destroy', $categoria) }}"
                style="display: inline;"
                onsubmit="return confirm('¿Seguro que deseas eliminar esta categoría?');"
            >
                @csrf
                @method('DELETE')
                
                <button type="submit">Eliminar</button>
            </form>
        </li>
        
    @empty
        <li>No hay categorías registradas</li>
    @endforelse
</ul>