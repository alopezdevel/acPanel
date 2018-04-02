//INICIANDO JS:
$(document).ready(function(){ 
    cpanelweb.init();

});

var cpanelweb = {
    init : function(){
        $.ajax({
            type: "POST",
            url : "server_funciones_sistema.php",
            data: {"action" : "valid_token"},
            dataType : "text",
            success : function(data){ 
                if(data != "OK"){   
                    $("#web_login input").val(""); 
                    cpanelweb.login();  
                }else{
                    location.href= "inicio.php?type=88e5542d2cd5b7f86cd6c204dc77fb523fb719071b2b08cfd7cbfbcadb365af1c8c9ba63"; 
                }
            }
       });
    },
    login : function(){
        
        var valid = true;
        var user  = $("#web_login input[name='usuario']").val();
        var pass  = $("#web_login input[name='contrasena']").val();
        
        if(valid){
           $.ajax({
            type: "POST",
            url : "server_funciones_sistema.php",
            data: {"action" : "valid_user","user":user,"password":pass},
            dataType : "text",
            success : function(data){ 
                if(data != "OK"){   
                    $("#web_login input").val(""); 
                }else{
                    location.href= "inicio.php?type=88e5542d2cd5b7f86cd6c204dc77fb523fb719071b2b08cfd7cbfbcadb365af1c8c9ba63"; 
                }
            }
           }); 
        }else{
            
        }
    },
    checkRegexp : function( o, regexp, n ) {
        if ( !( regexp.test( o.val() ) ) ) {
            fn_solotrucking.actualizarMensajeAlerta( n );
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
}