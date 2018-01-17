/* 
 * Javascript para controlar la funcionalidad del formulario.
 * @author: Juan Antonio SanjuÃ¡n
 * @version: 13/11/2017/1.0
 */
  $(document).ready(function() {
    if($("#cif").val().trim().length > 0){
        $("#email").css("background-color", "#a8dfee");
        $("#result").html("<div class='alert alert-dismissible alert-info'>\n\
                        <button type='button' class='close' data-dismiss='alert'>&times;</button>\n\
                        <strong>Info:</strong> Usuario ya registrado. Revise su bandeja de entrada o reenvÃ­e a su email el enlace de descarga.</div>");
        $("#login, #checkDatos, #repetirContrasena, #contrasena, #provincia, #localidad, #direccion, #telefono, #email, #empresa").prop( "disabled", true );
    }

    //En principio desactivamos todos los elementos de entrada excepto el cif
    $("#login, #checkDatos, #repetirContrasena, #contrasena, #provincia, #localidad, #direccion, #telefono, #email, #empresa").prop( "disabled", true );  
    $("#cif").blur(function(){
        var expresion = /^(\d{8})([A-Z])$/;
        var cif = $("#cif").val().trim();
        if (!expresion.test(cif)){
            $("#cif").css("background-color", "#f2dede");
            console.log("cif: "+cif);
            $("#result").html("<div class='alert alert-dismissible alert-danger'>\n\
            <button type='button' class='close' data-dismiss='alert'>&times;</button>\n\
            <strong>¡Error!</strong> Introduzca un CIF válido.</div>");
            $("#result").show();
            $("#login, #checkDatos, #repetirContrasena, #contrasena, #provincia, #localidad, #direccion, #telefono, #email, #empresa").prop( "disabled", true );
            $("#login, #checkDatos, #repetirContrasena, #contrasena, #provincia, #localidad, #direccion, #telefono, #email, #empresa").val("");
        }
        else {
            console.log("cif VALIDO: "+cif);
            $("#cif").css("background-color", "#dbf2f8");
            
            //var cif = $('#cif').val().trim();
            if($.trim(cif).length > 0){
            console.log("cif CONTIENE ALGO: "+cif);
            $.ajax({
                url:"verificarcif.php",
                method:"POST",
                dataType:"json",
                data:{cif:cif},
                cache:"false",
                beforeSend:function() {
                    $('#login').val("Verificando...");
                },
                success:function(data) {
                    $('#login').val("Registro");
                    var registrado = data.registrado;
                    //var nombre= data.nombre;
                    //var apellidos= data.apellidos;
                    console.log("registrado: "+registrado);
                    //console.log("nombre: "+nombre);
                    //console.log("apellidos: "+apellidos);
                    if (registrado==1){
                        $("#cif").css("background-color", "#a8dfee");
                        $("#result").html("<div class='alert alert-dismissible alert-info'>\n\
                        <button type='button' class='close' data-dismiss='alert'>&times;</button>\n\
                        <strong>Info:</strong> Empresa ya registrada. Revise su bandeja de entrada.</div>");
                        $("#login, #checkDatos, #repetirContrasena, #contrasena, #provincia, #localidad, #direccion, #telefono, #email, #empresa").prop( "disabled", true );
                        $("#result").show();
                    }
                    else{
                        $("#checkDatos, #repetirContrasena, #contrasena, #provincia, #localidad, #direccion, #telefono, #email, #empresa").val("");
                        $("#checkDatos, #repetirContrasena, #contrasena, #provincia, #localidad, #direccion, #telefono, #email, #empresa").prop( "disabled", false );
                        console.log("registrado DISTINTO DE 1: "+registrado);
                        $("#result").hide();
                    }
                },
                error: function( jqXhr, textStatus, errorThrown ){
                    console.log( errorThrown );
                }
            });
            }
        }
    });
    
   
    $('#checkDatos').change(function() {
        if($(this).is(":checked")) {
            $("#login").prop("disabled", false);
        }
        if(!$(this).is(":checked")) {
            $("#login").prop("disabled", true);
        }
    });
    
    $('#login').click(function(){
     
      var cif = $('#cif').val();
      var empresa = $('#empresa').val();
      var email = $('#email').val().trim();
      var telefono = $('#telefono').val();
      var direccion = $('#direccion').val();
      var localidad = $('#localidad').val();
      var provincia = $('#provincia').val();
      var contrasena = $('#contrasena').val().trim();
      var repetirContrasena = $('#repetirContrasena').val();
      var todoCorrecto = true;
      
      if(!$.trim(empresa).length > 0){
        todoCorrecto = false;
        $("#empresa").css("background-color", "#f2dede");
      }
      
      var expresionEmail = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
      if (!expresionEmail.test(email)){
        todoCorrecto = false;
        $("#email").css("background-color", "#f2dede");
      }
      var expresionTelefono = /^[9|6]{1}([\d]{2}[-]*){3}[\d]{2}$/;
      if (!expresionTelefono.test(telefono)){
        todoCorrecto = false;
        $("#telefono").css("background-color", "#f2dede");
      }
      if ((contrasena.length) < 5){
        todoCorrecto = false;
        $("#contrasena").css("background-color", "#f2dede");
      }
      if (contrasena != repetirContrasena){
        todoCorrecto = false;
        $("#repetirContrasena").css("background-color", "#f2dede");
      }


      if (todoCorrecto ==true){
        $("#telefono").css("background-color", "#dbf2f8");
        $("#empresa").css("background-color", "#dbf2f8");
        $("#email").css("background-color", "#dbf2f8");
        $("#contrasena").css("background-color", "#dbf2f8");
        $("#repetirContrasena").css("background-color", "#dbf2f8");
        $.ajax({
          url:"registroEmpresa.php",
          method:"POST",
          data:{registro:'1',
                cif:cif,
                empresa:empresa,
                email:email,
                telefono:telefono,
                direccion:direccion,
                localidad:localidad,
                provincia:provincia,
                contrasena:contrasena
                },
          cache:"false",
          beforeSend:function() {
            $('#login').val("Enviando...");
          },
          success:function(data) {
            $('#login').val("Registro");
            if (data=="1") {
            $("#login, #checkDatos, #repetirContrasena, #contrasena, #provincia, #localidad, #direccion, #telefono, #email, #empresa, #cif").prop( "disabled", true );
              $("#result").html("<div class='alert alert-dismissible alert-info'>\n\
              <button type='button' class='close' data-dismiss='alert'>&times;</button>\n\
              <strong>Hecho.</strong> Muchas gracias. Le hemos enviado un email con la confirmación del registro en el sistema.</div>");
              $("#result").show();
            } else {
              $("#result").html("<div class='alert alert-dismissible alert-danger'>\n\
              <button type='button' class='close' data-dismiss='alert'>&times;</button>\n\
              <strong>Error.</strong> Servicio no disponible temporalmente.</div>");
              $("#result").show();
            }
          }
        });
      }else {
          $("#result").html("<div class='alert alert-dismissible alert-danger'>\n\
              <button type='button' class='close' data-dismiss='alert'>&times;</button>\n\
              <strong>Atención </strong>Revise los campos señalados. CIF, empresa, email y contraseña obligatorios. La contraseña ha de tener un mínimo de 5 caracteres y no puede contener espacios.</div>");
              $("#result").show();
              
      };
    });
    
  });

