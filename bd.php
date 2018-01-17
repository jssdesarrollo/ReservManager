<?php

//ini_set('display_errors', 'On');
//ini_set('display_errors', 1);
class BD{
    protected static $conexion;
   
    //Función para conectar a la bd.
    private static function conectar(){
        $host = "localhost"; // Nombre del host.
        $dbname = "reservManager"; //Nombre de la base de datos. Será opcional
        $user = "root"; //Usuario
        $password = "root"; //Contraseña
            $dsn = "mysql:host=$host;dbname=$dbname";
        try {
            self::$conexion = new PDO($dsn, $user, $password);
            //echo ("<p>Conectado</p>");
        } catch (PDOException $e) {
            echo '<p>Falló la conexión: '.$e->getMessage()."</p>";
        }
        self::$conexion->query('SET NAMES \'utf8\''); //Seteamos el juego de caracteres a utf-8
    }
    
    //Verifica el login
    public static function comprobarLogin($userEmail,$userContrasena){
        self::conectar();
        $contrasena = md5($userContrasena);
        $consulta = "SELECT userNombre FROM usuarios WHERE userEmail='$userEmail' AND userContrasena='$contrasena'";
        $resultados = self::$conexion->query($consulta);//Lanzamos el query.
        self::$conexion=null; //Cerramos la conexión
        if ($resultados->fetch()){
            return true;
        }
        else{
            return false;
        }   
        
    }
    
    //Consulta si una empresa está en la bd
    public static function comprobarCif($cif){
        self::conectar();
        $consulta = "SELECT * FROM empresas WHERE empresaCIF = '$cif'";
        $resultados = self::$conexion->query($consulta);//Lanzamos el query.
        self::$conexion=null; //Cerramos la conexión
        if ($resultados->fetch()){
            return true;
        }
        else{
            return false;
        } 
    }
    
    //Registra una nueva empresa
    public static function registrarEmpresa ($cif, $email, $telefono, $direccion, $localidad, $provincia, $nombre){
        $direccion = $direccion." - ".$localidad." - ".$provincia;
        self::conectar();
        $consulta = "INSERT INTO empresas VALUES ('$cif', '$email', $telefono, '$direccion', '$nombre')";
        $filas = self::$conexion->exec($consulta);
        self::$conexion=null;
        if ($filas == 0){
            return false;
        }
        else{
            return true;
        }
    }
    
        // Devuelve todos los datos de una empresa
    public static function datosEmpresa($cif){
        self::conectar();
        $consulta = "SELECT * FROM empresas WHERE empresaCIF='$cif'";
        $resultados = self::$conexion->query($consulta);//Lanzamos el query.
        self::$conexion=null; //Cerramos la conexión
        return $resultados->fetch();   
    }


    //Devuelve todos los datos de usuario a través del email.
    public static function datosUsuario ($userEmail){
        self::conectar();
        $consulta = "SELECT * FROM usuarios WHERE userEmail='$userEmail'";
        $resultados = self::$conexion->query($consulta);//Lanzamos el query.
        self::$conexion=null; //Cerramos la conexión
        return $resultados->fetch();   
    }
    
    
    //Devuelve todos los datos de todos los usuarios de una empresa. Filtramos el tipo de usuario si le pasamos como rol administrador o cliente.
    public static function usuarios ($cif, $rol){
        
        if ($rol =="a" || $rol == "c"){
            $consulta = "SELECT * FROM usuarios WHERE empresaCIF='$cif'AND userRol='$rol'";
        }
        else{
            $consulta = "SELECT * FROM usuarios WHERE empresaCIF='$cif'";
        }
        self::conectar();   
        $ejecucion = self::$conexion->query($consulta);//Lanzamos el query.
        $ejecucion->setFetchMode(PDO::FETCH_ASSOC);       
        $resultados = $ejecucion->fetchAll();
        self::$conexion=null; //Cerramos la conexión
        return $resultados;
    }

    //Registra un nuevo usuario
    public static function registrarUsuario ($nombre, $telefono, $email, $contrasena, $rol, $cif){
        self::conectar();
        $contrasena = md5($contrasena);
        $consulta = "INSERT INTO usuarios VALUES ('$nombre', $telefono, '$email', '$contrasena', '$rol', '$cif')";
        $filas = self::$conexion->exec($consulta);
        self::$conexion=null;
        if ($filas == 0){
            return false;
        }
        else{
            return true;
        }
        
    }
    
    //Borra un usuario
    public static function borrarUsuario ($email){
         self::conectar();
        $consulta = "DELETE FROM usuarios WHERE userEmail = '$email'";
        $filas = self::$conexion->exec($consulta);
        self::$conexion=null;
        if ($filas == 0){
            return false;
        }
        else{
            return true;
        }
        
    }
    
    //Modificar datos de usuario
    public static function modificarUsuario ($nombre, $telefono, $email, $contrasena, $rol){
         self::conectar();
        $contrasena = md5($contrasena);
        $consulta = "UPDATE usuarios SET userNombre = '$nombre', userTelefono = $telefono, userContrasena= '$contrasena', userRol ='$rol' WHERE userEmail = '$email'";
        $filas = self::$conexion->exec($consulta);
        self::$conexion=null;
        if ($filas == 0){
            return false;
        }
        else{
            return true;
        }
        
    }

    
    //Devuelve los datos de las líneas de worflow de una empresa
    public static function lineasWorkflow($cif){
        self::conectar();
        $consulta = "SELECT * FROM lineasWorkflow WHERE empresaCIF='$cif'";       
        $ejecucion = self::$conexion->query($consulta);//Lanzamos el query.
        $ejecucion->setFetchMode(PDO::FETCH_ASSOC);       
        $resultados = $ejecucion->fetchAll();
        self::$conexion=null; //Cerramos la conexión
        return $resultados;
    }
        
    //Crea una nueva línea de workflow con las franjas horarias y días de la semana que se hayan asignado
    public static function nuevoWorkflow($cif, $nombre, $descripcion, $inicio, $fin, $dias){
        self::conectar();
        $consulta = "INSERT INTO lineasWorkflow VALUES (DEFAULT, '$nombre', '$cif', '$descripcion', '$inicio', '$fin', '$dias')";
        $filas = self::$conexion->exec($consulta);
        self::$conexion=null;
        if ($filas == 0){
            return false;
        }
        else{
            return true;
        }
        
    }
    
    //Borra una línea de workflow
    public static function borrarWorkflow($workflowID){
        self::conectar();
        $consulta = "DELETE FROM lineasWorkflow WHERE workflowID = $workflowID";
        $filas = self::$conexion->exec($consulta);
        self::$conexion=null;
        if ($filas == 0){
            return false;
        }
        else{
            return true;
        }
    }
    
        //Devuelve todos los servicios de una empresa.
    public static function servicios($cif){
        self::conectar();
        $consulta = "SELECT * FROM servicios WHERE empresaCIF='$cif'";       
        $ejecucion = self::$conexion->query($consulta);//Lanzamos el query.
        $ejecucion->setFetchMode(PDO::FETCH_ASSOC);       
        $resultados = $ejecucion->fetchAll();
        self::$conexion=null; //Cerramos la conexión
        return $resultados;
    }
    
    //Devuelve los IDs y nombres de las líneas de wf en las que se puede ejecutar un servicio dado.
    public static function servicioWF($servicioID){
        self::conectar();
        $consulta = "SELECT L. workflowID, L.workflowNombre, L.workflowHoraInicio, L.workflowHoraFin, L.workflowDias FROM lineasWorkflow L INNER JOIN serviciosWF S ON L.workflowID = S.workflowID WHERE S.servicioID = $servicioID";       
        $ejecucion = self::$conexion->query($consulta);//Lanzamos el query.
        $ejecucion->setFetchMode(PDO::FETCH_ASSOC);       
        $resultados = $ejecucion->fetchAll();
        self::$conexion=null; //Cerramos la conexión
        return $resultados;
    }
    
    // Crea un nuevo servicio y sus relaciones con las líneas de workflow
    public static function nuevoServicio ($cif, $servicioNombre, $servicioDescripcion, $servicioDuracion, $lineasWF){
        self::conectar();
        $consulta = "INSERT INTO servicios VALUES (DEFAULT, '$servicioNombre','$servicioDescripcion', '$servicioDuracion','$cif')";
        $filas = self::$conexion->exec($consulta);
        self::$conexion=null;
        if ($filas == 0){
            return false;
        }
        else{
            self::conectar();
            $consulta2 = "SELECT MAX(servicioID) FROM servicios";//Obtenemos el id del servicio recién introducido, ya que es auto increment.
            $resultados = self::$conexion->query($consulta2);//Lanzamos el query.
            self::$conexion=null; //Cerramos la conexión
            $arrayServicioID = $resultados->fetch();
            $servicioID = $arrayServicioID[0];
            foreach($lineasWF as $workflowID){
                self::conectar();
                $consulta3 = "INSERT INTO serviciosWF VALUES ('$cif', $servicioID, $workflowID)";
                self::$conexion->exec($consulta3);
                self::$conexion=null;
            }
            return true;
        }
    }
    
    //Elimina un servicio y sus relaciones con las líneas de workflow
    public static function borrarServicio ($servicioID){
        self::conectar();
        $consulta = "DELETE FROM serviciosWF WHERE servicioID = $servicioID";
        $filas = self::$conexion->exec($consulta);
        self::$conexion=null;
        if ($filas == 0){
            return false;
        }
        else{
            self::conectar();
            $consulta2 = "DELETE FROM servicios WHERE servicioID = $servicioID";
            self::$conexion->exec($consulta2);
            self::$conexion=null;
            return true;
        }
    }
    //Reservas por empresa y línea de trabajo.
    public static function reservas ($cif, $fecha, $workflowID){
        self::conectar();
        if($workflowID==0){
            $workflowID = "'%'";
        }
        $consulta = "SELECT R.*, S.servicioNombre, W.workflowNombre 
                     FROM reservas R 
                     INNER JOIN servicios S ON R.servicioID = S.servicioID 
                     INNER JOIN lineasWorkflow W ON W.workflowID = R.workflowID
                     WHERE R.reservaInicio LIKE '$fecha%' AND W.empresaCIF = '$cif' AND W.workflowID LIKE $workflowID AND R.reservaEstado = 'activa' ORDER BY R.reservaInicio";
        $ejecucion = self::$conexion->query($consulta);//Lanzamos el query.
        $ejecucion->setFetchMode(PDO::FETCH_ASSOC);       
        $resultados = $ejecucion->fetchAll();
        self::$conexion=null; //Cerramos la conexión
        return $resultados;
    }
    
    //Historial de reservas entre fechas (solo reservas finalizadas)
        public static function historialReservas ($cif, $fechaIni, $fechaFin){
        self::conectar();
        $consulta = "SELECT R.*, S.servicioNombre, W.workflowNombre 
                     FROM reservas R 
                     INNER JOIN servicios S ON R.servicioID = S.servicioID 
                     INNER JOIN lineasWorkflow W ON W.workflowID = R.workflowID
                     WHERE R.reservaInicio BETWEEN '$fechaIni' AND '$fechaFin' AND W.empresaCIF = '$cif' AND R.reservaEstado = 'finalizada' ORDER BY R.reservaInicio";
        $ejecucion = self::$conexion->query($consulta);//Lanzamos el query.
        $ejecucion->setFetchMode(PDO::FETCH_ASSOC);       
        $resultados = $ejecucion->fetchAll();
        self::$conexion=null; //Cerramos la conexión
        return $resultados;
    }
    
    
    //Marcar reserva como finalizada
    public static function finalizarReserva($reservaID, $observaciones){
        self::conectar();
        $consulta = "UPDATE reservas SET reservaEstado = 'finalizada', reservaObservacionesFinalizacion = '$observaciones' WHERE reservaID = $reservaID";
        $filas = self::$conexion->exec($consulta);
        self::$conexion=null;
        if ($filas == 0){
            return false;
        }
    }
    
    //Reservas por usuario
        public static function reservasUsuario ($cif, $email){
        self::conectar();
        $consulta = "SELECT R.*, S.servicioNombre, W.workflowNombre 
                     FROM reservas R 
                     INNER JOIN servicios S ON R.servicioID = S.servicioID 
                     INNER JOIN lineasWorkflow W ON W.workflowID = R.workflowID
                     WHERE W.empresaCIF = '$cif' AND R.userEmail = '$email' ORDER BY R.reservaInicio";
        $ejecucion = self::$conexion->query($consulta);//Lanzamos el query.
        $ejecucion->setFetchMode(PDO::FETCH_ASSOC);       
        $resultados = $ejecucion->fetchAll();
        self::$conexion=null; //Cerramos la conexión
        return $resultados;
    }
    
    //Borra una reserva
    public static function borrarReserva ($reservaID){
        self::conectar();
        $consulta = "DELETE FROM reservas WHERE reservaID = $reservaID";
        $filas = self::$conexion->exec($consulta);
        self::$conexion=null;
        if ($filas == 0){
            return false;
        }
        else{
            return true;
        }
        
    }
    //Crea una nueva reserva. Se sirve de la función ChequearOcupacion para verificar si existe hueco para hacerla con los parámetros indicados.
    public static function nuevaReserva ($fecha, $horaInicio, $servicioDuracion, $observaciones, $email, $workflowID, $servicioID){
        $inicioFin = self::chequearOcupacion($workflowID, $fecha, $horaInicio, $servicioDuracion);
        if ($inicioFin){
            $inicio = $inicioFin['inicio'];
            $fin = $inicioFin['fin'];
            self::conectar(); 
            $consulta = "INSERT INTO reservas VALUES(DEFAULT, '$inicio', '$fin','$observaciones', '$email', $workflowID, $servicioID, 'activa', '')";
            $filas = self::$conexion->exec($consulta);
            self::$conexion=null;
            if ($filas == 0){
                return false;
            }
            else{
                return true;
            }
        }
    }
    
    /* Chequea si una reserva se puede hacer en una determinada fecha y hora, para un servicio x y en una linea de workflow y.
     */
    public static function chequearOcupacion ($workflowID, $fecha, $horaInicio, $servicioDuracion){
        include 'funcionesHoras.php';
        $aperturaCierre = self::chequearHorario($workflowID, $fecha, $horaInicio);
        if($aperturaCierre){
            self::conectar();
            $apertura = ["reservaInicio" => $fecha." ".$aperturaCierre['apertura'], "reservaFin" => $fecha." ".$aperturaCierre['apertura']];
            $cierre = ["reservaInicio" => $fecha." ". $aperturaCierre['cierre'], "reservaFin" => $fecha." ".$aperturaCierre['cierre']];
            $consulta = "SELECT reservaInicio, reservaFin FROM reservas WHERE workflowID = $workflowID AND reservaInicio LIKE '$fecha%' AND reservaEstado = 'activa' ORDER BY reservaInicio";
            $ejecucion = self::$conexion->query($consulta);//Lanzamos el query.
            $ejecucion->setFetchMode(PDO::FETCH_ASSOC);
            $arrayIntervalosReservados[] = $apertura;
            $intervalos = $ejecucion->fetchAll();
            self::$conexion=null;
            foreach ($intervalos as $intervalo) {
                $arrayIntervalosReservados[] = $intervalo;
            }
            $arrayIntervalosReservados[] = $cierre;
            $horaInicioPropuesta = "$fecha $horaInicio";
            $horaFinPropuesta = "$fecha ".sumaHoras($horaInicio, $servicioDuracion);
            for ($i=0, $x=1; $i < count($arrayIntervalosReservados)-1; $i++, $x++){
                $inicioLibre = $arrayIntervalosReservados[$i]['reservaFin'];
                $finLibre = $arrayIntervalosReservados[$x]['reservaInicio'];
                if (entreDosHoras($inicioLibre, $finLibre, $horaInicioPropuesta) && entreDosHoras($inicioLibre, $finLibre, $horaFinPropuesta)){
                    //echo "correcto. Reserva asignada de $horaInicioPropuesta a $horaFinPropuesta<br>";
                    $resultado['inicio'] = $horaInicioPropuesta;
                    $resultado['fin'] = $horaFinPropuesta;
                    return $resultado;
                }
            }

        }
  
    }
    
    /* Comprueba que una fecha y hora se encuentra dentro del horario activo de una línea de workflow, incluido el control de días de la semana.
     * En caso afirmativo devuelve las horas de apertura y cierre para ese día.
     */
    public static function chequearHorario($workflowID, $fecha, $hora){
        self::conectar();
        //consultamos los campos de la tabla lineasWorkflow. Nos interesan los días de la semana, hora de apertura y cierre.
        $consulta = "SELECT * FROM lineasWorkflow where workflowID = $workflowID";
        $resultados = self::$conexion->query($consulta);//Lanzamos el query.
        self::$conexion=null; //Cerramos la conexión
        $fila =  $resultados->fetch();
        $diasDisponibles = $fila['workflowDias'];
        $apertura = $fila['workflowHoraInicio'];
        $cierre = $fila['workflowHoraFin'];
        //Comprobamos el día de la semana que es la fecha que hemos pasado como parámetro.
        $numeroDia = date('N', strtotime($fecha));
        $semana = ['dom','lun','mar','mie','jue','vie','sab','dom'];
        $diaSemana = $semana[$numeroDia];
        //Comprobamos si el día se encuentra contenido en la cadena que hemos leído de la BD y si la hora de inicio se encuentra entre la apertura y el cierre.
        if (mb_stristr($diasDisponibles, $diaSemana)){
            if ($hora >= $apertura && $hora< $cierre){
                $valores['apertura'] = $apertura;
                $valores['cierre'] = $cierre;
                return $valores;
            }
        }

    }
    
    public static function usuariosClientes ($cif){
        self::conectar();
        $consulta = "SELECT * FROM usuarios WHERE empresaCIF='$cif' and userRol = 'c'";       
        $ejecucion = self::$conexion->query($consulta);//Lanzamos el query.
        $ejecucion->setFetchMode(PDO::FETCH_ASSOC);       
        $resultados = $ejecucion->fetchAll();
        self::$conexion=null; //Cerramos la conexión
        return $resultados;
    }
    
    
    
}

