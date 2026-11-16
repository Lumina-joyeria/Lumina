@extends('layouts.workspace')
@section('title', 'Nuevo cliente')
@section('eyebrow', 'Relaciones / Clientes')
@section('page_title', 'Nuevo cliente')
@section('content')
<a class="back-link" href="{{ route('clientes.index') }}"><x-icon name="arrow-left" size="18" />Volver a clientes</a>
<div class="editor-grid"><form class="editor-card" method="POST" action="{{ route('clientes.store') }}">@csrf<div class="editor-card__head"><span><x-icon name="users" /></span><div><h2>Información del cliente</h2><p>Los campos marcados con * son obligatorios.</p></div></div>@include('clientes._form')<div class="form-actions"><a class="button button--ghost" href="{{ route('clientes.index') }}">Cancelar</a><button class="button button--primary" type="submit"><x-icon name="check" size="18" />Guardar cliente</button></div></form><aside class="help-card"><span><x-icon name="info" /></span><h2>Datos de contacto</h2><p>El correo debe ser único. El teléfono es opcional y puede tener hasta 20 caracteres.</p></aside></div>
@endsection
