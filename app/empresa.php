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
    $datosEmpresa = BD::datosEmpresa($cif);
    $email = $datosEmpresa['empresaEmail'];
    $telefono = $datosEmpresa['empresaTelefono'];
    $direccion = $datosEmpresa['empresaDireccion'];
    $nombreComercial = $datosEmpresa['empresaNombreComercial'];
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
    <div data-role="page" id="empresa" data-theme="b">
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
            <h1>Datos de empresa</h1>
            <a href="#myPanel" class="ui-btn ui-icon-bars ui-btn-icon-notext ui-corner-all ui-alt-icon"></a>
            <a href="#datosUser" data-icon="user" class="ui-btn-right">Usuario</a>
        </div>
        <div data-role="content">
            <ul data-role="listview">
                <li>Empresa: <?php echo "$empresa" ?></li>
                <li>Email: <?php echo "$email" ?></li>
                <li>Teléfono: <?php echo "$telefono" ?></li>
                <li>Dirección: <?php echo "$direccion" ?></li>
                <li>Razón social: <?php echo "$nombreComercial" ?></li>
                <a href="../logOff.php" class="ui-btn">Salir</a>
            </ul>

        </div>
        <div data-role="page" id="datosUser" data-theme="b">
            <div data-role="header" data-position="fixed" data-id="encabezado" data-theme="a">
                <h1>ReservManager - <?php echo "$empresa" ?></h1>
                <a href="#planning" data-icon="home" class="ui-btn-right">Inicio</a>
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
    </div>


</body>
</html>
