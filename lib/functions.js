//INICIANDO JS:
$(document).ready(function(){ 
    cpanelweb.init();
    
    //Modal:
    $("#mensaje").modal({'show':false});
});

var cpanelweb = {
    cache_module : [],
    cache_json : [],
    cache_etiquetas : [],
    lastModule : "",
    init : function(){
        
        //Revisar HTML actual:
        var HTML    = self.location.href;         
        var POS     = HTML.lastIndexOf("/"); 
        var HTML    = HTML.substr(POS + "/".length,HTML.length); 
        
        $.ajax({
            type: "POST",
            url : "server_funciones_sistema.php",
            data: {"action" : "valid_token"},
            dataType : "text",
            success : function(data){ 
                if(data != "OK"){ 
                    //Verificar ubicacion:
                    if(HTML != "index.php"){location.href="index.php";}
                    else{
                       $("#web_login input").val(""); 
                       cpanelweb.login(); 
                    }
                }else{
                    if(HTML == "index.php"){
                        location.href= "inicio.php?type=88e5542d2cd5b7f86cd6c204dc77fb523fb719071b2b08cfd7cbfbcadb365af1c8c9ba63"; 
                    }else{
                        var funcion = HTML.split(".");
                            funcion = funcion[0]+".init()";
                        eval(funcion);
                    }
                }
            }
       });
    },
    login : function(){ 
        $("#web_login button.btn-login").bind("click",function(){  
            var valid = true;
            var user  = $("#web_login input[name='usuario']").val().trim();
                        $("#web_login input[name='usuario']").val(user);
            var pass  = $("#web_login input[name='contrasena']").val();
            var usrEx = /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/; 
            valid     = valid && cpanelweb.checkRegexp($("#web_login input[name='usuario']"),usrEx);
            
            if(valid){
               $.ajax({
                type: "POST",
                url : "server_funciones_sistema.php",
                data: {"action" : "valid_user","user":user,"password":pass},
                dataType : "text",
                success : function(data){ 
                    if(data != "OK"){  
                        cpanelweb.mensaje(data);  
                        $("#web_login input").val(""); 
                    }else{
                        location.href= "inicio.php?type=88e5542d2cd5b7f86cd6c204dc77fb523fb719071b2b08cfd7cbfbcadb365af1c8c9ba63"; 
                    }
                }
               }); 
            }else{
               cpanelweb.mensaje("Favor de verificar los datos de acceso."); 
            } 
            
        });
    },
    checkRegexp : function( o, regexp, n ) {
        if ( !( regexp.test( o.val() ) ) ) {
            //cpanelweb.mensaje(n);
            o.addClass( "error" );
            o.focus();
            return false;
        } else {                     
            return true;        
        }
    },
    checkLength : function( o, n, min, max ) {
        if ( o.val().length > max || o.val().length < min ) {
            fn_solotrucking.actualizarMensajeAlerta( "Length of " + n + " must be between " + min + " and " + max + "."  );
            o.addClass( "error" );
            o.focus();
            return false;    
        } else {             
            return true;                     
        }                    
    },
    mensaje : function(texto){
        $("#mensaje .modal-body p").empty().append(texto);
        $("#mensaje").modal('show');
    },
}