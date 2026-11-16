<div class="form-grid">
    <div class="field field--wide"><label for="nombre">Nombre completo <span>*</span></label><input class="input" type="text" id="nombre" name="nombre" value="{{ old('nombre', $cliente->nombre ?? '') }}" maxlength="100" placeholder="Ej. María Andrade" required>@error('nombre')<p class="field__error">{{ $message }}</p>@enderror</div>
    <div class="field"><label for="correo">Correo electrónico <span>*</span></label><input class="input" type="email" id="correo" name="correo" value="{{ old('correo', $cliente->correo ?? '') }}" maxlength="100" placeholder="nombre@correo.com" required>@error('correo')<p class="field__error">{{ $message }}</p>@enderror</div>
    <div class="field"><label for="telefono">Teléfono</label><input class="input" type="tel" id="telefono" name="telefono" value="{{ old('telefono', $cliente->telefono ?? '') }}" maxlength="20" placeholder="Ej. 099 000 0000">@error('telefono')<p class="field__error">{{ $message }}</p>@enderror</div>
</div>
