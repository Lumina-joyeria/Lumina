<h1>Registrar pedido</h1>
@if (session('success'))
    <p style="color: #090;">
        {{ session('success') }}
    </p>
@endif

@if ($errors->any())
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

<form action="{{ route('pedidos.store') }}" method="POST">
    @csrf
    
    <label for="id_cliente">Cliente:</label>
    <select name="id_cliente" id="id_cliente" required>
        <option value="">Seleccione un cliente</option>
        
        @foreach ($clientes as $cliente)
            <option value="{{ $cliente->id }}">
                {{ $cliente->nombre }}
            </option>
        @endforeach
    </select>
    
    <hr>
    
    <div id="lista-productos">
        <div class="fila-producto">
            
            
            <h3>Producto 1</h3>
            
            <label for="id_producto">Joya:</label>
            
            <select name="items[0][id_producto]" id="id_producto" required>
                <option value="">Seleccione un producto</option>
                
                @foreach ($productos as $producto)
            <option value="{{ $producto->id }}">
                {{ $producto->nombre }}
                - ${{ $producto->precio }}
                (Stock: {{ $producto->stock }})
            </option>
            @endforeach
        </select>
        
        <label for="cantidad">Cantidad</label>
        
        <input
        type="number"
        name="items[0][cantidad]"
        id="cantidad"
        min="1"
        value="1"
        required
        >
        
        </div>
    </div>
    
    <button type="button" id="agregar-producto">
        + Añadir otro producto
    </button>
    
    <hr>
    
    <button type="submit">Registrar pedido</button>
</form>

<script>
    const contenedor = document.getElementById('lista-productos');
    const botonAgregar = document.getElementById('agregar-producto');
    
    let indice = 1;
    
    botonAgregar.addEventListener('click', function () {
        
        const primeraFila = contenedor.querySelector('.fila-producto');
        const nuevaFila = primeraFila.cloneNode(true);
        
        nuevaFila.querySelector('h3').textContent = `Producto ${indice + 1}`;
        
        const select = nuevaFila.querySelector('select');
        select.name = `items[${indice}][id_producto]`;
        select.id = `id_producto_${indice}`;
        select.value = '';
        
        const cantidad = nuevaFila.querySelector('input');
        cantidad.name = `items[${indice}][cantidad]`;
        cantidad.id = `cantidad_${indice}`;
        cantidad.value = 1;
        
        nuevaFila.querySelector('label[for="id_producto"]')
            .setAttribute('for', select.id);
        
        nuevaFila.querySelector('label[for="cantidad"]')
            .setAttribute('for', cantidad.id);
        
        const botonQuitar = document.createElement('button');
        botonQuitar.type = 'button';
        botonQuitar.textContent = 'Quitar producto';
        
        botonQuitar.addEventListener('click', function () {
            nuevaFila.remove();
        });
        
        nuevaFila.appendChild(botonQuitar);
        contenedor.appendChild(nuevaFila);
        
        indice++;
    });
</script>