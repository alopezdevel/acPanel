<?php

    function createKey(){
        $Key="";
        while(strlen($Key)<=8)
        {
            srand((double)microtime()*1000000);
            $number = rand(50,150);
            if($number>=65 && $number<=78)
                if($number%2 != 0){
                    $Key = strtolower($Key.chr($number));
                }else{
                    $Key = $Key.chr($number);
                }
            elseif($number>=80 && $number<=90)
                    $Key = $Key.chr($number);
            elseif($number>=49 && $number<=57)
                if($number%2 != 0){
                    $Key = strtolower($Key.chr($number));
                }else{
                    $Key = $Key.chr($number);
                }
        }
        return getEncode(trim($Key));
    }
    function encode_base64($sData) {
        $sBase64 = base64_encode($sData);
        //return strtr($sBase64, '=', '-');
        return $sBase64;
    }
    function getEncode($sData, $sKey = 'mlL98324kjDOkRLcd009LDPElmLKDEO90349KLMDEodokeddijnepp') {
            $sResult = '';
            for($i=0;$i<strlen($sData);$i++){
                $sChar    = substr($sData, $i, 1);
                $sKeyChar = substr($sKey, ($i % strlen($sKey)) - 1, 1);
                $sChar    = chr(ord($sChar) + ord($sKeyChar));
                $sResult .= $sChar;
            }
            return encode_base64($sResult);
    }
    function mysql_fetch_all($query, $kind = 'assoc') {
        $result = array();
        $kind = $kind === 'assoc' ? $kind : 'row';
        eval('while(@$r = mysql_fetch_'.$kind.'($query)) array_push($result, $r);');
        return $result;
    }
    function date_to_client($text){
        if (preg_match('/^(\d\d\d\d)-(\d\d?)-(\d\d?)$/', $text)) {
            //if (preg_match("/^(\d\d?)\D(\d\d?)\D(\d\d\d\d)$/", $text)) {
            /* fix when insert '' in date field */
            if($text=="0000-00-00"){
                return("");
            }else{
                $tmp = explode("-",$text);
                return($tmp[2]."/".$tmp[1]."/".$tmp[0]);
            }
        }
    }
    /* Token */
    function createToken(){
      $Key="";
      while(strlen($Key)<=29)
      {
        srand((double)microtime()*1000000);
        $number = rand(50,150);
        if($number>=65 && $number<=78)
            if($number%2 != 0){
                $Key = strtolower($Key.chr($number));
            }else{
                $Key = $Key.chr($number);
            }elseif($number>=80 && $number<=90)
                $Key = $Key.chr($number);
          elseif($number>=49 && $number<=57)
            if($number%2 != 0){
                    $Key = strtolower($Key.chr($number));
              }else{
                    $Key = $Key.chr($number);
              }
      }
      return trim(md5(sha1($Key)));
    }
    function getUserData($token,$con){ 

        $query  = "SELECT sUser,sName,sNivelUsuario,sNombreBD FROM bd_ecertifica_master.user WHERE sToken = '".$token."'";
        $result = mysqli_query($con,$query);
        $datos  = mysqli_fetch_assoc($result); 
        return $datos;
    }
    /* Bitacora */
    function inserta_accion_bitacora($token,$sDescripcion,$con,$sUser,$inst,$eCategoria,$sTablaNombre,$iTablaConsecutivo){  
        
        if($inst != "" && $sDescripcion != "" && $sUser != ""){
             
             $query  = "INSERT INTO ".$inst.".cb_bitacora_actividad (sUser,sToken,iTablaConsecutivo,sTablaNombre,eCategoria,sDescripcion,sIPIngreso) ".
                       "VALUES('$sUser','$token','$iTablaConsecutivo','$sTablaNombre','$eCategoria','$sDescripcion','".date_to_server($_SERVER['REMOTE_ADDR'])."')";
             $result = mysqli_query($con, $query)  or die (mysqli_error($con));  
             if($result){return true;}else{return false;}
    
        }else{return false;}

        
    }
    /*Transacciones */
    function server_trans_begin($con){
        $versionPHP = phpversion();
        if($versionPHP >= '5.5.0'){
            return mysqli_begin_transaction($con,MYSQLI_TRANS_START_READ_WRITE);
        }else{
            return mysqli_autocommit($con,FALSE);
            //mysqli_query($con, "START TRANSACTION");
        }
    }

?>
