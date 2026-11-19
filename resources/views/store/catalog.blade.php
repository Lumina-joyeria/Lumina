@extends('layouts.app')

@section('title', 'Catálogo | LÚMINA')

@section('content')

    {{-- Encabezado del catálogo --}}
    <header class="store-hero">
        <div class="container">

            <p class="eyebrow">
                Colecciones LÚMINA
            </p>

            <h1>
                Encuentra tu próximo destello
            </h1>

            <p>
                Explora piezas elegidas para acompañar
                cada gesto, celebración y recuerdo.
            </p>

        </div>
    </header>

    {{-- Sección principal --}}
    <section class="store-section">

        <div class="container catalog-layout">

            {{-- Panel de filtros --}}
            <aside class="filters">

                <div class="filters__head">
                    <h2>Filtros</h2>

                    <span>
                        {{ $productos->count() }} piezas
                    </span>
                </div>

                <form
                    id="catalog-form"
                    method="GET"
                    action="{{ route('catalogo.index') }}"
                >

                    {{-- Buscar por nombre --}}
                    <div class="field">

                        <label for="buscar">
                            Buscar
                        </label>

                        <input
                            class="input"
                            id="buscar"
                            name="buscar"
                            type="search"
                            value="{{ request('buscar') }}"
                            placeholder="Nombre del producto"
                            maxlength="100"
                        >

                    </div>

                    {{-- Filtrar por categorías --}}
                    <fieldset>

                        <legend>
                            Categorías
                        </legend>

                        @foreach ($categorias as $categoria)

                            <label class="check-row">

                                <input
                                    type="checkbox"
                                    name="categorias[]"
                                    value="{{ $categoria->id }}"
                                    @checked(
                                        in_array(
                                            (string) $categoria->id,
                                            (array) request('categorias', []),
                                            false
                                        )
                                    )
                                >

                                <span>
                                    {{ $categoria->nombre }}
                                </span>

                            </label>

                        @endforeach

                    </fieldset>

                    {{-- Aplicar búsqueda y filtros --}}
                    <button
                        class="button button--primary button--full"
                        type="submit"
                    >
                        Aplicar filtros
                    </button>

                    {{-- Limpiar filtros --}}
                    @if (
                        request()->filled('buscar') ||
                        request()->filled('categorias') ||
                        request()->filled('orden')
                    )

                        <a
                            class="button button--full"
                            href="{{ route('catalogo.index') }}"
                        >
                            Limpiar filtros
                        </a>

                    @endif

                </form>

            </aside>

            {{-- Resultados del catálogo --}}
            <div>

                <div class="catalog-toolbar">

                    <p>
                        {{ $productos->count() }}
                        {{ $productos->count() === 1 ? 'resultado' : 'resultados' }}
                    </p>

                    {{-- Ordenamiento --}}
                    <label for="orden">

                        Ordenar

                        <select
                            class="input"
                            id="orden"
                            name="orden"
                            form="catalog-form"
                        >

                            <option
                                value="recientes"
                                @selected(
                                    request('orden', 'recientes') === 'recientes'
                                )
                            >
                                Más recientes
                            </option>

                            <option
                                value="precio_asc"
                                @selected(
                                    request('orden') === 'precio_asc'
                                )
                            >
                                Menor precio
                            </option>

                            <option
                                value="precio_desc"
                                @selected(
                                    request('orden') === 'precio_desc'
                                )
                            >
                                Mayor precio
                            </option>

                        </select>

                    </label>

                </div>

                {{-- Tarjetas de productos --}}
                <div class="product-grid">

                    @forelse ($productos as $producto)

                        <x-product-card
                            :producto="$producto"
                            :href="route('catalogo.show', $producto)"
                        />

                    @empty

                        <x-empty-state
                            icon="search"
                            title="No encontramos piezas"
                            description="Prueba con otra búsqueda o elimina algunos filtros."
                        />

                    @endforelse

                </div>

            </div>

        </div>

    </section>

@endsection
