@if (session('success'))
    <div class="alert alert--success" role="status" data-dismissible>
        <span class="alert__icon"><x-icon name="check" /></span>
        <div><strong>Todo listo</strong><p>{{ session('success') }}</p></div>
        <button class="alert__close" type="button" aria-label="Cerrar mensaje" data-dismiss><x-icon name="close" size="16" /></button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert--error" role="alert" data-dismissible>
        <span class="alert__icon"><x-icon name="alert" /></span>
        <div><strong>No se pudo completar</strong><p>{{ session('error') }}</p></div>
        <button class="alert__close" type="button" aria-label="Cerrar mensaje" data-dismiss><x-icon name="close" size="16" /></button>
    </div>
@endif
