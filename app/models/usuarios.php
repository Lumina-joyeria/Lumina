<?php

function buscarUsuarioPorCorreo(PDO $conexion, string $correo) {
    
    $sql = "SELECT id, nombre, correo, contrasena, id_rol
            FROM usuarios
            WHERE correo = ?";
    
    $consulta = $conexion->prepare($sql);
    
    $consulta->execute([$correo]);
    
    return $consulta->fetch(PDO::FETCH_ASSOC);
}