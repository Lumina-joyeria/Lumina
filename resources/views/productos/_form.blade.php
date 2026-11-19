
<div class="form-grid">

    {{-- Nombre del producto --}}
    <div class="field field--wide">
        <label for="nombre">
            Nombre del producto 
        </label>

        <input
            class="input"
            type="text"
            name="nombre"
            id="nombre"
            value="{{ old('nombre', $producto->nombre ?? '') }}"
            maxlength="100"
            required
        >

        @error('nombre')
            <p class="field__error">
                {{ $message }}
            </p>
        @enderror
    </div>

    {{-- Precio --}}
    <div class="field">
        <label for="precio">
            Precio 
        </label>

        <div class="input-prefix">
            <span>$</span>

            <input
                class="input"
                type="number"
                name="precio"
                id="precio"
                min="0"
                max="99999999.99"
                step="0.01"
                value="{{ old('precio', $producto->precio ?? '') }}"
                placeholder="0.00"
                required
            >
        </div>

        @error('precio')
            <p class="field__error">
                {{ $message }}
            </p>
        @enderror
    </div>

    {{-- Categoría --}}
    <div class="field field--wide">
        <label for="id_categoria">
            Categoría 
        </label>

        <select
            class="input"
            name="id_categoria"
            id="id_categoria"
            required
        >
            <option value="">
                Selecciona una categoría
            </option>

            @foreach ($categorias as $categoria)
                <option
                    value="{{ $categoria->id }}"
                    @selected(
                        old('id_categoria', $producto->id_categoria ?? '') == $categoria->id
                    )
                >
                    {{ $categoria->nombre }}
                </option>
            @endforeach
        </select>

        @error('id_categoria')
            <p class="field__error">
                {{ $message }}
            </p>
        @enderror
    </div>

    {{-- Fotografía del producto --}}
    <div class="field field--wide">
        <label for="imagen">
            Fotografía del producto

            @if (!isset($producto))
                
            @endif
        </label>

        <input
            class="input"
            type="file"
            name="imagen"
            id="imagen"
            accept="image/jpeg,image/png,image/webp"
            @if (!isset($producto)) required @endif
        >

        <p class="field__hint">
            Formatos permitidos: JPG, PNG y WebP.
            Tamaño máximo: 5 MB.
        </p>

        @error('imagen')
            <p class="field__error">
                {{ $message }}
            </p>
        @enderror
    </div>

    {{-- Descripción --}}
    <div class="field field--wide">
        <label for="description">
            Descripción
        </label>

        <textarea
            class="input input--textarea"
            name="description"
            id="description"
            rows="6"
            required
        >{{ old('description', $producto->description ?? '') }}</textarea>

        @error('description')
            <p class="field__error">
                {{ $message }}
            </p>
        @enderror
    </div>

</div>
