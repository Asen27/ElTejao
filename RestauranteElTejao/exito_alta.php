<?php
session_start();

require_once ("gestionBD.php");
require_once ("gestionarUsuarios.php");


if (isset($_SESSION["formularioAlta"])) {
	
	$puestos = array(
			"RESERVAS"=>"Encargado de reservas",
			"ALMACEN"=>"Encargado de almacen",
			"GERENTE"=>"Gerente");	
	
	
	$usuario = $_SESSION["formularioAlta"];
	unset($_SESSION["formularioAlta"]);
	unset($_SESSION["errores"]);
}



else {

	Header("Location: formulario_alta.php");
}


$conexion = crearConexionBD();
?>


<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="author" content="Asen Rangelov Baykushev">
  <meta name="description" content="Trabajo práctico para la asignatura IISSI">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="css/hojaDeEstilo.css" type="text/css" rel="stylesheet"> 
  <title>Alta de Usuario realizada con éxito</title>
</head>

<body>

<?php
	include_once ("cabecera.php");
?>


	<main>
		
		<?php 	
				
				
				if (alta_usuario($conexion, $usuario)) {
				  
							
		?>
				
				
		<div class="row">
			
			<div class="col-12 texto">
				
	<h1> Hola, <?php echo $usuario["nombre"]; ?> </h1>
	
	    <h2> Bienvenido al sistema de gestión de El Tejao </h2>
	     

		 
		 </div>
		 
		 
		 </div>		
		 
		 <br>


<div class="row">

<div class="col-12" >

	<h3 class="texto"> Sus datos de usuario: </h3>
		 
		<div class="tabla-responsive">
	     <table id="datosUsuario">
	     	

	     	<tr>
	     		<th> Puesto: </th>
	     		<td> <?php echo $puestos[$usuario["puesto"]]?> </td>
	     	</tr>
	     	
	     	<tr>
	     		<th> Nombre: </th>
	     		<td> <?php echo $usuario["nombre"]?> </td>
	     	</tr>	
	     	
	     	<tr>
	     		<th> Apellidos: </th>
	     		<td> <?php echo $usuario["apellidos"]?> </td>
	     	</tr>	
	     	
	     	<tr>
	     		<th> NIF: </th>
	     		<td> <?php echo $usuario["nif"]?> </td>
	     	</tr>
	     	
	     	<?php if (isset ($usuario["fechaNacimiento"])) { ?>
	     	<tr>
	     		<th> Fecha de nacimiento: </th>
	     		<td> <?php echo $usuario["fechaNacimiento"]?> </td>
	     	</tr>
	     	
	     	<?php } ?>
	 
	     	<tr>
	     		<th> Nombre de usuario: </th>
	     		<td> <?php echo $usuario["nick"]?> </td>
	     	</tr>    	
	     	<tr>
	     		<th> Número de teléfono: </th>
	     		<td> <?php echo $usuario["telefono"]?> </td>
	     	</tr>
	     	
	     	<?php if (isset ($usuario["email"])) { ?>
	     	<tr>
	     		<th> Correo electrónico: </th>
	     		<td> <?php echo $usuario["email"]?> </td>
	     	</tr>
	     	<?php } ?>
	     	
	     	<?php if (isset ($usuario["provincia"])) { ?>
	     	<tr>
	     		<th> Provincia: </th>
	     		<td> <?php echo $usuario["provincia"]?> </td>
	     	</tr>
	     	<?php } ?>
	     	<?php if (isset ($usuario["direccion"])) { ?>
	     	<tr>
	     		<th> Dirección: </th>
	     		<td> <?php echo $usuario["direccion"]?> </td>
	     	</tr>
	     	<?php } ?>
	     </table>
	</div>

	</div>
	
	</div>
	
	<br>
	<br>
	
	
	<?php
		$_SESSION["login"] = $usuario["nick"]; 
	?>
	
	
	<br>
	<br>
	
	
	<div class="row">
	
	
	<div class="col-6 texto3" >
	
	<form action="menu.php" method="post" name="menu">
		<button class="botonAccion3"  type="submit" name="iniciarSesion" >  Iniciar sesión </button>
		</form> 
	
	
	</div>
	
	<div class="col-6 texto6" >
	
	<form action="logout.php" method="post" name="logout">
	<button class="botonAccion1"  type="submit" name="cerrarSesion" >  Cerrar sesión </button>
		</form> 
	
	
	</div>
	
	</div>
	
	
	
	
	
	
				
		<?php } else { ?>
		
				<div class="row">
				
				<div class = "col-12 texto6">
				<h2> Ya existe usuario con el NIF: <?php echo $usuario["nif"]?> </h2>
				</div>
				</div>
				
				
		<?php } ?>

	</main>

	
</body>
</html>
<?php
cerrarConexionBD($conexion);
?>

