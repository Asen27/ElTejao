<?php 

    require_once ("gestionBD.php");
	require_once ("gestionarReservas.php");

			
			$conexion = crearConexionBD();
			
			$datos1 = obtenerDatosFactura($conexion);
			$datos2 = obtenerDatosFactura2($conexion);
			
			terminarConsulta2($conexion);
			terminarConsulta3($conexion);
			
			cerrarConexionBD($conexion);
			
		    
		    
			
			
		    $datosFactura = array();
			foreach ($datos1 as $dato) {
				$codigo =  $dato['CODIGODEFACTURA'];
				$fechaElaboracion =  $dato['FECHADEELABORACION'];
				$precioTotal  = $dato['PRECIOTOTAL'];
			}
			
			$datosPlatos = array();
			foreach ($datos2 as $dato) {
			array_push($datosPlatos, $dato);
			}

?>



<!DOCTYPE html>
<html lang="es">
<head>
  		<meta charset="UTF-8">
		<meta name="author" content="Asen Rangelov Baykushev">
		<meta name="description" content="Trabajo práctico para la asignatura IISSI">
  <title>Factura</title>
</head>

<body>

<?php
	include_once("cabecera.php");
?>

<main>
	
	
	<h1> <strong> FACTURA </strong> </h1>
	
	<section> <strong> Código de la factura: </strong>  <?php echo $codigo;  ?>   </section>
	<section> <strong> Fecha de elaboración: </strong>  <?php echo $fechaElaboracion;  ?>   </section>
	
	<br>
	
	
	<?php if (!empty($datosPlatos)) { ?>
		
		<div>
		<ul>
			
			<?php  foreach ($datosPlatos as $linea) {  
				if (isset($linea['NOMBREDEPLATO'])) { ?>
				
				<li> <?php echo $linea['NOMBREDEPLATO']. " (" .$linea['RACIONDEPLATO']. ") x "  .$linea['UNIDADES']. " = " .$linea['PRECIO']. "€"; ?>     </li>
				
			
			
			<?php  } } ?>
			
		</ul>
		</div>
		
	<?php } ?>
	
	<hr>
	
	<section> <strong> Precio total: </strong>  <?php echo $precioTotal. "€";  ?>   </section>
	
	
	
	
	
	
	
</main>	
</body>
</html>