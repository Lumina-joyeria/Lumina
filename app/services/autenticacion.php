<?php

require_once __DIR__ . "/../models/usuarios.php";


function autenticarUsuario(PDO $conexion, string $correo, string $contrasena) {
    
    $usuario = buscarUsuarioPorCorreo($conexion, $correo);
    if ($usuario === false) {
        return false;
    }
    if (!password_verify($contrasena, $usuario["contrasena"])) {
        return false;
    }
    
    unset($usuario['contrasena']);
    return $usuario;
}