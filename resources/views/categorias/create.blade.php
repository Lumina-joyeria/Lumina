@extends('layouts.workspace')
@section('title', 'Nueva categoría')
@section('eyebrow', 'Catálogo / Categorías')
@section('page_title', 'Nueva categoría')
@section('content')
<a class="back-link" href="{{ route('categorias.index') }}"><x-icon name="arrow-left" size="18" />Volver a categorías</a>
<div class="editor-grid"><form class="editor-card" method="POST" action="{{ route('categorias.store') }}">@csrf<div class="editor-card__head"><span><x-icon name="tag" /></span><div><h2>Información de la categoría</h2><p>Los campos marcados con * son obligatorios.</p></div></div>@include('categorias._form')<div class="form-actions"><a class="button button--ghost" href="{{ route('categorias.index') }}">Cancelar</a><button class="button button--primary" type="submit"><x-icon name="check" size="18" />Guardar categoría</button></div></form><aside class="help-card"><span><x-icon name="info" /></span><h2>Una estructura clara</h2><p>Usa nombres breves y descripciones útiles. Esto facilitará la búsqueda y el filtrado del catálogo público cuando esté conectado.</p></aside></div>
@endsection
