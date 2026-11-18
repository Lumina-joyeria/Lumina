@extends('layouts.workspace')
@section('title', 'Editar cliente')
@section('eyebrow', 'Relaciones / Clientes')
@section('page_title', 'Editar cliente')
@section('content')
<a class="back-link" href="{{ route('clientes.index') }}"><x-icon name="arrow-left" size="18" />Volver a clientes</a>
<div class="editor-grid"><form class="editor-card" method="POST" action="{{ route('clientes.update', $cliente) }}">@csrf @method('PATCH')<div class="editor-card__head"><span><x-icon name="edit" /></span><div><h2>{{ $cliente->nombre }}</h2><p>Actualiza sus datos de contacto.</p></div></div>@include('clientes._form', ['cliente' => $cliente])<div class="form-actions"><a class="button button--ghost" href="{{ route('clientes.index') }}">Cancelar</a><button class="button button--primary" type="submit"><x-icon name="check" size="18" />Guardar cambios</button></div></form><aside class="help-card"><span><x-icon name="info" /></span><h2>Conexión existente</h2><p>Este formulario conserva los campos y validaciones actuales del backend: nombre, correo y teléfono.</p></aside></div>
@endsection
