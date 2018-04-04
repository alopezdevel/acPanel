<?php 
session_start();    
if (!($_SESSION["idUsuario"] != "" && $_SESSION["idUsuario"] != NULL) || $_SESSION["UST"] == ""){ 
    //No ha iniciado session, redirecciona a la pagina de login
    header("Location: index.php");
    exit;
}
else{
    
?>
<script type="text/javascript"> 
     var inicio = {
        domroot : "#cPanel_inicio",
        init    : function(){
            $.ajax({
                type: "POST",
                url : "server_inicio.php",
                data: {"action" : "cargar_datos"},
                dataType : "json",
                success : function(data){ 
                    $(inicio.domroot+" .row .col-4").empty().append(data.htmlco);
                }
           });
        } 
     }
</script>
<!-- HEADER -->
<?php include("header.php"); ?> 
<div id="cPanel_inicio" class="container-fluid">
    <h1>Bienvenido al sistema de Control de Pagos</h1>
    <div class="row">
        <div class="col-4"></div>
        <div class="col-8"></div>
    </div>
</div> 
<!-- FOOTER -->
<?php include("footer.php");}?> 
