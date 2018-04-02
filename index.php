<?php
    session_start();
?>
<!DOCTYPE>
<html>
<head>
    <meta http-equiv="Cache-Control" content="no-cache"> 
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type"/>
    <meta http-equiv="Lang" content="en"> 
    <title>WebsolutionsAC - Control Panel</title>
    <link rel="shortcut icon" href="images/icon.ico" type="img/x-icon">
    <!-- CSS & JS --> 
    <script type="text/javascript" src="lib/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="lib/bootstrap-4.0.0/js/bootstrap.min.js"></script>
    <link   type="text/css" rel="stylesheet" href="lib/bootstrap-4.0.0/css/bootstrap.min.css"> 
    <link   type="text/css" rel="stylesheet" href="css/login.css">
    <script type="text/javascript" src="lib/functions.js"></script>  
    <script type="text/javascript" src="lib/jquery.cookie.js"></script>  
</head>
<body>
    <div id="web_login">
        <form class="container frm-container" method="post" action="">
            <div class="logo"></div>
            <input  class="user" name="usuario" type="text" placeholder="Usuario"/>
            <input  class="pass" name="contrasena" type="password" placeholder="Contraseña"/>
            <button class="btn-login" class="btn" type="button">Login</button>
        </form>
        <div class="container">
          <div class="login-menu">
            <a href="#">¿Olvidaste tu contraseña?</a>
          </div>
        </div>
    </div>
</body>
</html>
