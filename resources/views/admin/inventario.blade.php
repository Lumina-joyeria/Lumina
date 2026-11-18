@extends('layouts.workspace')
@section('title', 'Inventario') @section('eyebrow', 'Operaciones') @section('page_title', 'Inventario')
@section('content')<div class="page-toolbar"><div><h2>Control de existencias</h2><p>Vista lista para recibir movimientos y alertas de stock.</p></div></div><x-empty-state icon="box" title="Módulo pendiente de backend" description="Esta interfaz se conectará cuando existan la ruta, el controlador y la estructura de datos del inventario." />@endsection
