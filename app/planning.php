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

//Funcionalidad para borrar reservas
if(isset($_GET['borrar']) && !empty($_GET['borrar'])){
    $identificador = $_GET['borrar'];
    if(!BD::borrarReserva($identificador)){
        $mensaje1 = "Error interno";
        $mensaje2 = "no se ha podido borrar esta reserva";
        header("Location: dialog.php?m1=$mensaje1&m2=$mensaje2");
    }
}
if (isset($_POST['finalizarReserva'])){
    $finalizarID = $_POST['finalizarID'];
    $observacionesFinalizacion = $_POST['observacionesFinalizacion'];
    BD::finalizarReserva($finalizarID, $observacionesFinalizacion);
}

// Se leen todas las líneas de wf de la empresa para llenar el select.
$lineasWF = BD::lineasWorkflow($cif);
$htmlSelect = "<option value='0'>Línea (todas)</option>";
foreach ($lineasWF as $lineaWF){
    $workflowNombre = $lineaWF['workflowNombre'];
    $workflowID = $lineaWF['workflowID'];
    $htmlSelect .= "<option value='$workflowID'>$workflowNombre</option>";
}

// Funcionalidad para llenar la lista de reservas
$fechaPlaceholder = "Fecha";
$elementos="";
$fecha = "";
$workflowSeleccionado = 0;
if (isset($_POST['fecha']) && !empty($_POST['fecha'])){
    $fechaLeida = $_POST['fecha'];
    $fechaFormateada = strtotime(str_replace("/", "-",$fechaLeida));
    $fecha = date('Y-m-d',$fechaFormateada);
    $fechaPlaceholder = $fechaLeida;
}
if (isset($_POST['seleccionLineasWF'])){
    $workflowSeleccionado = $_POST['seleccionLineasWF'];
}

$resultados = BD::reservas($cif, $fecha, $workflowSeleccionado);
foreach ($resultados as $reserva){
    $reservaID = $reserva['reservaID'];
    $reservaFecha = substr($reserva['reservaInicio'],0,10);
    $reservaHoraInicio = substr($reserva['reservaInicio'], -8);
    $reservaHoraFin = substr($reserva['reservaFin'], -8);
    $reservaObservaciones = $reserva['reservaObservaciones'];
    $userEmail = $reserva['userEmail'];
    $servicioNombre = $reserva['servicioNombre'];
    $workflowNombre = $reserva['workflowNombre'];
    $textoFranja = $reservaFecha." ".$reservaHoraInicio." - ".$reservaHoraFin;

    //Lo guardamos todo como html en una variable que imprimimos en el lugar correspondiente.
    $elementos.= <<<FIN
    <li data-icon="delete">
    <a href="marcarReserva.php?marcarReserva=$reservaID&servicioNombre=$servicioNombre&userEmail=$userEmail&workflowNombre=$workflowNombre&fecha=$reservaFecha&horaInicio=$reservaHoraInicio&horaFin=$reservaHoraFin">   
    <h2>$servicioNombre</h2>
    <p><strong>Cliente: $userEmail</strong></p>
    <p class='ui-li-aside'><strong>$textoFranja</strong><br><strong>Línea: $workflowNombre</strong></p>
    <p>$reservaObservaciones</p>
    </a>
    <a href="planning.php?borrar=$reservaID" data-position-to="window" data-transition="pop">Borrar elemento</a>
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
    <link rel="stylesheet" href="themes/datepicker.css">
    <script src="scripts/jquery.min.js"></script>
    <script src="scripts/jquery.mobile-1.4.5.min.js"></script>
    <script src="scripts/jquery.ui.datepicker.js"></script>
    <script id="mobile-datepicker" src="http://cdn.rawgit.com/arschmitz/jquery-mobile-datepicker-wrapper/v0.1.1/jquery.mobile.datepicker.js"></script>
       
    <title>ReservManager</title>
</head>
<body>
    <div data-role="page" id="planning" data-theme="b">
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
            <h1><?php echo "$empresa" ?> - Planning</h1>
            <a href="#myPanel" class="ui-btn ui-icon-bars ui-btn-icon-notext ui-corner-all ui-alt-icon"></a>
            <a href="#datosUser" data-icon="user" class="ui-btn-right">Usuario</a>
        </div>
        <div data-role="content">
            <!-- Aqui el contenido del planning -->  
            <form action="planning.php" method="POST" data-position="fixed">
                  <fieldset data-role="controlgroup" data-type="horizontal">
                      <input type="text" name="fecha" data-role="date" placeholder='<?php echo $fechaPlaceholder ?>'>
                    <select name="seleccionLineasWF" id="seleccionLineasWF" data-mini="true" >
                        <?php echo $htmlSelect ?>
                    </select>
                    <input type="submit" value="Aceptar" class="ui-button" data-mini="true">
                  </fieldset>
            </form>
            <ul data-role="listview" data-inset="true" id="listawf">
                <!-- Aquí añadimos los elementos de la lista-->
                <?php echo "$elementos" ?>
                <a href="servicios.php" class="ui-btn" data-transition="slide">Crear nueva</a>
            </ul>
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