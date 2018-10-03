<?php 

    require_once ("gestionBD.php");
	require_once ("gestionarReservas.php");

			
			$conexion = crearConexionBD();
			
			$datos1 = obtenerDatosPedido($conexion);
			$datos2 = obtenerDatosPedido2($conexion);
			
			terminarConsulta4($conexion);
			terminarConsulta5($conexion);
			
			cerrarConexionBD($conexion);
			
		    
		    
			
			
		    $datosPedido = array();
			foreach ($datos1 as $dato) {
				$identificadorMesa =  $dato['IDENTIFICADORMESA'];
				$fecha =  $dato['FECHAYHORARESERVA'];
			}
			
			
			

?>










<!DOCTYPE html>
<html lang="es">
<head>
  		<meta charset="UTF-8">
		<meta name="author" content="Asen Rangelov Baykushev">
		<meta name="description" content="Trabajo práctico para la asignatura IISSI">
  <title>Pedido</title>
</head>

<body>

<?php
	include_once("cabecera.php");
?>

<main>
	
	
	<h1> <strong> PEDIDO </strong> </h1>
	
	<h2>Se tienen que servir los siguientes platos en la mesa <?php echo $identificadorMesa; ?> el día <?php echo substr($fecha, 0, 14); ?> : </h2>
	

	
	<br>
	
	
	
		
		<div>
		<ul>
			
			<?php  foreach ($datos2 as $linea) {  
				if (isset($linea['NOMBREDEPLATOPEDIDO'])) { ?>
				
				<li> <?php echo $linea['NOMBREDEPLATOPEDIDO']. " (" .$linea['RACIONDEPLATOPEDIDO']. ") x "  .$linea['UNIDADESPEDIDAS']; ?>   </li>
				
			
			
			<?php  } } ?>
			
		</ul>
		</div>
		
	
	
	<hr>
	

	
	
	
</main>	
</body>
</html>