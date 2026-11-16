@extends('layouts.workspace')
@section('title', 'Productos')
@section('eyebrow', 'Catálogo')
@section('page_title', 'Productos')
@section('content')
<div class="page-toolbar"><div><h2>Catálogo de piezas</h2><p>{{ $productos->count() }} {{ $productos->count() === 1 ? 'producto registrado' : 'productos registrados' }}</p></div><a class="button button--primary" href="{{ route('productos.create') }}"><x-icon name="plus" size="18" />Nuevo producto</a></div>
@if($productos->isEmpty())
    <x-empty-state icon="gem" title="Aún no hay productos" description="Registra la primera pieza para comenzar a construir el catálogo de LÚMINA." action="Registrar producto" :href="route('productos.create')" />
@else
<section class="data-card"><div class="data-card__toolbar"><label class="search-field"><x-icon name="search" size="18" /><input type="search" placeholder="Buscar por nombre o categoría..." aria-label="Buscar productos" data-table-search></label><span class="data-card__count">{{ $productos->count() }} resultados</span></div>
<div class="table-wrap"><table class="data-table product-table"><thead><tr><th>Producto</th><th>Categoría</th><th>Precio</th><th>Stock</th><th class="table-actions">Acciones</th></tr></thead><tbody>
@foreach($productos as $producto)@php $fallbacks = ['product-necklace.jpg', 'product-ring.jpg', 'product-earrings.jpg', 'product-bracelet.jpg']; $fallbackImage = $fallbacks[((int) $producto->id - 1) % count($fallbacks)]; @endphp<tr data-search-row><td><div class="product-entity"><div class="product-thumb"><img src="{{ $producto->imagen ? asset('storage/' . $producto->imagen) : asset('images/lumina/' . $fallbackImage) }}" alt=""></div><div><strong>{{ $producto->nombre }}</strong><small>{{ Illuminate\Support\Str::limit($producto->description ?: 'Sin descripción', 54) }}</small></div></div></td><td><span class="category-pill">{{ $producto->categoria?->nombre ?? 'Sin categoría' }}</span></td><td><strong>${{ number_format($producto->precio, 2) }}</strong></td><td><span class="stock-pill {{ $producto->stock <= 5 ? 'stock-pill--low' : '' }}"><i></i>{{ $producto->stock }} unidades</span></td><td><div class="row-actions"><a href="{{ route('productos.edit', $producto) }}" aria-label="Editar {{ $producto->nombre }}"><x-icon name="edit" size="17" />Editar</a><form action="{{ route('productos.destroy', $producto) }}" method="POST" data-confirm="¿Eliminar {{ $producto->nombre }}? Esta acción no se puede deshacer.">@csrf @method('DELETE')<button class="danger-action" type="submit"><x-icon name="trash" size="17" />Eliminar</button></form></div></td></tr>@endforeach
</tbody></table></div></section>
@endif
@endsection
