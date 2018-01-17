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
    <div data-role="page" id="inicio" data-theme="b">
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
            <h1><?php echo "$empresa" ?> - Nueva línea de trabajo</h1>
            <a href="#myPanel" class="ui-btn ui-icon-bars ui-btn-icon-notext ui-corner-all ui-alt-icon"></a>
            <a href="#datosUser" data-icon="user" class="ui-btn-right">Usuario</a>
        </div>
        <div data-role="content">
            <form action="workflow.php" method="POST">
                
                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre" id="nombre" value="" required>
                <label for="descripcion">Descripción:</label>
                <input type="text" name="descripcion" id="descripcion" value="" required>
                <div class="ui-field-contain">
                    <fieldset data-role="controlgroup" data-type="horizontal">
                        <legend>Días de actividad:</legend>
                        <input type="checkbox" name="lun" id="lun" value="lun">
                        <label for="lun">Lunes</label>
                        <input type="checkbox" name="mar" id="mar" value="mar">
                        <label for="mar">Martes</label>
                        <input type="checkbox" name="mie" id="mie" value="mie">
                        <label for="mie">Miércoles</label>
                        <input type="checkbox" name="jue" id="jue" value="jue">
                        <label for="jue">Jueves</label>
                        <input type="checkbox" name="vie" id="vie" value="vie">
                        <label for="vie">Viernes</label>
                        <input type="checkbox" name="sab" id="sab" value="sab">
                        <label for="sab">Sábado</label>
                        <input type="checkbox" name="dom" id="dom"value="dom">
                        <label for="dom">Domingo</label>                       
                    </fieldset>
                </div>
                <fieldset data-role="controlgroup" data-type="vertical">
                    <legend>Franja horaria activa:</legend>
                    <div class="ui-field-contain">
                    <label for="inicio">Inicio:</label>
                    <input type="time" data-clear-btn="false" name="inicio" id="inicio" value="" required>
                    <label for="fin">Fin:</label>
                    <input type="time" data-clear-btn="true" name="fin" id="fin" value="" required>
                    </div>
                </fieldset>
                <input type="submit" value="Aceptar" class="ui-button">
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

