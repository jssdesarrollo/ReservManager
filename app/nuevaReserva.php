<?php
ini_set('display_errors', 'On');
ini_set('display_errors', 1);
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
    if (isset($_GET['servicio'])){
        $servicioID = $_GET['servicio'];
        $servicioNombre = $_GET['servicioNombre'];
        $servicioDuracion = $_GET['servicioDuracion'];
    }
    elseif (isset($_POST['servicioID'])) {
        $servicioID = $_POST['servicioID'];
        $servicioNombre = $_POST['servicioNombre'];
        $servicioDuracion = $_POST['servicioDuracion'];
    }
}
// Funcionalidad para llenar lista de clientes
$clientes = BD::usuariosClientes($cif);
$htmlClientes = "<option value='0'>Cliente</option>";
foreach ($clientes as $cliente){
    $clienteNombre = $cliente['userNombre'];
    $clienteEmail = $cliente['userEmail'];
    $htmlClientes .= "<option value='$clienteEmail'>$clienteEmail - $clienteNombre</option>";
}

//Funcionalidad para llenar las líneas de wf. Como pasamos el id del servicio, nos devuelve solo aquellas asociadas a este id.
$lineasWF = BD::servicioWF($servicioID);
$htmlLineas = "<option value='0'>Línea</option>";
foreach ($lineasWF as $lineaWF){
    $workflowNombre = $lineaWF['workflowNombre'];
    $workflowID = $lineaWF['workflowID'];
    $workflowApertura = $lineaWF['workflowHoraInicio'];
    $workflowCierre = $lineaWF['workflowHoraFin'];
    $workflowDias = $lineaWF['workflowDias'];
    $htmlLineas .= "<option value='$workflowID'>$workflowNombre: $workflowDias de $workflowApertura a $workflowCierre</option>";
}


// Funcionalidad para insertar una nueva línea de WF.
$htmlCuadro = "";
if (isset($_POST['aceptar'])){
    $fechaLeida = strtotime(str_replace("/", "-",$_POST['fecha']));
    $fechaFormateada = date('Y-m-d',$fechaLeida);
    $horaLeida = $_POST['hora'];
    $horaFormateada = $horaLeida.":00";
    $emailLeido = $_POST['cliente'];
    $workflowIDLeido = $_POST['seleccionLineasWF'];
    $observacionesLeidas = " ".$_POST['observaciones'];
    $servicioIDLeido = $_POST['servicioID'];
    $servicioDuracionLeido = $_POST['servicioDuracion'];
    $resultado = BD::nuevaReserva($fechaFormateada, $horaFormateada, $servicioDuracionLeido, $observacionesLeidas, $emailLeido, $workflowIDLeido, $servicioIDLeido);
    if ($resultado){
        $htmlCuadro = <<<FIN
                <div class="ui-body ui-body-a ui-corner-all">
                <h3>Reserva realizada con éxito</h3>
                <ul><li>Fecha: $fechaFormateada</li><li>Hora: $horaFormateada</li><li>Cliente: $emailLeido</li></ul>
                <p>Ahora puede visualizarla en su planning.</p>
                </div>
FIN;
    }
    else{
        $htmlCuadro = <<<FIN
                <div class="ui-body ui-body-a ui-corner-all">
                <h3>No se ha podido realizar la reserva</h3>
                <p>Asegúrese de que se puede encuadrar en el planning de la línea de trabajo seleccionada. Puede revisar la ocupación en el apartado de Workflow.</p>
                </div>
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
    <script id="mobile-datepicker" src="http://cdn.rawgit.com/arschmitz/jquery-mobile-datepicker-wrapper/v0.1.1/jquery.mobile.datepicker.js"></script>
       
    <title>ReservManager</title>
</head>
<body>
    <div data-role="page" id="nuevaReserva" data-theme="b">
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
            <h1><?php echo "$empresa" ?> - Nueva reserva</h1>
            <a href="#myPanel" class="ui-btn ui-icon-bars ui-btn-icon-notext ui-corner-all ui-alt-icon"></a>
            <a href="#datosUser" data-icon="user" class="ui-btn-right">Usuario</a>
        </div>
        <div data-role="content">
            <h3> <?php echo "Servicio: $servicioNombre. Duración: $servicioDuracion"?></h3>
            <!-- Aqui el contenido del planning -->  
            <form action="nuevaReserva.php" method="POST" data-position="fixed">
                <fieldset data-role="controlgroup" data-type="horizontal">
                    <legend>Fecha y hora</legend>
                    <input type="text" name="fecha" data-role="date" placeholder='Fecha'>
                    <label for="hora">Hora:</label>
                    <input type="time" data-clear-btn="false" name="hora" id="hora" value=""required> 
                </fieldset>
                 
                <select name ="cliente" id ="cliente">
                    <?php echo $htmlClientes ?>
                </select>
                <select name="seleccionLineasWF" id="seleccionLineasWF">
                  <?php echo $htmlLineas ?>
                </select>
                <label for="observaciones">Observaciones: </label>
                <textarea data-mini="true" cols="40" rows="8" name="observaciones" id="observaciones"></textarea>
                <input type="hidden" name ="servicioID" value = '<?php echo $servicioID ?>'>
                <input type="hidden" name ="servicioNombre" value = '<?php echo $servicioNombre ?>'>
                <input type="hidden" name ="servicioDuracion" value = '<?php echo $servicioDuracion ?>'>
                <input type="submit" value="Aceptar" name="aceptar" class="ui-button">
                  
            </form>
            <?php echo $htmlCuadro ?>
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