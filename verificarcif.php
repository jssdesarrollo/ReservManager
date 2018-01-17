<?php
header('Content-Type: application/json');
include 'bd.php';
session_start();
$cif = $_POST['cif'];
if (BD::comprobarCif($cif)){
    $resultado= array(
        'registrado'=>1
    );
}
else{
    $resultado= array(
        'registrado'=>0
    );
}
echo json_encode($resultado);


