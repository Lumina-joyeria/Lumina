@extends('layouts.workspace')
@section('title', 'Categorías')
@section('eyebrow', 'Catálogo')
@section('page_title', 'Categorías')
@section('content')
<div class="page-toolbar"><div><h2>Organiza tus colecciones</h2><p>{{ $categorias->count() }} {{ $categorias->count() === 1 ? 'categoría registrada' : 'categorías registradas' }}</p></div><a class="button button--primary" href="{{ route('categorias.create') }}"><x-icon name="plus" size="18" />Nueva categoría</a></div>
@if($categorias->isEmpty())
    <x-empty-state icon="tag" title="Aún no hay categorías" description="Crea la primera categoría para empezar a organizar las piezas de LÚMINA." action="Crear categoría" :href="route('categorias.create')" />
@else
<section class="data-card"><div class="data-card__toolbar"><label class="search-field"><x-icon name="search" size="18" /><input type="search" placeholder="Buscar categoría..." aria-label="Buscar categoría" data-table-search></label><span class="data-card__count">{{ $categorias->count() }} resultados</span></div>
    <div class="table-wrap"><table class="data-table"><thead><tr><th>Categoría</th><th>Descripción</th><th class="table-actions">Acciones</th></tr></thead><tbody>
    @foreach($categorias as $categoria)<tr data-search-row><td><div class="entity"><span class="entity__icon"><x-icon name="tag" /></span><strong>{{ $categoria->nombre }}</strong></div></td><td><span class="muted-text">{{ $categoria->descripcion ?: 'Sin descripción' }}</span></td><td><div class="row-actions"><a href="{{ route('categorias.edit', $categoria) }}" aria-label="Editar {{ $categoria->nombre }}"><x-icon name="edit" size="17" />Editar</a><form method="POST" action="{{ route('categorias.destroy', $categoria) }}" data-confirm="¿Eliminar la categoría {{ $categoria->nombre }}? Esta acción no se puede deshacer.">@csrf @method('DELETE')<button class="danger-action" type="submit" aria-label="Eliminar {{ $categoria->nombre }}"><x-icon name="trash" size="17" />Eliminar</button></form></div></td></tr>@endforeach
    </tbody></table></div>
</section>
@endif
@endsection
