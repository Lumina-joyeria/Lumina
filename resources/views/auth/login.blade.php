<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#17221d">
    <title>Iniciar sesión | LÚMINA</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="auth-body">
    <main class="auth-shell">
        <section class="auth-visual" aria-label="Bienvenida a LÚMINA">
            <a href="{{ url('/') }}" aria-label="Volver al inicio">
                <x-brand light />
            </a>

            <div class="auth-visual__content">
                <p class="eyebrow">Espacio privado</p>
                <h1>Donde cada detalle encuentra su lugar.</h1>
                <p>
                    Gestiona el universo LÚMINA desde un espacio claro,
                    sereno y seguro.
                </p>
            </div>

            <div class="auth-visual__jewel" aria-hidden="true">
                <span></span>
            </div>

            <p class="auth-visual__foot">Joyería contemporánea · Ecuador</p>
        </section>

        <section class="auth-form-wrap">
            <a class="auth-back" href="{{ url('/') }}">
                <x-icon name="arrow-left" size="18" />
                Volver a la tienda
            </a>

            <div class="auth-form-card">
                <div class="auth-mobile-brand">
                    <x-brand />
                </div>

                <p class="eyebrow">Bienvenido de vuelta</p>
                <h2>Inicia sesión</h2>
                <p class="auth-intro">
                    Ingresa tus credenciales para acceder al panel.
                </p>

                @if ($loginLockSeconds > 0)
                    <div
                        class="alert alert--error"
                        data-login-lock
                        data-login-lock-seconds="{{ $loginLockSeconds }}"
                    >
                        <span class="alert__icon">
                            <x-icon name="alert" />
                        </span>

                        <div>
                            <strong role="alert" aria-live="polite" data-login-lock-title>
                                Acceso temporalmente bloqueado
                            </strong>
                            <p role="timer" aria-live="off" data-login-lock-message>
                                Demasiados intentos fallidos. Inténtalo nuevamente en
                                <span data-login-lock-count>{{ $loginLockSeconds }}</span>
                                <span data-login-lock-unit>
                                    {{ $loginLockSeconds === 1 ? 'segundo' : 'segundos' }}
                                </span>.
                            </p>
                        </div>
                    </div>
                @elseif ($errors->any())
                    <div class="alert alert--error" role="alert">
                        <span class="alert__icon">
                            <x-icon name="alert" />
                        </span>

                        <div>
                            <strong>Revisa tus datos</strong>
                            <p>{{ $errors->first() }}</p>
                        </div>
                    </div>
                @endif

                <form
                    action="{{ route('login.submit') }}"
                    method="POST"
                    class="form-stack"
                    novalidate
                    data-login-form
                >
                    @csrf

                    <div class="field">
                        <label for="correo">Correo electrónico</label>

                        <div class="field__control">
                            <x-icon name="user" size="18" />
                            <input
                                type="email"
                                id="correo"
                                name="correo"
                                value="{{ old('correo', session('login_lock_email')) }}"
                                placeholder="nombre@correo.com"
                                autocomplete="email"
                                required
                            >
                        </div>

                        @error('correo')
                            @if ($loginLockSeconds === 0)
                                <p class="field__error">{{ $message }}</p>
                            @endif
                        @enderror
                    </div>

                    <div class="field">
                        <label for="contrasena">Contraseña</label>

                        <div class="field__control">
                            <x-icon name="gem" size="18" />
                            <input
                                type="password"
                                id="contrasena"
                                name="contrasena"
                                placeholder="Tu contraseña"
                                autocomplete="current-password"
                                required
                                data-password
                            >
                            <button
                                type="button"
                                class="field__action"
                                aria-label="Mostrar contraseña"
                                data-password-toggle
                            >
                                <x-icon name="eye" size="18" />
                            </button>
                        </div>

                        @error('contrasena')
                            <p class="field__error">{{ $message }}</p>
                        @enderror
                    </div>

                    <button
                        class="button button--primary button--full"
                        type="submit"
                        data-login-submit
                        @disabled($loginLockSeconds > 0)
                    >
                        <span data-login-submit-label>
                            {{ $loginLockSeconds > 0 ? "Espera {$loginLockSeconds} s" : 'Ingresar al panel' }}
                        </span>
                        <x-icon name="arrow-right" size="18" />
                    </button>
                </form>

                <p class="auth-help">
                    <x-icon name="info" size="17" />
                    ¿Necesitas acceso? Solicítalo al administrador de LÚMINA.
                </p>
            </div>
        </section>
    </main>
</body>
</html>
