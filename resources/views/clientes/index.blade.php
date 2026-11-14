<h1>Clientes</h1>

@if (session('success'))
    <p style="color: #090;">
        {{ session('success') }}
    </p>
@endif

@if (session('error'))
    <p style="color: #D00;">
        {{ session('error') }}
    </p>
@endif

<a href="{{ route('clientes.create') }}">
    Registrar nuevo cliente
</a>

@forelse ($clientes as $cliente)
    <div>
        <h3>{{ $cliente->nombre }}</h3>

        <p>Nombre: {{ $cliente->nombre }}</p>
        <p>Correo: {{ $cliente->correo }}</p>
        <p>Teléfono: {{ $cliente->telefono ?? 'No registrado' }}</p>
        <p>Fecha de registro: {{ $cliente->fecha_registro }}</p>

        <a href="{{ route('clientes.edit', $cliente->id) }}">
            Editar cliente
        </a>

        <form
            action="{{ route('clientes.destroy', $cliente->id) }}"
            method="POST"
            onsubmit="return confirm('¿Seguro que deseas eliminar este cliente?');"
        >
            @csrf
            @method('DELETE')

            <button type="submit">Eliminar cliente</button>
        </form>
    </div>

    <hr>
@empty
    <p>No hay clientes registrados.</p>
@endforelse
