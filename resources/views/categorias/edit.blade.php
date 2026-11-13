<h1>Editar categoría</h1>
<form method="post" action="{{ route('categorias.update', $categoria) }}">
    @csrf
    @method('PATCH')
    
    <label for="nombre">Nombre</label>
        <input
        type="text"
        id="nombre"
        name="nombre"
        value="{{ $categoria->nombre }}"
        maxlength="50"
        required
    >
    @error('nombre')
        <p style="color: #D00;">{{ $message }}</p>
    @enderror

    <label for="descripcion">Descripción</label>
    <textarea
        id="descripcion"
        name="descripcion"
        maxlength="255" 
    >{{ $categoria->descripcion }}</textarea>
    @error('descripcion')
        <p style="color: #D00;">{{ $message }}</p>
    @enderror
    
    <button type="submit">Guardar cambios</button>
</form>