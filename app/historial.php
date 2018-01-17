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
$htmlTabla = "";
if (isset($_POST['aceptar'])){
    $fechaInicioLeida = $_POST['fechaInicio'];
    $fechaInicioFormateada = strtotime(str_replace("/", "-",$fechaInicioLeida));
    $fechaInicio = date('Y-m-d',$fechaInicioFormateada);
    $fechaInicioPlaceholder = $fechaInicioLeida;
    $fechaFinLeida = $_POST['fechaFin'];
    $fechaFinFormateada = strtotime(str_replace("/", "-",$fechaFinLeida));
    $fechaFin = date('Y-m-d',$fechaFinFormateada);
    $fechaFinPlaceholder = $fechaFinLeida;

    $historicoReservas = BD::historialReservas($cif, $fechaInicio, $fechaFin);
    foreach ($historicoReservas as $fila){
        $reservaID = $fila['reservaID'];
        $reservaCliente = $fila['userEmail'];
        $reservaInicio = $fila['reservaInicio'];
        $reservaFin = $fila['reservaFin'];
        $reservaObservaciones = $fila['reservaObservaciones'];
        $servicio = $fila['servicioNombre'];
        $lineaWF = $fila['workflowNombre'];
        $reservaObservacionesFinalizacion = $fila['reservaObservacionesFinalizacion'];
        $htmlTabla.= <<<FIN
        <tr>
            <th>$reservaID</th><th>$reservaCliente</th><td>$reservaInicio</td><td>$reservaFin</td><td>$reservaObservaciones</td><td>$servicio</td><td>$lineaWF</td><td>$reservaObservacionesFinalizacion</td>
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
    <link rel="stylesheet" href="themes/datepicker.css">
    <script src="scripts/jquery.min.js"></script>
    <script src="scripts/jquery.mobile-1.4.5.min.js"></script>
    <script src="scripts/jquery.ui.datepicker.js"></script>
    <script id="mobile-datepicker" src="http://cdn.rawgit.com/arschmitz/jquery-mobile-datepicker-wrapper/v0.1.1/jquery.mobile.datepicker.js"></script>
       
    <title>ReservManager</title>
</head>
<body>
    <div data-role="page" id="historial" data-theme="b">
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
            <h1><?php echo "$empresa" ?> - Histórico de reservas</h1>
            <a href="#myPanel" class="ui-btn ui-icon-bars ui-btn-icon-notext ui-corner-all ui-alt-icon"></a>
            <a href="#datosUser" data-icon="user" class="ui-btn-right">Usuario</a>
        </div>
        <div data-role="content">
            <!-- Aqui el contenido del planning -->  
            <form action="historial.php" method="POST" data-position="fixed">
                  <fieldset data-role="controlgroup" data-type="horizontal">
                      <input type="text" name="fechaInicio" data-role="date" placeholder='Fecha inicial'>
                      <input type="text" name="fechaFin" data-role="date" placeholder='Fecha final'>

                    <input type="submit" name="aceptar" value="Aceptar" class="ui-button" data-mini="true">
                  </fieldset>
            </form>
            <fieldset data-role="controlgroup" data-type="vertical">
                <h3>Reservas entre fechas: </h3>
                <table data-role="table" id="table-column-toggle" class="ui-responsive table-stroke">
                    <thead>
                        <tr>
                            <th>ID reserva</th><th>Cliente</th><<th>Inicio</th><th>Fin</th><th>Observaciones Cliente</th><th>Servicio</th><th>Línea WF</th><th>Observaciones Finalización</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php echo $htmlTabla ?>
                    </tbody>
                </table>
            </fieldset>
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
