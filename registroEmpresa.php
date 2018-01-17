<?php
include 'bd.php';
session_start();
if (isset($_POST['registro'])){
    $cif = $_POST['cif'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];    
    $direccion = $_POST['direccion'];
    $localidad = $_POST['localidad'];
    $provincia = $_POST['provincia'];
    $nombre = $_POST['empresa'];    
    $contrasena = $_POST['contrasena'];
    $nombreUsuario = $nombre."Admin";
    $rol = "a";
}
//Registramos la empresa y el usuario administrador por defecto.
if (BD::registrarEmpresa($cif, $email, $telefono, $direccion, $localidad, $provincia, $nombre)
    && BD::registrarUsuario($nombreUsuario, $telefono, $email, $contrasena, $rol, $cif)){
    echo "1";
}
else{
    echo "0";
}

