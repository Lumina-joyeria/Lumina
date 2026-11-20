@extends('layouts.workspace')

@section('title', 'Clientes')
@section('eyebrow', 'Relaciones')
@section('page_title', 'Clientes')

@section('content')
    <div class="page-toolbar">
        <div>
            <h2>Directorio de clientes</h2>
            <p>{{ $clientes->count() }} {{ $clientes->count() === 1 ? 'cliente registrado' : 'clientes registrados' }}</p>
        </div>
        <a class="button button--primary" href="{{ route('clientes.create') }}">
            <x-icon name="plus" size="18" />
            Nuevo cliente
        </a>
    </div>

    @if ($clientes->isEmpty())
        <x-empty-state
            icon="users"
            title="Aún no hay clientes"
            description="Registra el primer cliente para comenzar el directorio."
            action="Registrar cliente"
            :href="route('clientes.create')"
        />
    @else
        <section class="data-card" aria-label="Clientes registrados">
            <div class="data-card__toolbar">
                <label class="search-field">
                    <x-icon name="search" size="18" />
                    <input
                        type="search"
                        placeholder="Buscar por nombre, correo o teléfono..."
                        aria-label="Buscar clientes"
                        aria-controls="tabla-clientes"
                        data-table-search
                    >
                </label>
                <span class="data-card__count" role="status" data-table-search-count>
                    {{ $clientes->count() }} {{ $clientes->count() === 1 ? 'resultado' : 'resultados' }}
                </span>
            </div>

            <div class="table-wrap">
                <table class="data-table" id="tabla-clientes">
                    <thead>
                        <tr>
                            <th scope="col">Cliente</th>
                            <th scope="col">Correo</th>
                            <th scope="col">Teléfono</th>
                            <th scope="col" class="table-actions">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($clientes as $cliente)
                            <tr data-search-row data-search-text="{{ implode(' ', [$cliente->nombre, $cliente->correo, $cliente->telefono]) }}">
                                <td>
                                    <div class="entity">
                                        <span class="entity__icon"><x-icon name="user" /></span>
                                        <strong>{{ $cliente->nombre }}</strong>
                                    </div>
                                </td>
                                <td><span class="muted-text">{{ $cliente->correo }}</span></td>
                                <td><span class="muted-text">{{ $cliente->telefono ?: 'No registrado' }}</span></td>
                                <td>
                                    <div class="row-actions">
                                        <a href="{{ route('clientes.edit', $cliente) }}" aria-label="Editar {{ $cliente->nombre }}">
                                            <x-icon name="edit" size="17" />
                                            Editar
                                        </a>
                                        @if ($cliente->pedidos_exists)
                                            <span class="muted-text">Tiene pedidos</span>
                                        @else
                                            <form
                                                method="POST"
                                                action="{{ route('clientes.destroy', $cliente) }}"
                                                data-confirm="¿Eliminar a {{ $cliente->nombre }}? Esta acción no se puede deshacer."
                                            >
                                                @csrf
                                                @method('DELETE')
                                                <button class="danger-action" type="submit" aria-label="Eliminar {{ $cliente->nombre }}">
                                                    <x-icon name="trash" size="17" />
                                                    Eliminar
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        <tr data-table-search-empty hidden>
                            <td colspan="4">No hay clientes que coincidan con la búsqueda.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    @endif
@endsection
