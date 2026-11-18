@extends('layouts.workspace')
@section('title', 'Editar categoría')
@section('eyebrow', 'Catálogo / Categorías')
@section('page_title', 'Editar categoría')
@section('content')
<a class="back-link" href="{{ route('categorias.index') }}"><x-icon name="arrow-left" size="18" />Volver a categorías</a>
<div class="editor-grid"><form class="editor-card" method="POST" action="{{ route('categorias.update', $categoria) }}">@csrf @method('PATCH')<div class="editor-card__head"><span><x-icon name="edit" /></span><div><h2>{{ $categoria->nombre }}</h2><p>Actualiza la información conservando la estructura actual.</p></div></div>@include('categorias._form', ['categoria' => $categoria])<div class="form-actions"><a class="button button--ghost" href="{{ route('categorias.index') }}">Cancelar</a><button class="button button--primary" type="submit"><x-icon name="check" size="18" />Guardar cambios</button></div></form><aside class="help-card"><span><x-icon name="info" /></span><h2>Antes de guardar</h2><p>Los productos asociados conservarán esta categoría. El backend valida que el nombre no esté repetido.</p></aside></div>
@endsection
