@extends('layouts.workspace')
@section('title', 'Clientes') @section('eyebrow', 'Relaciones') @section('page_title', 'Clientes')
@section('content')<div class="page-toolbar"><div><h2>Directorio de clientes</h2><p>Vista lista para recibir la colección $clientes.</p></div></div><x-empty-state icon="users" title="Módulo pendiente de backend" description="Esta interfaz se conectará cuando existan la ruta, el controlador y los campos definitivos de clientes." />@endsection
