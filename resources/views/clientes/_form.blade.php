<a class="back-link" href="{{ route('clientes.index') }}">
    <x-icon name="arrow-left" size="18" />
    Volver a clientes
</a>

<div class="editor-grid">
    <form
        class="editor-card"
        method="POST"
        action="{{ $cliente ? route('clientes.update', $cliente) : route('clientes.store') }}"
    >
        @csrf
        @if ($cliente)
            @method('PATCH')
        @endif

        <div class="editor-card__head">
            <span>
                <x-icon :name="$cliente ? 'edit' : 'users'" />
            </span>
            <div>
                <h2>{{ $cliente?->nombre ?? 'Información del cliente' }}</h2>
                <p>Los campos marcados con * son obligatorios.</p>
            </div>
        </div>

        <div class="form-grid">
            <div class="field field--wide">
                <label for="nombre">Nombre completo <span aria-hidden="true">*</span></label>
                <input
                    class="input"
                    type="text"
                    id="nombre"
                    name="nombre"
                    value="{{ old('nombre', $cliente?->nombre) }}"
                    maxlength="100"
                    required
                    @error('nombre') aria-invalid="true" aria-describedby="nombre-error" @enderror
                >
                @error('nombre')
                    <p class="field__error" id="nombre-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="field">
                <label for="correo">Correo electrónico <span aria-hidden="true">*</span></label>
                <input
                    class="input"
                    type="email"
                    id="correo"
                    name="correo"
                    value="{{ old('correo', $cliente?->correo) }}"
                    maxlength="100"
                    required
                    @error('correo') aria-invalid="true" aria-describedby="correo-error" @enderror
                >
                @error('correo')
                    <p class="field__error" id="correo-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="field">
                <label for="telefono">Teléfono</label>
                <input
                    class="input"
                    type="tel"
                    id="telefono"
                    name="telefono"
                    value="{{ old('telefono', $cliente?->telefono) }}"
                    maxlength="20"
                    @error('telefono') aria-invalid="true" aria-describedby="telefono-error" @enderror
                >
                @error('telefono')
                    <p class="field__error" id="telefono-error">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="form-actions">
            <a class="button button--ghost" href="{{ route('clientes.index') }}">Cancelar</a>
            <button class="button button--primary" type="submit">
                <x-icon name="check" size="18" />
                {{ $cliente ? 'Guardar cambios' : 'Guardar cliente' }}
            </button>
        </div>
    </form>

    <aside class="help-card">
        <span><x-icon name="info" /></span>
        <h2>Datos de contacto</h2>
        <p>Usa un correo distinto para cada cliente. El teléfono es opcional.</p>
    </aside>
</div>
