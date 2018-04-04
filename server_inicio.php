<?php
    session_start();
    include("server_funciones_genericas.php"); // Funciones genericas del sistema   

    #Ejecutar Funciones POST/GET
    isset($_POST["action"]) &&  $_POST["action"]!= "" ? call_user_func_array($_POST["action"],array()) : "";
    isset($_GET["action"])  &&  $_GET["action"] != "" ? call_user_func_array($_GET["action"],array())  : "";
    
    function cargar_datos(){
         
         $error       = "0";
         $mensaje     = ""; 
         $htmlCoInfo  = "<div class=\"data-grid\"><h3>Informaci&oacute;n de la Cuenta</h3>";
         
         require_once("cn_webcpanel.php");
         
         #CONSULTAR DATOS DE LA COMPAÑIA: 
         $query     = "SELECT A.idEmpresa , sRazonSocial, sAlias, idContacto, sNombreContacto, sTelefonoContacto ".
                      "FROM      cb_empresa          AS A ".
                      "LEFT JOIN rl_usuario_empresa  AS B ON A.idEmpresa = B.idEmpresa ".
                      "LEFT JOIN cb_contacto_empresa AS C ON A.idEmpresa = C.idEmpresa ".
                      "WHERE B.idUsuario = '".$_SESSION["idUsuario"]."' AND bActivo = '1'";  
         $result    = $conexion->query($query);
         
         if($result->num_rows != 0){
             $row = $result->fetch_assoc();
    
             $htmlCoInfo .= "<table class=\"table table-bordered table-striped\">";
             $htmlCoInfo .= "<tbody>"; 
             $htmlCoInfo .= "<tr><th class=\"text-nowrap\" scope=\"row\">Compañia:</th><td>".$row['sRazonSocial']."</td></tr>";
             $htmlCoInfo .= "<tr><th class=\"text-nowrap\" scope=\"row\">Contacto:</th><td>".$row['sNombreContacto']."</td></tr>";
             $htmlCoInfo .= "<tr><th class=\"text-nowrap\" scope=\"row\">Tel&eacute;fono:</th><td>".$row['sTelefonoContacto']."</td></tr>";
             $htmlCoInfo .= "</tbody>";
             $htmlCoInfo .= "</table>";
             
         }else{
            $error       = "1";
            $mensaje     = "Error al consultar los datos de la compa&iacute;a.";
            $htmlCoInfo .= "<table class=\"table table-bordered table-striped\">";
            $htmlCoInfo .= "<tbody>"; 
            $htmlCoInfo .= "<tr><td colspan=\"100%\">No se encontraron los datos.</td></tr>";
            $htmlCoInfo .= "</tbody>";
            $htmlCoInfo .= "</table>"; 
         }
         $htmlCoInfo .= "</div";
         
         $response = array("error"=>"$error","mensaje"=>"$mensaje","htmlco"=>"$htmlCoInfo");
         echo json_encode($response);
         
    }
?>
