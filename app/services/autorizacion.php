<?php

function usuarioAutenticado() {
    
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    
    if (!isset($_SESSION["id_usuario"])) {
        return false;
    }
    
    return true;
}

function protegerPagina() {
    
    if (usuarioAutenticado() === false) {
        http_response_code(401);
        exit("Debes iniciar sesión");
    
    }
}

function esAdministrador(){
    if (usuarioAutenticado()) {
        if (isset($_SESSION["rol_usuario"]) && $_SESSION["rol_usuario"] === 1) {
            return true;
        } else {
            return false;
        }
    }
    return false;
}