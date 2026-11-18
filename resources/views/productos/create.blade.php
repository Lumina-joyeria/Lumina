<h1>Registrar producto</h1>

<form action="{{ route('productos.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <label for="nombre">Nombre:</label>
    <input
        type="text"
        name="nombre"
        id="nombre"
        value="{{ old('nombre') }}"
        maxlength="100"
        required
    >
    @error('nombre')
        <p>{{ $message }}</p>
    @enderror
    
    <label for="description">Descripción:</label>
    <textarea
        name="description"
        id="description"
    >{{ old('description') }}</textarea>
    @error('description')
        <p>{{$message}}</p>
    @enderror
    
    <label for="precio">Precio</label>
    <input
        type="number"
        name="precio"
        id="precio"
        min="0"
        step="0.01"
        value="{{ old('precio') }}"
        required
    >
    @error('precio')
        <p>{{ $message }}</p>
    @enderror
    
    <label for="imagen">Imagen del producto</label>
    
    <input
        type="file"
        name="imagen"
        id="imagen"
        accept=".jpg,.jpeg,.jfif,.png,.webp"
        required
    >
    
    @error('imagen')
        <p>{{ $message }}</p>
    @enderror
    
    <label for="id_categoria">Categoría</label>
    <select
        name="id_categoria"
        id="id_categoria"
        required
    >
        <option value="">Seleccione una categoría</option>
        @foreach ($categorias as $categoria)
            <option value="{{ $categoria->id }}"
                @selected(old('id_categoria') == $categoria->id)>
                {{ $categoria->nombre }}
            </option>
        @endforeach
    </select>
    @error('id_categoria')
        <p>{{ $message }}</p>
    @enderror
    
    <button type="submit">Guardar producto</button>
</form>

<a href="{{ route('productos.index') }}">Volver al listado</a>