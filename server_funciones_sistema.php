<?php
    session_start();
    include("funciones_genericas.php"); // Funciones genericas del sistema   

    #Ejecutar Funciones POST/GET
    isset($_POST["action"]) &&  $_POST["action"]!= "" ? call_user_func_array($_POST["action"],array()) : "";
    isset($_GET["action"])  &&  $_GET["action"] != "" ? call_user_func_array($_GET["action"],array())  : "";
    
    function valid_token(){
        
        require_once("cn_webcpanel.php");
        $estado       = 'NO';
        //hacer if que verifique que exite una cookie de token
        if(isset($_COOKIE["UST"]) != ""){
            
            $token     = $_COOKIE["UST"];
            $query     = "SELECT * FROM cu_control_acceso WHERE dFechaVencimientoToken < NOW() AND sToken='$token'";   
            $Result    = $conexion->query($query);
            $row       = $Result->fetch_assoc();
            
            if($row['sUsuario'] != ""){
                $estado       = 'OK';
                $sDescripcion = "Reinicio de sesi&oacute;n exitoso.";
                //$bitacora     = inserta_accion_bitacora($token,$sDescripcion,$con,$_SESSION["user"],"LOGIN");
            }else{
                
                $sDescripcion = "La sesi&oacute;n expir&oacute;";
                //$bitacora     = inserta_accion_bitacora($token,$sDescripcion,$con,$_SESSION["user"],"LOGIN");
                $cerrar_sesion = cerrar_sesion(true);  
            }
            
        }  
        echo $estado;
    }
    function cerrar_sesion($server = false){
        require_once("cn_webcpanel.php");
        $estado = 'OK'; 
        
        if(!($server)){
           $sDescripcion = "Cierre de sesi&oacute;n exitoso.";  
           $token        = $_COOKIE["UST"];
           //Alta a bitacora...
        }
        
        //Destruir Session PHP 
        session_unset();   
        session_destroy(); 
    
        return $estado;
    } 
    function valid_user(){
    
        require_once("cn_master.php"); // Conexion a la BDD Master  
        $Usuario = $_POST['login'];
        $Contra  = $_POST['contra']; 


       //entra cuando se completan los datos
        if($_SERVER["HTTP_HOST"]=="dev1.globalpc.net" OR $_SERVER["HTTP_HOST"]=="localhost:8080"){$inst="bd_ecertifica_prueba";}
        elseif(strstr($_SERVER["HTTP_HOST"], ".econta.mx") !== FALSE){
            $bd_empresa_host = explode(".econta.mx",$_SERVER["HTTP_HOST"]);
            $inst            = "bd_ecertifica_".$bd_empresa_host[0];
        }
        
        //Verificar que exista la instancia y que tenga acceso:
        $query  = "SELECT sNombreBD,sEstatusAcceso FROM instancias WHERE sNombreBD='$inst'";    
        $result = mysqli_query($con,$query);  
            
        if(mysqli_num_rows($result) != 1 ){$respuesta_array = array("estado"=>"invalido", "nombre"=>"","mensaje"=>"Error: La instancia no existe.");}
        else{
            
            $accesoInst = mysqli_fetch_assoc($result);
            if($accesoInst['sEstatusAcceso'] != 'OK' && $accesoInst["sEstatusAcceso"] != "WARNING"){$respuesta_array = array("estado"=>"invalido", "nombre"=>"","mensaje"=>"Su acceso al sistema ha sido restringido debido a que tiene facturas pendientes de pago. Favor de contactarnos para mas informaci&oacute;n.");}
            else{
                
                //Revisar Datos del Usuario:
                if($Usuario == 'global@ecertifica.mx' OR $Usuario == 'ecertificaadmin'){
                    $query = "SELECT sUser, sNombreBD, sNivelUsuario, sName ".
                             "FROM user WHERE sUser='".$Usuario."' AND hPassword='".$Contra."' AND hPassword != '' AND iDeleted = '0'";
                }else{
                    $query = "SELECT sUser,sName, sNivelUsuario ".
                             "FROM user A WHERE sUser='".$Usuario."' AND hPassword='".$Contra."' AND hPassword != '' ".
                             "AND iDeleted = 0 AND sNombreBD = '$inst'";
                }
                
            }

            //verificar usuario 
            $consulta = mysqli_query($con,$query);    
            
            if (mysqli_num_rows($consulta) != 1){
                
                $sDescripcion    = "Inicio de sesi&oacute;n inv&aacute;lido - usuario/contrase&ntilde;a incorrectos.";
                $bitacora        = inserta_accion_bitacora('',$sDescripcion,$con,$Usuario,$inst,"LOGIN","", null);
                $respuesta_array = array("estado"=>"invalido", "nombre"=>"","mensaje"=>"Error: El usuario y/o contraseña son incorrectos.");}
                
            else{ 
               
                $datos    = mysqli_fetch_assoc($consulta); 
                   
                //Crear Variables de Sesion:
                $_SESSION["db_empresa"]   = $inst;
                $_SESSION["user_nombre"]  = $datos['sName']; 
                $_SESSION["user"]         = $datos['sUser']; 
                $_SESSION["NivelUsuario"] = $datos['sNivelUsuario'];
               
               //Token:
               $token = createToken();
                   $_SESSION["token"]=$token;
                   
               //Actualiza usuario estatus:
               $query   = "UPDATE user SET sToken = '$token',sEstatusConexion='C',dFechaHoraConexion=NOW(),dFechaVencimientoToken = DATE_SUB(CONCAT(CURDATE(), ' ',CURTIME()), INTERVAL -1 DAY) ".
                          "WHERE sUser = '$Usuario' AND hPassword = '$Contra' AND iDeleted = '0'";
               $success = mysqli_query($con, $query);  
                   
               if(!($success)){
                   $respuesta_array = array("estado"=>"invalido", "nombre"=>"","mensaje"=>"Error: La sesi&oacutel;n no se inicio correctamente, favor de intentarlo nuevamente.");
               }else{
                   //Registrar en Bitacora:
                   $sDescripcion = "Inicio de sesi&oacute;n exitoso.";
                   $bitacora     = inserta_accion_bitacora($token,$sDescripcion,$con,$Usuario,$inst,"LOGIN","",null);

                   if(!($bitacora)){$respuesta_array = array("estado"=>"invalido", "nombre"=>"","mensaje"=>"Error: La sesi&oacutel;n no se registro correctamente, favor de intentarlo nuevamente.");}
                   else{$respuesta_array = array("estado"=>"valido","nombre"=>"$Usuario", "token"=>"$token", "empresa"=>$_SESSION['db_empresa']); }
               }
          
            } 
        }
        echo json_encode($respuesta_array);
    }
?>
