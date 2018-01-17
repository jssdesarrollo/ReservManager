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
//Funcionalidad para leer los datos de un usuario al pinchar sobre el mismo en la vista de usuarios.
$emailBox = $contrasenaBox = $nombreBox = $telefonoBox = $htmlTabla = "";
$rolBox = "cliente";
if (isset($_GET['userEmail'])){
    $emailBox = $_GET['userEmail'];
    $contrasenaBox = $_GET['userContrasena'];
    $nombreBox = $_GET['userNombre'];
    $telefonoBox = $_GET['userTelefono'];
    $rolBox = $_GET['userRol'];
    $filasTabla = BD::reservasUsuario($cif, $emailBox);
    foreach ($filasTabla as $fila){
        $reservaID = $fila['reservaID'];
        $reservaInicio = $fila['reservaInicio'];
        $reservaFin = $fila['reservaFin'];
        $servicio = $fila['servicioNombre'];
        $lineaWF = $fila['workflowNombre'];
        $estado = $fila['reservaEstado'];
        $htmlTabla.= <<<FIN
                <tr>
                    <th>$reservaID</th><td>$reservaInicio</td><td>$reservaFin</td><td>$servicio</td><td>$lineaWF</td><td>$estado</td>
                </tr>
FIN;
    }
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
    <div data-role="page" id="nuevoUsuario" data-theme="b">
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
            <h1>Ficha de usuario</h1>
            <a href="#myPanel" class="ui-btn ui-icon-bars ui-btn-icon-notext ui-corner-all ui-alt-icon"></a>
            <a href="#datosUser" data-icon="user" class="ui-btn-right">Usuario</a>
        </div>
        <div data-role="content">
            <form action="usuarios.php" method="POST">
                <label for="userNombre">Nombre:</label>
                <input type="text" name="userNombre" id="userNombre" value="<?php echo $nombreBox ?>" required>
                <label for="userEmail">Email:</label>
                <input type="email" name="userEmail" id="userEmail" value="<?php echo $emailBox ?>" required>
                <label for="contrasena">Contrasena:</label>
                <input type="password" name="userContrasena" id="userContrasena" value="<?php echo $contrasenaBox ?>" required>
                <label for="userTelefono">Teléfono:</label>
                <input type="text" name="userTelefono" id="userTelefono" value="<?php echo $telefonoBox ?>" required>
                <fieldset data-role="controlgroup" data-type="horizontal">
                    <legend>Rol: </legend>
                    <label for="userRol_a">Administrador</label>
                    <input type="radio" name="userRol" id="userRol_a" value="administrador"<?php if($rolBox=="administrador") echo "checked='checked'" ?> >
                    <label for="userRol_b">Cliente</label>
                    <input type="radio" name="userRol" id="userRol_b" value="cliente"<?php if($rolBox=="cliente") echo "checked='checked'" ?> >
                </fieldset>
                <fieldset data-role="controlgroup" data-type="vertical">
                    <h3>Reservas de usuario: </h3>
                    <table data-role="table" id="table-column-toggle" class="ui-responsive table-stroke">
                        <thead>
                            <tr>
                                <th>ID reserva</th><<th>Inicio</th><th>Fin</th><th>Servicio</th><th>Línea WF</th><th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php echo $htmlTabla ?>
                        </tbody>
                    </table>
                </fieldset>
                <br>
                <fieldset data-role="controlgroup" data-type="horizontal">
                <input type="submit" name="aceptar" value="Guardar cambios" class="ui-button">
                <a href="usuarios.php" class="ui-btn ui-btn-inline">Cancelar</a>
                </fieldset>
            </form>
            
        </div>
    </div>
    <div data-role="page" id="datosUser" data-theme="b">
        <div data-role="header" data-position="fixed" data-id="encabezado" data-theme="a">
            <h1>ReservManager - <?php echo "$empresa" ?></h1>
            <a href="#inicio" data-icon="home" class="ui-btn-right">Inicio</a>
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