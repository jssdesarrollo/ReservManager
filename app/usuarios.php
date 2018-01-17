<?php
session_start();
include '../bd.php';
if (!isset($_SESSION['email'])){
    header('Location: ../index.html');
}
else{
    $empresa = $_SESSION['empresa'];
    $usuario = $_SESSION['email'];
    $rol = $_SESSION['rol'];
    $cif = $_SESSION['cif'];
}

//Funcionalidad para borrar un usuario
if (isset($_GET['borrar'])){
    $emailBorrar = $_GET['borrar'];
    if(!BD::borrarUsuario($emailBorrar)){
        $mensaje1 = "No se puede eliminar este usuario";
        $mensaje2 = "Puede que el usuario tenga reservas activas en el sistema. Revise la ficha de usuario para verificarlo.";
        header("Location: dialog.php?m1=$mensaje1&m2=$mensaje2");
    }
    
}
//Funcionalidad para recoger los datos de nuevoUsuario.php y crear el nuevo usuario o modificar existente según proceda.
if (isset($_POST['aceptar'])){
    $emailBox = $_POST['userEmail'];
    $contrasenaBox = $_POST['userContrasena'];
    $nombreBox = $_POST['userNombre'];
    $telefonoBox = $_POST['userTelefono'];
    if($_POST['userRol']=='administrador'){
        $rolBox = "a";
    }
    if($_POST['userRol']=='cliente'){
        $rolBox = "c";
    }
    if(BD::comprobarLogin($emailBox, $contrasenaBox)){
        if(!BD::modificarUsuario($nombreBox, $telefonoBox, $emailBox, $contrasenaBox,$rolBox)){
            $mensaje1 = "No se puede modificar este usuario";
            $mensaje2 = "Los datos introducidos no son correctos";
            header("Location: dialog.php?m1=$mensaje1&m2=$mensaje2");
        }
    }
    else{
        if(!BD::registrarUsuario($nombreBox, $telefonoBox, $emailBox, $contrasenaBox,$rolBox, $cif)){
            $mensaje1 = "No se ha podido dar de alta el usuario";
            $mensaje2 = "Los datos introducidos no son correctos";
            header("Location: dialog.php?m1=$mensaje1&m2=$mensaje2");
        }
    }
}

//Funcionalidad para rellenar lista de usuarios
if (isset($_GET['tipoUsuario'])){
    $tipoUsuario = $_GET['tipoUsuario'];
}
else{
    $tipoUsuario = "todos";
}
$usuarios = BD::usuarios($cif, $tipoUsuario);
foreach ($usuarios as $usuario) {
    $nombre = $usuario['userNombre'];
    $email = $usuario['userEmail'];
    $contrasena = $usuario['userContrasena'];
    $telefono = $usuario ['userTelefono'];
    if ($usuario['userRol'] == "a")
        $rolUser = "administrador";
    elseif ($usuario['userRol'] == "c")
        $rolUser = "cliente";

$elementos.= <<<FIN
<li data-icon="delete">
<a href="nuevoUsuario.php?userEmail=$email&userNombre=$nombre&userContrasena=$contrasena&userTelefono=$telefono&userRol=$rolUser">
<h2>$nombre</h2>
<p><strong>$email</strong></p>
<p class='ui-li-aside'><strong>Teléfono: $telefono</strong><br><br>Rol: $rolUser</p>
</a>
<a href="usuarios.php?borrar=$email" data-position-to="window" data-transition="pop">Borrar elemento</a>
</li>
FIN;
    }


?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content = "width=device-width, initial-scale=1, user-scalable=yes">
    <link rel="stylesheet" href="themes/jquery.mobile-1.4.5.min.css">
    <script src="scripts/jquery.min.js"></script>
    <script src="scripts/jquery.mobile-1.4.5.min.js"></script>
    <title>ReservManager</title>
</head>
<body>
    <div data-role="page" id="usuarios" data-theme="b">
        <div data-role="panel" id="myPanel" data-theme="b" data-display="overlay">
            <h2>ReservManager</h2>
            <ul data-role="listview">
                <li><a href="planning.php" data-transition="slide">Planning</a></li>
                <li><a href="servicios.php" data-transition="slide">Servicios</a></li>
                <li><a href="workflow.php" data-transition="slide">Workflow</a></li>
                <li><a href="usuarios.php" data-transition="slide">Usuarios</a></li>
                <li><a href="empresa.php" data-transition="slide">Empresa</a></li>
                <li><a href="historial.php" data-transition="slide">Historial</a></li>
            </ul>
        </div> 
        <div data-role="header" data-position="fixed" data-id="encabezado" data-theme="a">
            <h1><?php echo "$empresa" ?> - Gestión de usuarios</h1>
            <a href="#myPanel" class="ui-btn ui-icon-bars ui-btn-icon-notext ui-corner-all ui-alt-icon"></a>
            <a href="#datosUser" data-icon="user" class="ui-btn-right">Usuario</a>
        </div>
        <div data-role="content">
            <div class="ui-field-contain">
                <fieldset data-role="controlgroup" data-type="horizontal">
                    <legend>Tipo de usuario a mostrar:</legend>
                    <a href="usuarios.php?tipoUsuario=t" class="ui-btn ui-btn-inline">Todos</a>
                    <a href="usuarios.php?tipoUsuario=a" class="ui-btn ui-btn-inline">Administradores</a>
                    <a href="usuarios.php?tipoUsuario=c" class="ui-btn ui-btn-inline">Cientes</a>                   
                </fieldset>
            </div>
            
            <ul data-role="listview" data-inset="true" id="listawf">
                <!-- Aquí añadimos los elementos de la lista-->
                <?php echo "$elementos" ?>
                <a href="nuevoUsuario.php" class="ui-btn" data-transition="slide">Nuevo usuario</a>
            </ul>
        </div>
    </div>
    <div data-role="page" id="datosUser" data-theme="b">
        <div data-role="header" data-position="fixed" data-id="encabezado" data-theme="a">
            <h1>ReservManager - <?php echo "$empresa" ?></h1>
            <a href="#workflow" data-icon="back" class="ui-btn-right">Atrás</a>
        </div>
        <div data-role="content">
        <!-- Aqui los datos de usuario -->  
            <ul data-role="listview">
                <li>Empresa: <?php echo "$empresa" ?></li>
                <li>Usuario: <?php echo "$usuario" ?></li>
                <li>Rol: <?php echo "$rol" ?></li>
                <a href="../logOff.php" class="ui-btn">Salir</a>

            </ul>

        </div>
        
    </div>


</body>
</html>

