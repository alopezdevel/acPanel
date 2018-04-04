<?php
    session_start();
    include("server_funciones_genericas.php"); // Funciones genericas del sistema   

    #Ejecutar Funciones POST/GET
    isset($_POST["action"]) &&  $_POST["action"]!= "" ? call_user_func_array($_POST["action"],array()) : "";
    isset($_GET["action"])  &&  $_GET["action"] != "" ? call_user_func_array($_GET["action"],array())  : "";
    
    function valid_token(){
        
        require_once("cn_webcpanel.php");
        $estado       = 'NO';
        
        //hacer if que verifique que exite una cookie de token
        if(isset($_SESSION["UST"]) && $_SESSION["UST"] != ""){
            
            $token     = $_SESSION["UST"];
            $query     = "SELECT * FROM cu_control_acceso WHERE sToken='$token' AND dFechaVencimientoToken >= NOW()";  
            $Result    = $conexion->query($query);
            $row       = $Result->fetch_assoc();
            
            if($row['sUsuario'] != ""){
                $estado       = 'OK';
                $sDescripcion = "Reinicio de sesi&oacute;n exitoso.";
                //$bitacora     = inserta_accion_bitacora($token,$sDescripcion,$con,$_SESSION["user"],"LOGIN");
            }else{
                
                $sDescripcion = "La sesi&oacute;n expir&oacute;";
                //$bitacora     = inserta_accion_bitacora($token,$sDescripcion,$con,$_SESSION["user"],"LOGIN");
                //$cerrar_sesion = cerrar_sesion(true);  
            }
            
        }  
        echo $estado;
    }
    function cerrar_sesion($server = false){
        require_once("cn_webcpanel.php");
        $estado = 'OK'; 
        
        if(!($server)){
           $sDescripcion = "Cierre de sesi&oacute;n exitoso.";  
           $token        = $_SESSION["UST"];
           //Alta a bitacora...
        }
        
        //Destruir Session PHP 
        session_unset();   
        session_destroy(); 
    
        return $estado;
    } 
    function valid_user(){
    
        require_once("cn_webcpanel.php"); // Conexion a la BDD Master  
        $user      = $_POST['user'];
        $password  = $_POST['password']; 
           
        //Revisar Datos del Usuario:
        $query  = "SELECT idUsuario, eTipoUsuario, hActivado FROM cu_control_acceso ".
                  "WHERE sUsuario='$user' AND hClave='$password' AND hClave != '' AND hActivado = '1'";
        $result = $conexion->query($query);     
            
        if($result->num_rows != 1){
                
            $sDescripcion    = "Inicio de sesi&oacute;n inv&aacute;lido - usuario/contrase&ntilde;a incorrectos.";
          //$bitacora        = inserta_accion_bitacora('',$sDescripcion,$con,$Usuario,$inst,"LOGIN","", null);
            $response        = "Error: El usuario y/o contrase&ntilde;a es incorrecto.";
                
        }else{ 
               
            $datos = $result->fetch_assoc();  
            //Crear Variables de Sesion:
            $_SESSION["idUsuario"]   = $datos['idUsuario'];
            $_SESSION["eTipoUsuario"]= $datos['eTipoUsuario']; 
            $_SESSION["sUsuario"]    = $user; 
            
            //Token:
            $token = createToken();
            $_SESSION["UST"] = $token; // USER TOKEN
                   
            //Actualiza usuario estatus:
            $query   = "UPDATE cu_control_acceso SET sToken = '$token',sEstatusConexion='C',dFechaHoraConexion=NOW(), dFechaVencimientoToken = DATE_SUB(CONCAT(CURDATE(), ' ',CURTIME()), INTERVAL -1 DAY) ".
                       "WHERE  sUsuario='$user' AND hClave='$password' ";
            $success = $conexion->query($query);   
                   
            if(!($success)){$response = "Error: La sesi&oacutel;n no se registro correctamente, favor de intentarlo nuevamente.";}
            else{
               //Registrar en Bitacora:
               $sDescripcion = "Inicio de sesi&oacute;n exitoso.";
               //$bitacora     = inserta_accion_bitacora($token,$sDescripcion,$con,$Usuario,$inst,"LOGIN","",null);

               /*if(!($bitacora)){$response = "Error: La sesi&oacutel;n no se registro correctamente, favor de intentarlo nuevamente.";}
               else{$response = "OK"; } */
               $response = "OK";
            }
        }

        echo $response;
    }
?>
