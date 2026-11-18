@extends('layouts.workspace')
@section('title', 'Nuevo producto')
@section('eyebrow', 'Catálogo / Productos')
@section('page_title', 'Nuevo producto')
@section('content')
<a class="back-link" href="{{ route('productos.index') }}"><x-icon name="arrow-left" size="18" />Volver a productos</a>
<div class="editor-grid"><form class="editor-card" action="{{ route('productos.store') }}" method="POST">@csrf<div class="editor-card__head"><span><x-icon name="gem" /></span><div><h2>Información del producto</h2><p>Los campos marcados con * son obligatorios.</p></div></div>@include('productos._form')<div class="form-actions"><a class="button button--ghost" href="{{ route('productos.index') }}">Cancelar</a><button class="button button--primary" type="submit"><x-icon name="check" size="18" />Guardar producto</button></div></form><aside class="help-card"><span><x-icon name="info" /></span><h2>Imagen del producto</h2><p>El modelo ya contempla el campo <strong>imagen</strong>, pero el controlador todavía no procesa archivos. El selector visual se añadirá cuando Zahid habilite esa validación y almacenamiento.</p></aside></div>
@endsection
