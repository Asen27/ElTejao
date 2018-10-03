<?php 
	session_start();
	
	require_once ("gestionBD.php");
	require_once ("gestionarAlmacen.php");

	
	
	if ((isset($_SESSION["usuario"]) && $_SESSION["usuario"]["puesto"] == "ALMACEN" || $_SESSION["usuario"]["puesto"] == "GERENTE")) {
		$usuario = $_SESSION["usuario"];
		
		
		
		// CARGA DE DATOS:
	
	
	$conexion = crearConexionBD();
	
	if (isset($_SESSION["menuGruposProductos"])) {
		$arrayGrupos = array();
		$grupos = consultarGruposProductos($conexion);	
		foreach ($grupos as $grupo) {
			array_push($arrayGrupos, $grupo);
		   }
		
		
		$arrayProductos = array();
		$productos = consultarProductos($conexion);	
		foreach ($productos as $producto) {
			array_push($arrayProductos, $producto['NOMBRE']);
		   }
		
		
		if (isset($_SESSION["erroresGrupoProductos"])) {
		$erroresGrupo = $_SESSION["erroresGrupoProductos"];
		unset($_SESSION["erroresGrupoProductos"]);
	
		$datosGrupo = $_SESSION["formularioGrupoProductos"];
		unset($_SESSION["formularioGrupoProductos"]);
		}
		
		if (isset($_SESSION["erroresEntregar"])) {
		$erroresEntregar = $_SESSION["erroresEntregar"];
		unset($_SESSION["erroresEntregar"]);
	
		$datosEntregar = $_SESSION["formularioEntregar"];
		unset($_SESSION["formularioEntregar"]);

		}
		
		
		
		if (isset($_SESSION["subMenuComprobarExistencias"])) {
			$subMenuComprobarExistencias = $_SESSION["subMenuComprobarExistencias"];
			unset($_SESSION["subMenuComprobarExistencias"]);
			$arrayProductosAComprar = array();
			$productosAComprar = consultarProductosAComprar($conexion);
			foreach ($productosAComprar as $producto) {
			array_push($arrayProductosAComprar, $producto['NOMBREDEPRODUCTO']);
			}
			
			terminarConsulta6($conexion);
		}
		
		if (isset($_SESSION["subMenuComprobarCaducidad"])) {
			$subMenuComprobarCaducidad = $_SESSION["subMenuComprobarCaducidad"];
			unset($_SESSION["subMenuComprobarCaducidad"]);
			$arrayProductosCaducados = array();
			$productosCaducados = consultarProductosCaducados($conexion);
			foreach ($productosCaducados as $producto) {
			array_push($arrayProductosCaducados, $producto);
			}
			
			terminarConsulta7($conexion);
		}
		
		
		}
	
	
	cerrarConexionBD($conexion);
	
	
		if (isset($_SESSION["grupoProductos"])) {
			$grupoProductos = $_SESSION["grupoProductos"];   
			unset($_SESSION["grupoProductos"]);
		}
	
	
		if (isset($_SESSION["mensaje"])) {
			$mensaje = $_SESSION["mensaje"];   
			unset($_SESSION["mensaje"]);
		}
	

		?>






<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="UTF-8">
		<meta name="author" content="Asen Rangelov Baykushev">
		<meta name="description" content="Trabajo práctico para la asignatura IISSI">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="css/hojaDeEstilo.css" type="text/css" rel="stylesheet">
		<script src="js/scripts.js" type="text/javascript"></script>
		
				
		<title> Gestión del almacén </title>
		

		</head>

		<body>
<?php 
include_once("cabecera.php");
?>


<div class="row" class="col-12">
			<nav>
				<ul class="nav">
					<form method="post" action="controlador_gestion_almacen.php" >
				
						<li> 
							<button <?php if (isset($_SESSION["menuGruposProductos"])) { echo "class='activo' "; }?>
							type="submit" id="gruposProductos" name="gruposProductos" > Productos </button>
						</li>
										
						   <li class="derecha" > 
							<button type="submit" name="logout" > Cerrar sesión </button>
						</li>
				</ul>
				</form>
			</nav>
</div>





<main>
	
	<div class="row">
			<?php

			if (isset($erroresGrupo)) {
				foreach ($erroresGrupo as $error) {
					print("<div class='col-12 texto4 error'>");
					print("$error");
					print("</div>");
				}
				} elseif (isset($erroresEntregar)) {
					foreach ($erroresEntregar as $error) {
					print("<div class='col-12 texto4 error'>");
					print("$error");
					print("</div>");
				}	
					
	
				}
			?>
		</div>
	
	
	
	
	
<div class="row">

<?php $puestos = array(
			"RESERVAS"=>"Encargado de reservas",
			"ALMACEN"=>"Encargado de almacen",
			"GERENTE"=>"Gerente");	?>


<div class="col-4">

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



<?php   if (isset($_SESSION["menuGruposProductos"])) { ?>
			
        <div class="col-8">
        	
      
       
		<h2 class="texto"> Grupos de productos: </h2>
		
		
		  <?php 
		  
		  if (isset($mensaje)) { ?>
		  	<div>
        	<span class="texto4"> <em> <?php echo $mensaje; ?></em> </span>
        	</div>
        	<br>
       <?php }
       
	   
	   
       if (!empty($arrayGrupos)) {
       
       ?>
       <br>	
       <div class="tabla-responsive">
		<table>
		<tr>
			<th> <strong> Grupo: </strong> </th>
			<th> <strong> Producto: </strong> </th>
			<th> <strong> Fecha de caducidad: </strong> </th>
			<th> <strong> Cantidad: </strong> </th>
			<th>  Acciones:  </th>
		</tr>
        
       
        
      <?php  foreach($arrayGrupos as $grupo) { 
      		if (isset($grupo['OID_GP'])) {
      	
      	?>
      	
        <form method="post" action="controlador_gestion_almacen.php">
			
		<input type="hidden" name = "oidGrupo" id = "oidGrupo" value="<?php echo $grupo['OID_GP'] ?>">
		<input type="hidden" name = "cantidadExistencia" id = "cantidadExistencia" value="<?php echo $grupo['CANTIDADEXISTENCIA'] ?>">
		<tr>
			<td> <?php echo $grupo['OID_GP']; ?> </td>
			<td> <?php echo $grupo['NOMBRE']; ?> </td>
			<td> <?php echo $grupo['FECHACADUCIDAD']; ?> </td>
			<td> <?php echo $grupo['CANTIDADEXISTENCIA']. " " .$grupo['UNIDADMEDIDA']; ?> </td>
			<td> <?php if(!(isset($grupoProductos) && $grupo['OID_GP'] == $grupoProductos)) {  ?>
				<button type="submit" name="entregarProductos" class="botonAccion5"> Entregar productos </button>
				<?php } ?>
				<button type="submit" name="tirarProductos" class="botonAccion1"> Tirar a la basura </button></td>
				 
		</tr>
		
		<?php if((isset($grupoProductos) && $grupo['OID_GP'] == $grupoProductos) || (isset($erroresEntregar) && $datosEntregar["oidGrupo"] == $grupo['OID_GP'])) {  ?>
		<tr>
			<td colspan="5">
				<div>
					<label for="cantidad"> ¿Cuántos/as <?php echo " " .$grupo['UNIDADMEDIDA']. " "; ?> va a coger? <strong> * </strong> </label>
					<input type="number" id="cantidad" name="cantidad" size="2" min="1" max="<?php echo $grupo['CANTIDADEXISTENCIA']; ?>" 
					<?php 
if (isset($datosEntregar["cantidad"])) {
	echo "value='" .$datosEntregar["cantidad"]. "'";
}

?>
					
					>
				</div>

				</div>
			<div><button class="botonAccion3" type="submit" name="entregar"> Entregar </button> </div>	
				</td>
		</tr>
		<?php }?>
		</form>
   
      <?php  }
         } ?>
        </table>
        </div>
       
        <?php } else { ?>
        	<div>
        <span class="texto4"> No se ha encontrado ningún elemento. </span>
        </div>
        <br>
         <?php }  ?>
        <br>
        
        
        
        <?php if(!isset($_REQUEST["anadirGrupo"])) { ?>
     
     
     
      <form class= "boton" method="post" name = "nuevoGrupo" action="gestion_almacen.php">
		<button  class= "botonAccion3" type="submit" name ="anadirGrupo"> Añadir nuevo grupo</button>
		</form>
     
    
     <?php } ?>
     
     <?php if(!isset($subMenuComprobarExistencias)) { ?>
     
  
       <form class= "boton" method="post" name = "existencias" action="controlador_gestion_almacen.php">
		<button  class= "botonAccion5" type="submit" name ="comprobarExistencias"> Comprobar existencias </button>
		</form>
    
     <?php } ?>
     
     <?php if(!isset($subMenuComprobarCaducidad)) { ?>
     
  
       <form class= "boton" method="post" name = "caducidad" action="controlador_gestion_almacen.php">
		<button  class= "botonAccion1" type="submit" name ="comprobarFechasCaducidad"> Alertar de productos caducados  </button>
		</form>
    
     <?php } ?>
     
     
  
     
     <br>
     
         <?php if(isset($_REQUEST["anadirGrupo"]) || isset($erroresGrupo)) { ?>
        
        <div class="texto5">
<form method="post" name = "introducirNuevoGrupo" action="controlador_gestion_almacen.php">
	
<div>
					<label for="nombreProducto"> Nombre de producto <strong> * </strong> </label>
					<select name="nombreProducto" id="nombreProducto" required>
				<?php if (!empty($arrayProductos)) {
					foreach ($arrayProductos as $producto) { ?>
						<option value="<?php echo $producto; ?>"<?php if (isset($datosGrupo['nombreProducto']) && $datosGrupo['nombreProducto'] == $producto) {
						echo "selected"; } ?>	
							> <?php echo $producto; ?> </option>
				<?php	}
				}?>		
						
				</select> 


</div>





<div>
<label for="fechaCaducidad"> Fecha de caducidad <strong> * </strong> </label>
<input type="date" name="fechaCaducidad" id="fechaCaducidad" placeholder="yyyy-mm-dd" 
title="Introduzca la fecha de caducidad de los productos del grupo. La fecha de nacimiento debe seguir el patrón yyyy-mm-dd." required 
<?php if (isset($datosGrupo["fechaCaducidad"])) {
						echo "value='".$datosGrupo["fechaCaducidad"]."'";
					}
					?>


>
</div>

<div>
<label for="cantidad"> Cantidad <strong> * </strong> </label>
<input type="number" name="cantidad" id="cantidad" size ="4" min="1" title="Introduzca aquí el número de produtos en el grupo"  placeholder="ej: 12" required
<?php if (isset($datosGrupo["cantidad"])) {
						echo "value='".$datosGrupo["cantidad"]."'";
					}
					?>

>
</div>

<button class="botonAccion3" type="submit" name="introducirGrupo" > Añadir el grupo </button>
</form>
</div>

             
        
        
   <?php } elseif(isset($subMenuComprobarExistencias)) { ?>
        
        <div class="texto2">

<?php if(!empty($arrayProductosAComprar)) { ?>											
		<ul>											
	<?php foreach ($arrayProductosAComprar as $producto) { ?>									
								
		<li> Hay que comprar <?php echo $producto; ?>  </li>						
						
<?php  } ?>	
	</ul>					
	
<?php }  else { ?>	
	<p> De momento hay suficientes productos </p>
<?php  } ?>
</div>

    
      
      
      
     <?php } elseif(isset($subMenuComprobarCaducidad)) { ?>
        
        <div class="texto6">

<?php if(!empty($arrayProductosCaducados)) { ?>											
		<ul>											
	<?php foreach ($arrayProductosCaducados as $producto) { ?>									
								
		<li> Los/Las <?php echo $producto['CANTIDAD']; ?>  <?php echo $producto['UNIDAD']; ?>  de  <?php echo $producto['NOMBREPRODUCTO']; ?> 
			ya han caducado. Tiene que tirar los productos del grupo <?php echo $producto['OID_GRUPO']; ?> a la basura;  </li>						
						
<?php  } ?>	
	</ul>					
	
<?php }  else { ?>	
	<p> No hay productos caducados </p>
<?php  } ?>
</div>

        <?php }  ?>    
      
      
      
      
      
      
      
      
        
        
        
        
      
      
      
      
      
      
        
   <?php }  ?>     
  
        </div>
        
        
        

		
	
</main>


		
		
		
		
		
		
		
		
		
		
		



		
	</body>

</html>


<?php } else {
	Header("Location: index.php"); 
}


?>



