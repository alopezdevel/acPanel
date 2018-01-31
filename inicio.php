<!doctype>  
<html lang="en">
<head>
    <meta http-equiv="Cache-Control" content="no-cache">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta content="text/html; charset=windows-1252" http-equiv="Content-Type">
    <meta http-equiv="Lang" content="en">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>WebsolutionsAC - Control Panel</title> 
    <link rel="shortcut icon" href="images/icon.ico" type="img/x-icon"> 
    <!-- CSS & JS --> 
    <script src="lib/jquery-3.3.1.min.js"></script>
    <link rel="stylesheet" type="text/css" href="lib/bootstrap-4.0.0/css/bootstrap.min.css"> 
    <script src="lib/bootstrap-4.0.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="css/fontawesome-all.min.css">   
    <link rel="stylesheet" type="text/css" href="css/styles.css"> 
  
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="#">
    <img src="images/logo-cpanel300px.png" width="300" alt="">
  </a>
  <!-- Boton para menu movil -->
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#MenuPrincipal" aria-controls="MenuPrincipal" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <!-- Menu principal -->
  <div class="collapse navbar-collapse" id="MenuPrincipal">
    <ul class="navbar-nav">
      <li class="nav-item active">
        <a class="nav-link" href="#">Inicio</a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="submenuPagos" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Mis Pagos</a>
        <div class="dropdown-menu" aria-labelledby="submenuPagos">
          <a class="dropdown-item" href="#">Nuevo</a>
          <a class="dropdown-item" href="#">Historial de Pagos</a>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Contacto</a>
      </li>
    </ul>
  </div>
</nav> 
<div id="cPanel_inicio" class="container">
    <div class="info_login">
        <div class="row">
            <div class="col-sm-9"><span><i class="fas fa-user"></i> usuario@dominio.com</span>
            </div>
            <div class="col-sm-3"><span onclick=""><i class="fas fa-sign-out-alt"></i> Salir</span></div>
        </div>
    </div>
</div> 
</body>
</html>
