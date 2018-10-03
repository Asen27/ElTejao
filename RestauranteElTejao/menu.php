<?php
session_start();

include_once("gestionBD.php");
include_once("gestionarUsuarios.php");
?>



<!DOCTYPE html>
<html lang="es">
<head>
  		<meta charset="UTF-8">
		<meta name="author" content="Asen Rangelov Baykushev">
		<meta name="description" content="Trabajo práctico para la asignatura IISSI">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="css/hojaDeEstilo.css" type="text/css" rel="stylesheet">
		
		
  <title>Menú</title>
</head>

<body>

<?php
	include_once("cabecera.php"); 
	


	if (isset($_SESSION["login"])) {

		$nick = $_SESSION["login"];
		$conexion = crearConexionBD();
		$usuario = obtenerDatosUsuario($conexion, $nick);
		$_SESSION["usuario"] = $usuario;
		cerrarConexionBD($conexion);
		
	}

?>



<div class="row">
	<nav class="col-12">
	<ul class="nav2">
	<form action="controlador_menu.php" name = "controlador" id="controlador" method="post"> 
	<li > 
			<button type="submit" name = "reservas"
		<?php if (!((isset($usuario["puesto"]) && ($usuario["puesto"] == "RESERVAS" || $usuario["puesto"] == "GERENTE")))) {
			 echo " class='botonNoActivo' disabled"; } ?> >  Iniciar sesión como encargado de reservas </button>
	</li>
		<li > 
			<button type="submit" name = "almacen"
		<?php if (!((isset($usuario["puesto"]) && ($usuario["puesto"] == "ALMACEN" || $usuario["puesto"] == "GERENTE")))) {
			 echo " class='botonNoActivo' disabled"; } ?> >  Iniciar sesión como encargado de almacén </button>
	</li>
	<li >
			<button type="submit" name = "gerente"
		<?php if (!isset($usuario["puesto"]) || $usuario["puesto"] <> "GERENTE") {
			 echo " class='botonNoActivo' disabled"; } ?> >  Iniciar sesión como gerente </button>
	</li>
	</form>
	<?php if (isset($_SESSION["login"])) { ?>
	<form action="logout.php" method="post" name="logout" id="logout">
		<li class="derecha">
			<button type="submit" name = "cerrarSesion">  Cerrar sesión </button>
	</li>
	</form>
	<?php	} ?>
	</ul>
	</nav>
</div>


<?php 
	if (!isset($_SESSION["login"])) {
?>

<div class="row">
<p class="col-12 texto3"> Para poder acceder a la aplicación del El Tejao <a href="login.php"> inicia sesión</a> o <a href="formulario_alta.php"> regístrate.</a> </p>
</div>


<?php		
	} 
	
?>


	</body>
	
	
	</html>




