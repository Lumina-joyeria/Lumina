<h1>Actualizar clientes</h1>

@if ($errors->any())
    <ul>
        @foreach ($errors->all() as $error)
            <li style="color: #D00;">{{ $error }}</li>
        @endforeach
    </ul>
@endif

<form action="{{ route('clientes.update', $cliente->id) }}" method="POST">
    @csrf
    @method('PATCH')

    <label for="nombre">Nombre:</label>
    <input
        type="text"
        id="nombre"
        name="nombre"
        value="{{ old('nombre', $cliente->nombre) }}"
        required
        maxlength="100"
    >

    <label for="correo">Correo:</label>
    <input
        type="email"
        id="correo"
        name="correo"
        value="{{ old('correo', $cliente->correo) }}"
        required
        maxlength="100"
    >

    <label for="telefono">Telefono:</label>
    <input
        type="tel"
        id="telefono"
        name="telefono"
        value="{{ old('telefono', $cliente->telefono) }}"
        maxlength="20"
    >

    <button type="submit">Actualizar cliente</button>
</form>