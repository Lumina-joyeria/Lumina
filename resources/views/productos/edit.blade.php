
@extends('layouts.workspace')

@section('title', 'Editar producto')
@section('eyebrow', 'Catálogo / Productos')
@section('page_title', 'Editar producto')

@section('content')

    <a class="back-link" href="{{ route('productos.index') }}">
        <x-icon name="arrow-left" size="18" />
        Volver a productos
    </a>

    <div class="editor-grid">

        <form
            class="editor-card"
            action="{{ route('productos.update', $producto) }}"
            method="POST"
            enctype="multipart/form-data"
        >
            @csrf
            @method('PATCH')

            <div class="editor-card__head">
                <span>
                    <x-icon name="edit" />
                </span>

                <div>
                    <h2>{{ $producto->nombre }}</h2>
                    <p>Actualiza la información de esta pieza.</p>
                </div>
            </div>

            @if ($producto->imagen)
                <div class="field field--wide">
                    <label>Fotografía actual</label>

                    <img
                        src="{{ asset('storage/' . $producto->imagen) }}"
                        alt="Fotografía de {{ $producto->nombre }}"
                        style="
                            width: 180px;
                            height: 180px;
                            object-fit: cover;
                            border-radius: 12px;
                        "
                    >
                </div>
            @endif

            @include('productos._form', ['producto' => $producto])

            <div class="form-actions">
                <a
                    class="button button--ghost"
                    href="{{ route('productos.index') }}"
                >
                    Cancelar
                </a>

                <button class="button button--primary" type="submit">
                    <x-icon name="check" size="18" />
                    Guardar cambios
                </button>
            </div>
        </form>

        <aside class="help-card">
            <span>
                <x-icon name="info" />
            </span>

            <h2>Edición del producto</h2>

            <p>
                Puedes actualizar el nombre, precio,
                categoría y descripción del producto.
            </p>

            <p>
                Si deseas reemplazar la fotografía,
                selecciona una nueva imagen.
                Si no seleccionas ninguna,
                se conservará la actual.
            </p>

            <p>
                Las existencias se administran desde
                el módulo de compras de inventario.
            </p>
        </aside>

    </div>

@endsection
