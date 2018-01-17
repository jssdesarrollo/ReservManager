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
if (isset($_GET['marcarReserva'])){
    $reservaID = $_GET['marcarReserva'];
    $servicioNombre = $_GET['servicioNombre'];
    $workflowNombre = $_GET['workflowNombre'];
    $fecha = $_GET['fecha'];
    $userEmail = $_GET['userEmail'];
    $horaInicio = $_GET['horaInicio'];
    $horaFin = $_GET['horaFin'];
    $htmlCuadro = <<<FIN
        <div class="ui-body ui-body-a ui-corner-all">
        <h3>Datos de la reserva:</h3>
        <ul><li>Fecha: $fecha</li><li>Hora inicio: $horaInicio</li><li>Hora fin: $horaFin</li><li>Cliente: $userEmail</li><li>Servicio: $servicioNombre</li><li>Línea: $workflowNombre</li></ul>
        </div>
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
    <script id="mobile-datepicker" src="http://cdn.rawgit.com/arschmitz/jquery-mobile-datepicker-wrapper/v0.1.1/jquery.mobile.datepicker.js"></script>
       
    <title>ReservManager</title>
</head>
<body>
    <div data-role="page" id="marcarReserva" data-theme="b">
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
            <h1><?php echo "$empresa" ?> - Finalizar reserva</h1>
            <a href="#myPanel" class="ui-btn ui-icon-bars ui-btn-icon-notext ui-corner-all ui-alt-icon"></a>
            <a href="#datosUser" data-icon="user" class="ui-btn-right">Usuario</a>
        </div>
        <div data-role="content">
            <h3> Finalizar reserva</h3>
            <?php echo $htmlCuadro ?>
            <br><br>
            <form action="planning.php" method="POST" data-position="fixed">
                <label for="observacionesFinalizacion">Observaciones de finalización: </label>
                <textarea data-mini="true" cols="40" rows="8" name="observacionesFinalizacion" id="observacionesFinalizacion"></textarea>
                <input type="hidden" name="finalizarID" value="<?php echo $reservaID?>">
                <fieldset data-role="controlgroup" data-type="horizontal">
                    <input type="submit" name="finalizarReserva" value="Finalizar reserva" class="ui-button">
                    <a href="planning.php" class="ui-btn ui-btn-inline">Cancelar</a>
                </fieldset>
            </form>
        </div>
    </div>
    <div data-role="page" id="datosUser" data-theme="b">
        <div data-role="header" data-position="fixed" data-id="encabezado" data-theme="a">
            <h1>ReservManager - <?php echo "$empresa" ?></h1>
            <a href="#planning" data-icon="back" class="ui-btn-right">Atrás</a>
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

