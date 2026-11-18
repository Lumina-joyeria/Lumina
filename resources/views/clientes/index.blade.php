@extends('layouts.workspace')
@section('title', 'Clientes')
@section('eyebrow', 'Relaciones')
@section('page_title', 'Clientes')
@section('content')
<div class="page-toolbar"><div><h2>Directorio de clientes</h2><p>{{ $clientes->count() }} {{ $clientes->count() === 1 ? 'cliente registrado' : 'clientes registrados' }}</p></div><a class="button button--primary" href="{{ route('clientes.create') }}"><x-icon name="plus" size="18" />Nuevo cliente</a></div>
@if($clientes->isEmpty())
    <x-empty-state icon="users" title="Aún no hay clientes" description="Registra el primer cliente para comenzar a construir el directorio de LÚMINA." action="Registrar cliente" :href="route('clientes.create')" />
@else
<section class="data-card"><div class="data-card__toolbar"><label class="search-field"><x-icon name="search" size="18" /><input type="search" placeholder="Buscar por nombre, correo o teléfono..." aria-label="Buscar clientes" data-table-search></label><span class="data-card__count">{{ $clientes->count() }} resultados</span></div>
<div class="table-wrap"><table class="data-table"><thead><tr><th>Cliente</th><th>Correo</th><th>Teléfono</th><th class="table-actions">Acciones</th></tr></thead><tbody>
@foreach($clientes as $cliente)<tr data-search-row><td><div class="entity"><span class="entity__icon"><x-icon name="user" /></span><strong>{{ $cliente->nombre }}</strong></div></td><td><span class="muted-text">{{ $cliente->correo }}</span></td><td><span class="muted-text">{{ $cliente->telefono ?: 'No registrado' }}</span></td><td><div class="row-actions"><a href="{{ route('clientes.edit', $cliente) }}" aria-label="Editar {{ $cliente->nombre }}"><x-icon name="edit" size="17" />Editar</a><form method="POST" action="{{ route('clientes.destroy', $cliente) }}" data-confirm="¿Eliminar a {{ $cliente->nombre }}? Esta acción no se puede deshacer.">@csrf @method('DELETE')<button class="danger-action" type="submit"><x-icon name="trash" size="17" />Eliminar</button></form></div></td></tr>@endforeach
</tbody></table></div></section>
@endif
@endsection
