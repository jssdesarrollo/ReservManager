<?php
//ini_set('display_errors', 'On');
//ini_set('display_errors', 1);
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

//Funcionalidad para borrar un wf de la lista
if(isset($_GET['borrar']) && !empty($_GET['borrar'])){
    $identificador = $_GET['borrar'];
    if(!BD::borrarWorkflow($identificador)){
        $mensaje1 = "No se puede borrar esta línea";
        $mensaje2 = "Contiene reservas o servicios asociados";
        header("Location: dialog.php?m1=$mensaje1&m2=$mensaje2");
    }
}

//Funcionalidad para crear un nuevo wf si recibimos los datos del formulario de nuevoWF.php
if(isset($_POST['nombre']) && !empty($_POST['nombre'])){
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $dias = $_POST['lun']."-".$_POST['mar']."-".$_POST['mie']."-".$_POST['jue']."-".$_POST['vie']."-".$_POST['sab']."-".$_POST['dom'];
    $inicio = $_POST['inicio'];
    $fin = $_POST['fin'];
    BD::nuevoWorkflow($cif, $nombre, $descripcion, $inicio, $fin, $dias);
}
//Leemos las líneas de wf asociadas a la empreesa
$resultados = BD::lineasWorkflow($cif);
foreach ($resultados as $lineawf) {
    $id = $lineawf['workflowID'];
    $nombre = $lineawf['workflowNombre'];
    $descripcion = $lineawf['workflowDescripcion'];
    $inicio = $lineawf['workflowHoraInicio'];
    $fin = $lineawf['workflowHoraFin'];
    $dias = $lineawf['workflowDias'];
    $textoFranja = $dias." ".$inicio." - ".$fin;
    
//Lo guardamos todo como html en una variable que imprimimos en el lugar correspondiente.
    $elementos.= <<<FIN
    <li data-icon="delete">
    <a href="#">
    <h2>$nombre</h2>
    <p><strong>$descripcion</strong></p>
    <p class='ui-li-aside'><strong>$textoFranja</strong></p>
    </a>
    <a href="workflow.php?borrar=$id" data-position-to="window" data-transition="pop">Borrar elemento</a>
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
    <div data-role="page" id="workflow" data-theme="b">
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
            <h1><?php echo "$empresa" ?> - Gestión de workflow</h1>
            <a href="#myPanel" class="ui-btn ui-icon-bars ui-btn-icon-notext ui-corner-all ui-alt-icon"></a>
            <a href="#datosUser" data-icon="user" class="ui-btn-right">Usuario</a>
        </div>
        <div data-role="content">
            <ul data-role="listview" data-inset="true" id="listawf">
                <!-- Aquí añadimos los elementos de la lista-->
                <?php echo "$elementos" ?>
                <a href="nuevoWF.php" class="ui-btn" data-transition="slide">Nuevo</a>
                   
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



