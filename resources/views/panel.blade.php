<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel | LÚMINA</title>
</head>
<body>
    <h1>Bienvenido a LÚMINA, {{ $nombre }}</h1>
    
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        
        <button type="submit">Cerrar sesión</button>
    </form>
</body>
</html>