<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión | LÚMINA</title>
</head>
<body>
    <h1>Bienvenido a LÚMINA</h1>
    <h2>Iniciar sesión</h2>
    
    <form action="{{ route('login.submit') }}" method="POST">
        @csrf
        
        <label for="correo">Correo electrónico:</label>
        <input type="email" id="correo" name="correo" value="{{ old('correo') }}" required>
        @error('correo')
            <p style="color: #C00;">{{ $message }}</p>
        @enderror
        <label for="contrasena">Contraseña:</label>
        <input type="password" id="contrasena" name="contrasena" required>
        
        <button type="submit">Ingresar</button>
    </form>
</body>
</html>