<?php 
	session_start();
	
	$excepcion = $_SESSION["excepcion"];
	unset($_SESSION["excepcion"]);
	

?>

<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="UTF-8">
		<meta name="author" content="Asen Rangelov Baykushev">
		<meta name="description" content="Trabajo práctico para la asignatura IISSI">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="css/hojaDeEstilo.css" type="text/css" rel="stylesheet">
		<title> Página de error </title>
		</head>
<body>	
	
<?php	
	include_once("cabecera.php"); 
?>	
	<div class="row">
	<div class="col-12 texto5">
		<h2>Ups!</h2>

		<p>Ocurrió un problema para acceder a la base de datos. </p>
		
	</div>
	</div>
		
	<div class="row">	
	<div class='col-12 texto4 error'>	
		<?php echo "Información relativa al problema: $excepcion;" ?>
	</div>
	</div>




</body>
</html>