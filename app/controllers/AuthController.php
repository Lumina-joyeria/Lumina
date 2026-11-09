<?php

require_once __DIR__ . "/../services/autenticacion.php";

function iniciarSesion(PDO $conexion, string $correo, string $contrasena) {
    
    $usuario = autenticarUsuario($conexion, $correo, $contrasena);
    
    if ($usuario === false) {
        return false;
    }
    
    session_start();
    session_regenerate_id(true);
    $_SESSION["id_usuario"] = $usuario["id"];
    $_SESSION["rol_usuario"] = $usuario["id_rol"];
    return true;
}

function cerrarSesion() {
    
    session_start();
    $_SESSION = [];
    session_destroy();
    
}