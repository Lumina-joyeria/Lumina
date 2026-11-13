<h1>Registrar categoría</h1>

<form method="POST" action="{{ route('categorias.store') }}">
    @csrf
    
    <label for="nombre">Nombre:</label>
    <input
        type="text"
        id="nombre"
        name="nombre"
        maxlength="50"
        required
    >
    @error('nombre')
        <p style="color: #D00;">{{ $message }}</p>
    @enderror
    
    <label for="descripcion">Descripción:</label>
    <textarea
        id="descripcion"
        name="descripcion"
        maxlength="255"></textarea>
    @error('descripcion')
        <p style="color: #D00;">{{ $message }}</p>
    @enderror
    
    <button type="submit">Guardar categoría</button>
</form>