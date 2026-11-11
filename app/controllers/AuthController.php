<?php

require_once __DIR__ . "/../services/autenticacion.php";

function iniciarSesion(PDO $conexion, string $correo, string $contrasena) {
    
    $usuario = autenticarUsuario($conexion, $correo, $contrasena);
    
    if ($usuario === false) {
        return false;
    }
    
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    session_regenerate_id(true);
    $_SESSION["id_usuario"] = (int) $usuario["id"];
    $_SESSION["rol_usuario"] = (int) $usuario["id_rol"];
    return true;
}

function cerrarSesion() {
    
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    $_SESSION = [];
    session_destroy();
}