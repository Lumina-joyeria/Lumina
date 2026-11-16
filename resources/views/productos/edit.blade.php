@extends('layouts.workspace')
@section('title', 'Editar producto')
@section('eyebrow', 'Catálogo / Productos')
@section('page_title', 'Editar producto')
@section('content')
<a class="back-link" href="{{ route('productos.index') }}"><x-icon name="arrow-left" size="18" />Volver a productos</a>
<div class="editor-grid"><form class="editor-card" action="{{ route('productos.update', $producto) }}" method="POST">@csrf @method('PATCH')<div class="editor-card__head"><span><x-icon name="edit" /></span><div><h2>{{ $producto->nombre }}</h2><p>Actualiza la información de esta pieza.</p></div></div>@include('productos._form', ['producto' => $producto])<div class="form-actions"><a class="button button--ghost" href="{{ route('productos.index') }}">Cancelar</a><button class="button button--primary" type="submit"><x-icon name="check" size="18" />Guardar cambios</button></div></form><aside class="help-card"><span><x-icon name="info" /></span><h2>Conexión existente</h2><p>Este formulario conserva los campos y reglas actuales del backend: nombre, description, precio, stock e id_categoria.</p></aside></div>
@endsection
