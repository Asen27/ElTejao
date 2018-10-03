<?php 
	session_start();
	
	require_once ("gestionarRestaurante.php");
	require_once ("gestionBD.php");
	require_once ("paginacion.php");	
	
	if (isset($_SESSION["usuario"]) && $_SESSION["usuario"]["puesto"] == "GERENTE") {
		$usuario = $_SESSION["usuario"];
		
		//--------------------- PAGINACIÓN ----------------------------------------
		if (isset($_GET["cambiar"]) || isset($_GET["pagina_seleccionada"])) {
				$pagina_seleccionada = (int) $_GET["pagina_seleccionada"];
				$pag_tam = (int) $_GET["pag_tam"];
	         }
			
			elseif ( isset ($_SESSION["PAG_NUM"]) && isset($_SESSION["PAG_TAM"])) {
				$pagina_seleccionada = (int) $_SESSION["PAG_NUM"];
				$pag_tam = (int) $_SESSION["PAG_TAM"];
		
			 } else {
		
				$pagina_seleccionada = 1;
				$pag_tam = 5; 
	    	}
	
			
			if ($pagina_seleccionada < 1) $pagina_seleccionada = 1;
	        if ($pag_tam < 1) $pag_tam = 5;
			
			unset ($_SESSION["PAG_TAM"]);
	        unset ($_SESSION["PAG_NUM"]);
		
		

		
		// CARGA DE DATOS:
		
	    $conexion = crearConexionBD();
		
		
		
		if (isset($_SESSION["menuPlatos"])) {
			
			$consulta = "SELECT * FROM Platos";
			
			$total_registros = (int) total_consulta($conexion, $consulta);
	        $total_paginas = (int) ($total_registros / $pag_tam);
	
	if ($total_registros % $pag_tam > 0) $total_paginas++;
	if($pagina_seleccionada > $total_paginas) $pagina_seleccionada = $total_paginas;
	if($pag_tam > $total_registros) $pag_tam = $total_registros;
	
	
	 $_SESSION["PAG_NUM"] = $pagina_seleccionada;
	 $_SESSION["PAG_TAM"] = $pag_tam;
	
			
			
		$arrayPlatos = array();
		$platos = consulta_paginada($conexion, $consulta, $pagina_seleccionada, $pag_tam);	
		foreach ($platos as $plato) {
			array_push($arrayPlatos, $plato);
		   }
		
		
		
		$platosPedidos = consultarLineasDePedido($conexion);
			$nombresPlatos = array();
			foreach ($platosPedidos as $comprobarNombre) { 
				array_push($nombresPlatos, $comprobarNombre['NOMBRE']);
	}
		
		if (isset($_SESSION["erroresPlato"])) {
		$erroresPlato = $_SESSION["erroresPlato"];
		unset($_SESSION["erroresPlato"]);
	
		$datosPlato = $_SESSION["formularioPlato"];
		unset($_SESSION["formularioPlato"]);
		
		
		}
	
		} elseif (isset($_SESSION["menuTiposMesa"])) {
			
			$consulta = "SELECT * FROM Mesas";
			
			$total_registros = (int) total_consulta($conexion, $consulta);
	        $total_paginas = (int) ($total_registros / $pag_tam);
	
	
	if ($total_registros % $pag_tam > 0) $total_paginas++;
	if($pagina_seleccionada > $total_paginas) $pagina_seleccionada = $total_paginas;
	if($pag_tam > $total_registros) $pag_tam = $total_registros;
	
	
	 $_SESSION["PAG_NUM"] = $pagina_seleccionada;
	 $_SESSION["PAG_TAM"] = $pag_tam;
			
		$arrayTiposMesa = array();
		$tiposMesa = consulta_paginada($conexion, $consulta, $pagina_seleccionada, $pag_tam);	
		foreach ($tiposMesa as $tipoMesa) {
			array_push($arrayTiposMesa, $tipoMesa);
		   }
		
		if (isset($_SESSION["erroresTipoMesa"])) {
		$erroresTipoMesa = $_SESSION["erroresTipoMesa"];
		unset($_SESSION["erroresTipoMesa"]);
	
		$datosTipoMesa = $_SESSION["formularioTipoMesa"];
		unset($_SESSION["formularioTipoMesa"]);
		
		
		}
		
		
		
		
		
		
		
		}
		
			elseif (isset($_SESSION["menuMesas"])) {
			
			$consulta = "SELECT * FROM MesasConcretas NATURAL JOIN Mesas ORDER BY IDENTIFICADOR DESC";
			
			$total_registros = (int) total_consulta($conexion, $consulta);
	        $total_paginas = (int) ($total_registros / $pag_tam);
	
	
	if ($total_registros % $pag_tam > 0) $total_paginas++;
	if($pagina_seleccionada > $total_paginas) $pagina_seleccionada = $total_paginas;
	if($pag_tam > $total_registros) $pag_tam = $total_registros;
	
	
	 $_SESSION["PAG_NUM"] = $pagina_seleccionada;
	 $_SESSION["PAG_TAM"] = $pag_tam;
			
		$arrayMesas = array();
		$mesas = consulta_paginada($conexion, $consulta, $pagina_seleccionada, $pag_tam);	
		foreach ($mesas as $mesa) {
			array_push($arrayMesas, $mesa);
		   }
		
			$reservas = consultarReservas($conexion);
			$mesasReservadas = array();
			foreach ($reservas as $comprobarMesa) { 
				array_push($mesasReservadas, $comprobarMesa['IDENTIFICADOR']);
	}
		
		
		
		
		
		
		
		} elseif (isset($_SESSION["menuProductos"])) {
			
			$consulta = "SELECT * FROM Productos";
			
			$total_registros = (int) total_consulta($conexion, $consulta);
	        $total_paginas = (int) ($total_registros / $pag_tam);
	
	
	if ($total_registros % $pag_tam > 0) $total_paginas++;
	if($pagina_seleccionada > $total_paginas) $pagina_seleccionada = $total_paginas;
	if($pag_tam > $total_registros) $pag_tam = $total_registros;
	
	
	 $_SESSION["PAG_NUM"] = $pagina_seleccionada;
	 $_SESSION["PAG_TAM"] = $pag_tam;
			
		$arrayProductos = array();
		$productos = consulta_paginada($conexion, $consulta, $pagina_seleccionada, $pag_tam);	
		foreach ($productos as $producto) {
			array_push($arrayProductos, $producto);
		   }
		
		$grupos= array();
		$productosAlmacen = consultarGruposProductos($conexion);	
		foreach ($productosAlmacen as $comprobarProducto) {
			array_push($grupos, $comprobarProducto['NOMBRE']);
		   }
	
	if (isset($_SESSION["erroresProducto"])) {
		$erroresProducto = $_SESSION["erroresProducto"];
		unset($_SESSION["erroresProducto"]);
	
		$datosProducto = $_SESSION["formularioProducto"];
		unset($_SESSION["formularioProducto"]);
		
		
		}
	
	
		
		}
	
	


		cerrarConexionBD($conexion);
		
		if (isset($_SESSION["mensaje"])) {
			$mensaje = $_SESSION["mensaje"];   
		    unset($_SESSION["mensaje"]);
		}
		
		if (isset($_SESSION["marcar"])) {
			$marcar = $_SESSION["marcar"];   
			unset($_SESSION["marcar"]);
		}
		
		

?>






<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="UTF-8">
		<meta name="author" content="Asen Rangelov Baykushev">
		<meta name="description" content="Trabajo práctico para la asignatura IISSI">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title> Gestión del restaurante </title>
		<link href="css/hojaDeEstilo.css" type="text/css" rel="stylesheet">
		<script src="js/scripts.js" type="text/javascript"></script>
	
	




		</head>

		<body>

<?php 
include_once("cabecera.php");
?>


<div class="row">

			<nav class="col-12">
				<ul class="nav">
					<form method="post" name = "navegador" action="controlador_gestion_ElTejao.php" >
						  <li > 
							<button <?php if (isset($_SESSION["menuPlatos"])) { echo "class='activo' "; }?>
							 id="platos" name="platos" type="submit"> Platos </button>
						</li>
						<li > 
							<button <?php if (isset($_SESSION["menuTiposMesa"])) { echo "class='activo' "; }?>
							id="tiposMesa" name="tiposMesa" type="submit"> Tipos de mesas</button>
						</li>
					    <li > 
							<button <?php if (isset($_SESSION["menuMesas"])) { echo "class='activo' "; }?>
							id="mesas" name="mesas" type="submit">  Mesas </button>
						</li>
						<li > 
							<button <?php if (isset($_SESSION["menuProductos"])) { echo "class='activo' "; }?>
							id="productos" name="productos" type="submit">  Productos </button>
						</li>
						   <li class="derecha" > 
							<button type="submit" name="logout" > Cerrar sesión </button>
						</li>
                      </form>
				</ul>

			</nav>

</div>

<main>
	
	<div class="row">
			<?php

			if (isset($erroresPlato)) {
				foreach ($erroresPlato as $error) {
					print("<div class='col-12 texto4 error'>");
					print("$error");
					print("</div>");
				}
			} elseif (isset($erroresTipoMesa)) {
				
				foreach ($erroresTipoMesa as $error) {
					print("<div class='col-12 texto4 error'>");
					print("$error");
					print("</div>");
				}
			} elseif(isset($erroresProducto)) {
				foreach ($erroresProducto as $error) {
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
	

	<!--  ----------------------------------------   MENÚ PLATOS -------------------------------------------------  -->
	<?php   if (isset($_SESSION["menuPlatos"])) { ?>
			
        <div class="col-8">
        	
      
       
		<h2 class="texto"> Platos del restaurante: </h2>
		
		  <?php  if (isset($mensaje)) { ?>
        	<span class="texto4"> <em> <?php echo $mensaje; ?></em> </span>
        	<br>
       <?php } 
       
       if (!empty($arrayPlatos)) {
       
       ?>	
 		<br>
       <div class="tabla-responsive">
		<table>
		<tr>
			<th> <strong> Nombre: </strong> </th>
			<th> <strong> Precio (media ración): </strong> </th>
			<th> <strong> Precio (ración completa): </strong> </th>
			<th>  Acciones:  </th>
		</tr>
        
       
        
      <?php  foreach($arrayPlatos as $plato) { 
      		if (isset($plato['NOMBRE'])) {
      	
      	?>
					
					
		<form method="post" action="controlador_gestion_ElTejao.php">
			
		<input type="hidden" name = "nombre" id ="nombre" value="<?php echo $plato['NOMBRE'] ?>">
		<tr>
			<td> <?php echo $plato['NOMBRE'] ?> </td>
			<td> <?php if (isset($plato['PRECIOMEDIARACION'])) {
				echo $plato['PRECIOMEDIARACION']; } else { ?>
					--
			<?php	} ?> </td>
			<td> <?php echo $plato['PRECIORACION'] ?> </td>
			<td> <button type="submit" name="eliminarPlato" <?php if (in_array($plato['NOMBRE'], $nombresPlatos)) {
				echo " class='botonAccion1 botonNoActivo' disabled title='Este plato no se puede eliminar porque hay clientes que lo hayan pedido.'";
			} else { echo "class='botonAccion1'"; } ?> 
				> Eliminar plato </button> </td>
		</tr>
		</form>
	<?php	}
					}
			?>
									
	
</table>
</div>
<?php } else { ?>
        <span class="texto4"> No se ha encontrado ningún elemento. </span>
         <?php }  ?>
        <br>

   	 <nav class="texto2">
   	 	<span> <strong>Página: </strong> </span>
		<div class="paginacion">
			<?php
			for ($i=1; $i <= $total_paginas; $i++) { 
				if ($i == $pagina_seleccionada) { ?>		
				<button type="button" class="actual"> <?php echo " ".$i." "; ?>  </button>
				<?php	
				} else {
					?>
					<form class="boton" action="gestion_ElTejao.php" method="get">
						<input type="hidden" name="pagina_seleccionada" value="<?php echo $i; ?>">
						<input type="hidden" name="pag_tam" value="<?php echo $pag_tam; ?>">
						<button type="submit"> <?php echo $i; ?> </button> 
					</form>
					<?php
				} }
			?>
		</div>
		<br>
		<form method="get" action="gestion_ElTejao.php">
			<input type="hidden" id="pagina_seleccionada" name="pagina_seleccionada" value="<?php echo $pagina_seleccionada ?>">
			Mostrando
			<input type="number" id="pag_tam" name="pag_tam" min="1" size="3" max="<?php echo $total_registros ?>" value="<?php echo $pag_tam ?>" >
			platos de <?php echo $total_registros ?>
			<button class="botonAccion2" type="submit" id="cambiar" name="cambiar" value="Cambiar"> Cambiar </button>
		</form>
	</nav>









<?php
if (isset($_REQUEST["anadirPlato"]) || isset($erroresPlato)) { ?>

<div class = "texto3">
<form method="post" name ="introducirNuevoPlato" action="controlador_gestion_ElTejao.php">
	<div>
		
	<label for="nombreNuevoPlato"> Nombre del plato <strong> * </strong> </label>
<input type="text" name="nombreNuevoPlato" id="nombreNuevoPlato" maxlength="60"  size ="60" title="Introduzca aquí el nombre del nuevo plato" required
<?php if(isset($datosPlato["nombreNuevoPlato"])) {
	echo "value='".$datosPlato["nombreNuevoPlato"]."'";
}?>
>
</div>
<div>
<label for="precioMediaRacion"> Precio (media ración)  </label>
<input type="number" name="precioMediaRacion" id="precioMediaRacion" min="0" step="0.01" size ="5"  title="Establezca el precio (1/2 ración)"

<?php if(isset($datosPlato["precioMediaRacion"])) {
	echo "value='".$datosPlato["precioMediaRacion"]."'";
}?>
>
<button type="button" class="botonAccion4" id="reset" onclick="resetInput('precioMediaRacion') "> no tiene </button>
</div>
<div>
<label for="precioRacion"> Precio (ración completa) <strong> * </strong> </label>
<input type="number" name="precioRacion" id="precioRacion"  min="0" step="0.01" size ="5"  title="Establezca el precio (ración completa)" required
<?php if(isset($datosPlato["precioRacion"])) {
	echo "value='".$datosPlato["precioRacion"]."'";
}?>

>
</div>
<button type="submit" class="botonAccion5" name="introducirPlato" > Añadir el plato </button>
</form>
</div>

<?php  } else { ?>


<form method="post" name = "nuevoPlato" action="gestion_ElTejao.php">
<button class="botonAccion3" type="submit" name ="anadirPlato"> Añadir nuevo plato</button>
</form>
<?php } ?>



</div>	



	<!--  ----------------------------------------   MENÚ TIPOS DE MESAS -------------------------------------------------  -->
	<?php  }  elseif (isset($_SESSION["menuTiposMesa"])) { ?>
			
        <div class="col-8">
        	
      
       
		<h2 class="texto"> Tipos de mesas: </h2>
		
		  <?php  if (isset($mensaje)) { ?>
		  	<div>
        	<span class="texto4"> <em> <?php echo $mensaje; ?></em> </span>
        	</div>
        	<br>
        	
       <?php } 
       
       if (!empty($arrayTiposMesa)) {
       
       ?>	
       <br>
       <div class="tabla-responsive">
		<table>
		<tr>
			<th> <strong> Tipo: </strong> </th>
			<th> <strong> Capacidad: </strong> </th>
			<th> <strong> Ubicación: </strong> </th>
			<th> <strong> Fumar/No fumar: </strong> </th>
			<th> <strong> № de mesas: </strong> </th>
			<th>  Acciones:  </th>
		</tr>
        
       
        
      <?php  foreach($arrayTiposMesa as $tipoMesa) { 
      		if (isset($tipoMesa['OID_M'])) {
      	
      	?>
					
					
		<form method="post" action="controlador_gestion_ElTejao.php">
			
		<input type="hidden" name = "oidMesa" id ="oidMesa" value="<?php echo $tipoMesa['OID_M']; ?>">
		<tr>
			<td> <?php echo $tipoMesa['OID_M']; ?> </td>
			<td> <?php echo $tipoMesa['MAXPERSONAS']; ?> </td>
			<td> <?php echo $tipoMesa['UBICACION']; ?> </td>
			<td> <?php if($tipoMesa['TIPO'] == "noFumadores") { echo " no fumadores"; 
			} else { echo "fumadores"; 
			} ?> </td>
			<td> <?php echo $tipoMesa['NUMMESASCONCRETAS']; ?> </td>
			<td><button class = "botonAccion3" type="submit" name="introducirMesa"> Añadir mesa </button> 
				<button type="submit" name="eliminarTipoMesa" <?php if ($tipoMesa['NUMMESASCONCRETAS'] <> "0") { echo " class='botonAccion1 botonNoActivo' disabled title='Este tipo de mesa no se puede eliminar porque en el restaurante hay mesas de dicho tipo'"; } 
					else { echo "class='botonAccion1'";} ?> > Eliminar tipo </button> </td>
		</tr>
		</form>
	<?php	}
					}
			?>
									
	
</table>
</div>
<?php } else { ?>
        <span class="texto4"> No se ha encontrado ningún elemento. </span>
         <?php }  ?>
        <br>

   	 <nav> <nav class="texto2">
   	 	<span> <strong>Página: </strong> </span>
		<div class="paginacion">
			<?php
			for ($i=1; $i <= $total_paginas; $i++) { 
				if ($i == $pagina_seleccionada) { ?>		
				<button type="button" class="actual"> <?php echo " ".$i." "; ?>  </button>
				<?php	
				} else {
					?>
					<form class="boton" action="gestion_ElTejao.php" method="get">
						<input type="hidden" name="pagina_seleccionada" value="<?php echo $i; ?>">
						<input type="hidden" name="pag_tam" value="<?php echo $pag_tam; ?>">
						<button type="submit"> <?php echo $i; ?> </button> 
					</form>
					<?php
				} }
			?>
		</div>
			<br>
		<form method="get" action="gestion_ElTejao.php">
			<input type="hidden" id="pagina_seleccionada" name="pagina_seleccionada" value="<?php echo $pagina_seleccionada ?>">
			Mostrando
			<input type="number" id="pag_tam" name="pag_tam" min="1" size="3" max="<?php echo $total_registros ?>" value="<?php echo $pag_tam ?>">
			tipos de <?php echo $total_registros ?>
			<button class="botonAccion2" type="submit" id="cambiar" name="cambiar" value="Cambiar"> Cambiar </button>
		</form>
	</nav>

<?php
if (isset($_REQUEST["anadirTipoMesa"]) || isset($erroresTipoMesa)) { ?>

<div class="texto3">
<form method="post" name ="introducirNuevoTipoMesa" action="controlador_gestion_ElTejao.php">
	<div>
		
	<label for="capacidad"> Capacidad <strong> * </strong> </label>
<input type="number" name="capacidad" id="capacidad" size ="2" min="1" title="¿Cuántas personas como mucho podrán acomodarse en la mesa?" required
<?php 
if (isset($datosTipoMesa["capacidad"])) {
	echo "value='" .$datosTipoMesa["capacidad"]. "'";
}

?>

>
</div>

<div>
  <label title="Elija la ubicación de la mesa"> Ubicación <strong>*</strong></label>

	<label> <input name="ubicacion" type="radio" value="interior" checked> Interior </label>
    <label> <input name="ubicacion" type="radio" value="exterior"> Exterior </label>
</div>

<div>
  <label title="¿La mesa es para fumadores o no fumadores?"> Tipo <strong>*</strong></label>

	<label> <input name="fumar" type="radio" value="noFumadores" checked> Para no fumadores </label>
    <label> <input name="fumar" type="radio" value="fumadores"> Para fumadores </label>
</div>


<button type="submit" name="introducirTipoMesa" class="botonAccion2"> Añadir </button>
</form>
</div>

<?php  } else { ?>


<form method="post" name = "nuevoTipoMesa" action="gestion_ElTejao.php">
<button class="botonAccion3"  type="submit" name ="anadirTipoMesa"> Añadir nuevo tipo de mesa</button>
</form>

<?php } ?>



</div>

<!--  ----------------------------------------   MENÚ Mesas -------------------------------------------------  -->

<?php } elseif (isset($_SESSION["menuMesas"])) { ?>
			
        <div class="col-8">
        	
      
       
		<h2 class="texto"> Mesas: </h2>
		
		
        	
       <?php 
       
       if (!empty($arrayMesas)) {
       
       ?>	
 
       <div class="tabla-responsive">
		<table>
		<tr>
			<th> <strong> Identificador: </strong> </th>
			<th> <strong> Tipo: </strong> </th>
			<th>  Acciones:  </th>
		</tr>
        
       
        
      <?php  foreach($arrayMesas as $mesa) { 
      		if (isset($mesa['IDENTIFICADOR'])) {
      	
      	?>
					
					
		<form method="post" action="controlador_gestion_ElTejao.php">
			
		<input type="hidden" name = "id" id ="id" value="<?php echo $mesa['IDENTIFICADOR']; ?>">
		<tr>
			<td> <?php if (isset($marcar) && $marcar == $mesa['IDENTIFICADOR']) { echo "<mark>" .$mesa['IDENTIFICADOR']. "</mark>"; } 
			else { echo $mesa['IDENTIFICADOR']; } ?> </td>
			
			
			
			<?php if($mesa["TIPO"] == "fumadores") {
			 $tipoMesa = "fumadores"; 
		} else {
			$tipoMesa = "no fumadores";
		}?>
			
	
			
			
			<td title="<?php echo 'Mesa para ' .$mesa['MAXPERSONAS']. ' personas ' .$tipoMesa. ' situada en el ' .$mesa['UBICACION']. ' del restaurante';?>"> 
				<?php if (isset($marcar) && $marcar == $mesa['IDENTIFICADOR']) { echo "<mark>" .$mesa['OID_M']. "</mark>"; } else {
				echo $mesa['OID_M']; } ?> </td>
			<td> <button type="submit" name="eliminarMesa" <?php if(in_array($mesa['IDENTIFICADOR'], $mesasReservadas)) {
				echo "class='botonAccion1 botonNoActivo' disabled title='La mesa no se puede eliminar porque está reservada.'"; 
			} else {echo "class='botonAccion1'"; } ?> > Eliminar mesa </button> </td>
		</tr>
		</form>
	<?php	}
					}
			?>
									
	
</table>
</div>
<?php } else { ?>
        <span class="texto4"> No se ha encontrado ningún elemento. </span>
         <?php }  ?>
        <br>

   	 <nav class="texto2">
   	 	<span> <strong>Página: </strong> </span>
		<div class="paginacion">
			<?php
			for ($i=1; $i <= $total_paginas; $i++) { 
				if ($i == $pagina_seleccionada) { ?>		
				<button type="button" class="actual"> <?php echo " ".$i." "; ?>  </button>
				<?php	
				} else {
					?>
					<form class="boton" action="gestion_ElTejao.php" method="get">
						<input type="hidden" name="pagina_seleccionada" value="<?php echo $i; ?>">
						<input type="hidden" name="pag_tam" value="<?php echo $pag_tam; ?>">
						<button type="submit"> <?php echo $i; ?> </button> 
					</form>
					<?php
				} }
			?>
		</div>
		<br>
		<form method="get" action="gestion_ElTejao.php">
			<input type="hidden" id="pagina_seleccionada" name="pagina_seleccionada" value="<?php echo $pagina_seleccionada ?>">
			Mostrando
			<input type="number" id="pag_tam" name="pag_tam" min="1" size="3" max="<?php echo $total_registros ?>" value="<?php echo $pag_tam ?>" >
			mesas de <?php echo $total_registros ?>
			<button class="botonAccion2" type="submit" id="cambiar" name="cambiar" value="Cambiar"> Cambiar </button>
		</form>
	</nav>
	
	
</div>	


<!--  ----------------------------------------   MENÚ Productos -------------------------------------------------  -->
	<?php  } elseif (isset($_SESSION["menuProductos"])) { ?>
			
        <div class="col-8">
        	
      
       
		<h2 class="texto"> Productos del restaurante: </h2>
		
		  <?php  if (isset($mensaje)) { ?>
		  	<div>
        	<span class="texto4"> <em> <?php echo $mensaje; ?></em> </span>
        	</div>
       <?php } 
       
       if (!empty($arrayProductos)) {
       
       ?>	
 		<br>
       <div class="tabla-responsive">
		<table>
		<tr>
			<th> <strong> Nombre: </strong> </th>
			<th> <strong> Unidad de medida: </strong> </th>
			<th> <strong> Umbral de existencias: </strong> </th>
			<th>  Acciones:  </th>
		</tr>
        
       
        
      <?php  foreach($arrayProductos as $producto) { 
      		if (isset($producto['NOMBRE'])) {
      	
      	?>
					
					
		<form method="post" action="controlador_gestion_ElTejao.php">
			
		<input type="hidden" name = "nombreProducto" id ="nombreProducto" value="<?php echo $producto['NOMBRE'] ?>">
		<tr>
			<td> <?php echo $producto['NOMBRE'] ?> </td>
			<td> <?php echo $producto['UNIDADMEDIDA'] ?> </td>
			<td> <?php echo $producto['UMBRALEXISTENCIAS'] ?> </td>
			<td> <button type="submit" name="eliminarProducto" <?php if (in_array($producto['NOMBRE'], $grupos)) {
				echo " class='botonAccion1 botonNoActivo' disabled title='Este producto no se puede eliminar porque está en el almacén del restaurante.'";
			} else { echo "class='botonAccion1'"; } ?> 
				> Eliminar producto </button> </td>
		</tr>
		</form>
	<?php	}
					}
			?>
									
	
</table>
</div>
<?php } else { ?>
        <span class="texto4"> No se ha encontrado ningún elemento. </span>
         <?php }  ?>
        <br>

   	 <nav class="texto2">
   	 	<span> <strong>Página: </strong> </span>
		<div class="paginacion">
			<?php
			for ($i=1; $i <= $total_paginas; $i++) { 
				if ($i == $pagina_seleccionada) { ?>		
				<button type="button" class="actual"> <?php echo " ".$i." "; ?>  </button>
				<?php	
				} else {
					?>
					<form class="boton" action="gestion_ElTejao.php" method="get">
						<input type="hidden" name="pagina_seleccionada" value="<?php echo $i; ?>">
						<input type="hidden" name="pag_tam" value="<?php echo $pag_tam; ?>">
						<button type="submit"> <?php echo $i; ?> </button> 
					</form>
					<?php
				} }
			?>
		</div>
		<br>
		<form method="get" action="gestion_ElTejao.php">
			<input type="hidden" id="pagina_seleccionada" name="pagina_seleccionada" value="<?php echo $pagina_seleccionada ?>">
			Mostrando
			<input type="number" id="pag_tam" name="pag_tam" min="1" size="3" max="<?php echo $total_registros ?>" value="<?php echo $pag_tam ?>">
			productos de <?php echo $total_registros ?>
			<button class="botonAccion2" type="submit" id="cambiar" name="cambiar" value="Cambiar"> Cambiar </button>
		</form>
	</nav>









<?php
if (isset($_REQUEST["anadirProducto"]) || isset($erroresProducto)) { ?>

<div class = "texto3">
<form method="post" name ="introducirNuevoProducto" action="controlador_gestion_ElTejao.php">
	<div>
		
	<label for="nombreNuevoProducto"> Nombre del producto <strong> * </strong> </label>
<input type="text" name="nombreNuevoProducto" id="nombreNuevoProducto" maxlength="30"  size ="30" title="Introduzca aquí el nombre del nuevo producto" required
<?php 
if (isset($datosProducto["nombreNuevoProducto"])) {
	echo "value='" .$datosProducto["nombreNuevoProducto"]. "'";
}

?>
>
</div>

<div>
					<label for="unidadMedida"> Unidad de medida: <strong> * </strong> </label>
					<select name="unidadMedida" id="unidadMedida" required>
					<option value="gramos"<?php if (isset($datosProducto['unidadMedida']) && $datosProducto['unidadMedida'] == "gramos") {
						echo "selected"; } ?>
					> gramos </option> 
					<option value="kilogramos" <?php if (isset($datosProducto['unidadMedida']) && $datosProducto['unidadMedida'] == "kilogramos") {
						echo "selected"; } ?> > kilogramos </option>
					<option value="botes" <?php if (isset($datosProducto['unidadMedida']) && $datosProducto['unidadMedida'] == "botes") {
						echo "selected"; } ?>> botes </option>
					<option value="botellas" <?php if (isset($datosProducto['unidadMedida']) && $datosProducto['unidadMedida'] == "botellas") {
						echo "selected"; } ?>> botellas </option>
					<option value="paquetes" <?php if (isset($datosProducto['unidadMedida']) && $datosProducto['unidadMedida'] == "paquetes") {
						echo "selected"; } ?>> paquetes </option>
					<option value="latas"<?php if (isset($datosProducto['unidadMedida']) && $datosProducto['unidadMedida'] == "kilogramos") {
						echo "selected"; } ?>> latas </option>
					<option value="unidades"<?php if (isset($datosProducto['unidadMedida']) && $datosProducto['unidadMedida'] == "unidades") {
						echo "selected"; } ?>> unidades </option>
					<option value="bolsas"<?php if (isset($datosProducto['unidadMedida']) && $datosProducto['unidadMedida'] == "bolsas") {
						echo "selected"; } ?>> bolsas </option>
					<option value="litros"<?php if (isset($datosProducto['unidadMedida']) && $datosProducto['unidadMedida'] == "litros") {
						echo "selected"; } ?>> litros </option>	
				</select> 

</div>

<div>
<label for="umbral"> Umbral de existencias <strong> * </strong> </label>
<input type="number" name="umbral" id="umbral"  min="0" size ="3"  title="Establezca el umbral de existencias" required 
<?php 
if (isset($datosProducto["umbral"])) {
	echo "value='" .$datosProducto["umbral"]. "'";
}

?>
>
</div>

<button type="submit" class="botonAccion5" name="introducirProducto" > Añadir el producto </button>
</form>
</div>

<?php  } else { ?>


<form method="post" name = "nuevoProducto" action="gestion_ElTejao.php">
<button class="botonAccion3" type="submit" name ="anadirProducto"> Añadir nuevo producto</button>
</form>
<?php } ?>



</div>	


<?php  } ?>




</div>

</main>
		
	</body>

</html>


<?php } else {
	Header("Location: index.php"); 
}


?>


