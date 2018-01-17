<?php
include 'bd.php';
session_start();
if (isset($_POST['logEmail']) && isset($_POST['logContrasena'])){
    $email = $_POST['logEmail'];
    $contrasena = $_POST['logContrasena'];
}
if (BD::comprobarLogin($email, $contrasena)){
    $datosUsuario = BD::datosUsuario($email);
    $cif = $datosUsuario['empresaCIF'];
    $codigoRolUsuario = $datosUsuario['userRol'];
    if ($codigoRolUsuario=="a"){
        $rol = "Administrador";
        $datosEmpresa = BD::datosEmpresa($cif);
        $_SESSION['empresa'] = $datosEmpresa['empresaNombreComercial'];
        $_SESSION['email'] = $email;
        $_SESSION['contrasena'] = $contrasena;
        $_SESSION['rol'] = $rol;
        $_SESSION['cif'] = $cif;
    
    header('Location: app/planning.php');
    }
    elseif ($codigoRolUsuario=="u") {
        $rol = "Usuario";
    }
    else{
        $rol = "Cliente";
    }

}
else {
    //Implementar funcionalidad; una página para recuperar la contraseña mediante envío de mail.
    echo "Usuario no registrado. Redirigiendo...";
    header('Location: index.html');
}


