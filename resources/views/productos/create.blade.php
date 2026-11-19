
@extends('layouts.workspace')

@section('title', 'Nuevo producto')
@section('eyebrow', 'Catálogo / Productos')
@section('page_title', 'Nuevo producto')

@section('content')

    <a class="back-link" href="{{ route('productos.index') }}">
        <x-icon name="arrow-left" size="18" />
        Volver a productos
    </a>

    <div class="editor-grid">

        {{-- Formulario de registro --}}
        <form
            class="editor-card"
            action="{{ route('productos.store') }}"
            method="POST"
            enctype="multipart/form-data"
        >
            @csrf

            <div class="editor-card__head">
                <span>
                    <x-icon name="gem" />
                </span>

                <div>
                    <h2>Información del producto</h2>
                    <p>Completa la información de la joya para registrarla en el catálogo.</p>
                </div>
            </div>

            {{-- Campos reutilizables --}}
            @include('productos._form')

            {{-- Botones --}}
            <div class="form-actions">
                <a
                    class="button button--ghost"
                    href="{{ route('productos.index') }}"
                >
                    Cancelar
                </a>

                <button
                    class="button button--primary"
                    type="submit"
                >
                    <x-icon name="check" size="18" />
                    Guardar producto
                </button>
            </div>

        </form>

        {{-- Información adicional --}}
        <aside class="help-card">
            <span>
                <x-icon name="info" />
            </span>

            <h2>Fotografía e inventario</h2>

            <p>
                Selecciona una fotografía JPG, PNG o WebP
                de hasta 5 MB.
            </p>

            <p>
                El producto se registrará con stock inicial de 0.
                Para aumentar las existencias, utiliza
                el módulo de compras de inventario.
            </p>
        </aside>

    </div>

@endsection