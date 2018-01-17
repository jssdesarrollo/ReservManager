<?php

//Funciones auxiliares para el manejo de fechas.
            function entreDosHoras($horaLimiteInferior, $horaLimiteSuperior, $hora){
                $limInf = date_create("$horaLimiteInferior");
                $limSup = date_create("$horaLimiteSuperior");
                $hora2 = date_create("$hora");
                //$duracion = date_create("$duracion");
                if ($hora2>=$limInf && $hora2<=$limSup){
                    return true;
                }
                else{
                    return false;
                }
            }

            function sumaHoras ($hora1, $hora2){
                $segundos_hora1 = strtotime($hora1);
                $segundos_hora2 = strtotime($hora2);
                $nuevaHora=date("H:i:s",$segundos_hora1+$segundos_hora2+3600);
                return $nuevaHora;
            }

            function restaHoras ($hora1, $hora2){
                $segundos_hora1 = strtotime($hora1);
                $segundos_hora2 = strtotime($hora2);
                $nuevaHora=date("H:i:s",$segundos_hora1-$segundos_hora2-3600);
                return $nuevaHora;
            }

