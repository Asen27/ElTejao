<?php 
	session_start();
	
	require_once ("gestionBD.php");
	require_once ("gestionarReservas.php");

	
	
	if (isset($_SESSION["usuario"]) && ($_SESSION["usuario"]["puesto"] == "RESERVAS" || $_SESSION["usuario"]["puesto"] == "GERENTE")) {
		$usuario = $_SESSION["usuario"];
		
		// CARGA DE DATOS:
		
		$conexion = crearConexionBD();
		
		if (isset($_SESSION["menuClientes"])) {
		$arrayClientes = array();
		$clientes = consultarClientes($conexion);	
		foreach ($clientes as $cliente) {
			array_push($arrayClientes, $cliente);
		   }
		
		
			$peticiones = consultarPeticionesReserva($conexion);
			$clientesPeticiones = array();
			foreach ($peticiones as $comprobarPeticion) { 
				array_push($clientesPeticiones, $comprobarPeticion['OID_C']);
	}
		
			$reservas = consultarReservas($conexion);
			$clientesReservas = array();
			foreach ($reservas as $comprobarReserva) { 
			array_push($clientesReservas, $comprobarReserva['OID_C']);
			
	}
		
		if (isset($_SESSION["erroresCliente"])) {
		$erroresCliente = $_SESSION["erroresCliente"];
		unset($_SESSION["erroresCliente"]);
	
		$datosCliente= $_SESSION["formularioCliente"];
		unset($_SESSION["formularioCliente"]);
		
		}
		
		
		if (isset($_SESSION["erroresPeticion"])) {
		$erroresPeticion = $_SESSION["erroresPeticion"];
		unset($_SESSION["erroresPeticion"]);
	
		$datosPeticion= $_SESSION["formularioPeticion"];
		unset($_SESSION["formularioPeticion"]);
		
		
		
		}
		
		}
		
		elseif (isset($_SESSION["menuPeticionesReserva"])) {
			$arrayPeticionesReserva = array();
	    $peticionesReserva = consultarPeticionesReserva($conexion);
		foreach ($peticionesReserva as $peticion) {
			array_push($arrayPeticionesReserva, $peticion);
		   }
		
		    $reservasRealizadas = consultarReservas($conexion);
			$idClientes = array();
			$fechasYHoras = array();
			foreach ($reservasRealizadas as $comprobarReserva) { 
				array_push($idClientes, $comprobarReserva['OID_C']);
				array_push($fechasYHoras, $comprobarReserva['FECHAYHORA']);
	}
			
			$facturasExistentes = consultarFacturas($conexion);
			$codigos = array();
			foreach ($facturasExistentes as $comprobarFactura) { 
				array_push($codigos, $comprobarFactura['CODIGOFACTURA']);
	}
			
			
			
		    $pedidosRealizados = consultarPedidos($conexion);
			$idPR = array();
			foreach ($pedidosRealizados as $comprobarPedido) { 
				array_push($idPR, $comprobarPedido['OID_PR']);
	}
			
		
			
			
		}
        
		elseif (isset($_SESSION["menuReservas"])) {
			
			if (isset($_SESSION["factura"])) {
				$factura = "factura";
				unset($_SESSION["factura"]);
			}
			
			
			$arrayReservas = array();
			$reservas = consultarReservas($conexion);
			foreach ($reservas as $reserva) {
			array_push($arrayReservas, $reserva);
		   }
			
			
			
			
	
			
			
		}
		

		
		elseif (isset($_SESSION["menuPedidos"])) {
			
			if (isset($_SESSION["pedido"])) {
			$imprimirPedido = "pedido";
			unset($_SESSION["pedido"]);
			}
			
			
			
			
			$arrayPedidos = array();
			$pedidos = consultarPedidos($conexion);
			foreach ($pedidos as $pedido) {
				array_push($arrayPedidos, $pedido);
			}
			
		
			
			$platos = consultarPlatos($conexion);
			$nombrePlatos = array();
			$seOfreceMediaRacion= array();
			foreach ($platos as $plato) {
			array_push($nombrePlatos, $plato['NOMBRE']);
				
			if(isset($plato['PRECIOMEDIARACION'])) {
				$mediaRacion = 1; 
			} else {
				 $mediaRacion = 0; 
			} 
		    array_push($seOfreceMediaRacion, $mediaRacion);
			}
			
			
			$lineas = consultarLineasDePedido($conexion);
			$arrayLineas = array();
			foreach ($lineas as $linea) {
			array_push($arrayLineas, $linea);
			}
			
			if (isset($_SESSION["erroresPedido"])) {
			$erroresPedido = $_SESSION["erroresPedido"];
			unset($_SESSION["erroresPedido"]);
	
			$datosPedido= $_SESSION["formularioPedido"];
			unset($_SESSION["formularioPedido"]);
		
		}
			
			
		}
		
		
		
		
		
		if (isset($_SESSION["subMenuMesasDisponibles"])) {
			$peticionReserva = $_SESSION["subMenuMesasDisponibles"];
			unset($_SESSION["subMenuMesasDisponibles"]);
			$arrayMesasDisponibles = array();
			$mesasDisponibles = consultarMesasDisponibles($conexion);
			foreach ($mesasDisponibles as $mesa) {
			array_push($arrayMesasDisponibles, $mesa);
			}
			
			terminarConsulta($conexion);
		}
		
		
	
		
		
		cerrarConexionBD($conexion);
	
	
	
	
	
	
	
		if (isset($_SESSION["clienteReserva"])) {
			$clienteReserva = $_SESSION["clienteReserva"];   
			unset($_SESSION["clienteReserva"]);
		}
	
		if (isset($_SESSION["mensaje"])) {
			$mensaje = $_SESSION["mensaje"];   
			unset($_SESSION["mensaje"]);
		}
	
	
		if (isset($_SESSION["marcar"])) {
			$marcar = $_SESSION["marcar"];   
			unset($_SESSION["marcar"]);
		}
	
		
		if (isset($_SESSION["pedidoPlato"])) {
			$pedidoPlato = $_SESSION["pedidoPlato"];   
			unset($_SESSION["pedidoPlato"]);
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
		
		
		<script type="text/javascript">
  			
     function controlarRaciones(selectElement) {
    	var selectedOption = selectElement.selectedIndex;
 
   var filter = <?php echo json_encode($seOfreceMediaRacion); ?>;
    document.getElementById("media").disabled = filter[selectedOption] == 0;  
   
 
   }
  
   
  
  

  
  
  
 </script>
		
		
		
		<title> Gestión de reservas </title>
		

		</head>

		<body>
<?php 
include_once("cabecera.php");
?>

<?php if (isset($factura)) { ?>
<a id="link" target="_blank" href="imprimirFactura.php"></a>

<script type="text/javascript">
    document.getElementById('link').click();
</script>

<?php } elseif (isset($imprimirPedido)) {?>

<a id="link2" target="_blank" href="imprimirPedido.php"></a>

<script type="text/javascript">
    document.getElementById('link2').click();
</script>

<?php } ?>

<div class="row" class="col-12">
			<nav>
				<ul class="nav">
					<form method="post" action="controlador_gestion_reservas.php" >
				
						<li> 
							<button <?php if (isset($_SESSION["menuClientes"])) { echo "class='activo' "; }?>
							type="submit" id="clientes" name="clientes" > Clientes </button>
						</li>
						<li>	<button <?php if (isset($_SESSION["menuPeticionesReserva"])) { echo "class='activo' "; }?>
							type="submit" id="peticionesReserva" name="peticionesReserva" > Peticiones de reserva </button>
						</li>
						<li>	<button <?php if (isset($_SESSION["menuReservas"])) { echo "class='activo' "; }?>
							type="submit" id="reservas" name="reservas" > Reservas </button>
						</li>	
						<li>	<button <?php if (isset($_SESSION["menuPedidos"])) { echo "class='activo' "; }?> 
							type="submit" id="pedidos" name="pedidos"> Pedidos </button>
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

			if (isset($erroresCliente)) {
				foreach ($erroresCliente as $error) {
					print("<div class='col-12 texto4 error'>");
					print("$error");
					print("</div>");
				}
			} elseif (isset($erroresPeticion)) {
				foreach ($erroresPeticion as $error) {
					print("<div class='col-12 texto4 error'>");
					print("$error");
					print("</div>");
				}
				

			}elseif (isset($erroresPedido)) {
				foreach ($erroresPedido as $error) {
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





<?php   if (isset($_SESSION["menuClientes"])) { ?>
			
        <div class="col-8">
        	
      
       
		<h2 class="texto"> Clientes del restaurante: </h2>
		
		  <?php if (isset($mensaje)) { ?>
		  	<div>
        	<span class="texto4"> <em> <?php echo $mensaje; ?></em> </span>
        	</div>
        	<br>
       <?php } 
       
       if (!empty($arrayClientes)) {
       
       ?>
       <br>	
       <div class="tabla-responsive">
		<table>
		<tr>
			<th> <strong> Nombre: </strong> </th>
			<th> <strong> Apellidos: </strong> </th>
			<th> <strong> Teléfono: </strong> </th>
			<th>  Acciones:  </th>
		</tr>
        
       
        
      <?php  foreach($arrayClientes as $cliente) { 
      		if (isset($cliente['OID_C'])) {
      	
      	?>
      	
        <form method="post" name="introducirNuevaPeticionReserva" action="controlador_gestion_reservas.php">
			
		<input type="hidden" name = "oidCliente" id = "oidCliente" value="<?php echo $cliente['OID_C'] ?>">
		<tr>
			<td> <?php echo $cliente['NOMBRE'] ?> </td>
			<td> <?php echo $cliente['APELLIDOS'] ?> </td>
			<td> <?php echo $cliente['TELEFONO'] ?> </td>
			<?php if(isset($clienteReserva) && $cliente['OID_C'] == $clienteReserva) {  ?>
				
				<td> -- </td>
				<?php } else { ?>
				
			<td> <button class="botonAccion3" type="submit" name="peticionReserva"> Añadir petición de reserva </button>
				 <button type="submit" name="eliminarCliente" <?php if (in_array($cliente['OID_C'], $clientesReservas)) {
				 	echo " class='botonAccion1 botonNoActivo' disabled title = 'Este cliente no se puede eliminar porque tiene asignada una reserva.'"; } elseif 
				 	(in_array($cliente['OID_C'], $clientesPeticiones)) { echo " class='botonAccion1 botonNoActivo' disabled title=' Este cliente no se puede eliminar porque ha hecho una petición de reserva.'";
					} else { echo "class='botonAccion1'";}?>
				 
				 > Eliminar cliente </button> </td>
				 
				 
	<?php } ?>
		</tr>
		
		<?php if((isset($clienteReserva) && $cliente['OID_C'] == $clienteReserva) || (isset($erroresPeticion) && $cliente['OID_C'] == $datosPeticion['oidCliente'])) {  ?>
		<tr>
			<td colspan="4">
				<div>
					<label for="fechaReserva">Fecha de la reserva: <strong> * </strong> </label>
					<input type="date" id="fechaReserva" name="fechaReserva" placeholder="yyyy-mm-dd" title="Introduzca la fecha de la reserva. La fecha debe seguir el patrón yyyy-mm-dd."
					<?php if (isset($datosPeticion["fechaReserva"])) {
						echo "value='".$datosPeticion["fechaReserva"]."'";
					}
					?>
					>
				</div>

				<div>
					<label for="hora">Hora de la reserva: <strong> * </strong> </label>
					<select name="hora" id="hora" required>
					<option value="08:" <?php if (isset($datosPeticion['hora']) && $datosPeticion['hora'] == "08") {
						echo "selected"; } ?>
					> 08</option>
					<option value="09:" <?php if (isset($datosPeticion['hora']) && $datosPeticion['hora'] == "09") {
						echo "selected"; } ?>
					> 09</option>
					<option value="10:"<?php if (isset($datosPeticion['hora']) && $datosPeticion['hora'] == "10") {
						echo "selected"; } ?>
					> 10</option>
					<option value="11:"<?php if (isset($datosPeticion['hora']) && $datosPeticion['hora'] == "11") {
						echo "selected"; } ?>
					> 11</option>
					<option value="12:"<?php if (isset($datosPeticion['hora']) && $datosPeticion['hora'] == "12") {
						echo "selected"; } ?>
					> 12</option>
					<option value="13:"<?php if (isset($datosPeticion['hora']) && $datosPeticion['hora'] == "13") {
						echo "selected"; } ?>
					> 13</option>
					<option value="14:"<?php if (isset($datosPeticion['hora']) && $datosPeticion['hora'] == "14") {
						echo "selected"; } ?>
					> 14</option>
					<option value="15:"<?php if (isset($datosPeticion['hora']) && $datosPeticion['hora'] == "15") {
						echo "selected"; } ?>
					> 15</option>
					<option value="16:"<?php if (isset($datosPeticion['hora']) && $datosPeticion['hora'] == "16") {
						echo "selected"; } ?>
					> 16</option>
					<option value="17:"<?php if (isset($datosPeticion['hora']) && $datosPeticion['hora'] == "17") {
						echo "selected"; } ?>
					> 17</option>
					<option value="18:"<?php if (isset($datosPeticion['hora']) && $datosPeticion['hora'] == "18") {
						echo "selected"; } ?>
					> 18</option>
					<option value="19:"<?php if (isset($datosPeticion['hora']) && $datosPeticion['hora'] == "19") {
						echo "selected"; } ?>
					> 19</option>
					<option value="20:"<?php if (isset($datosPeticion['hora']) && $datosPeticion['hora'] == "20") {
						echo "selected"; } ?>
					> 20</option>
					<option value="21:"<?php if (isset($datosPeticion['hora']) && $datosPeticion['hora'] == "21") {
						echo "selected"; } ?>
					> 21</option>
					<option value="22:"<?php if (isset($datosPeticion['hora']) && $datosPeticion['hora'] == "22") {
						echo "selected"; } ?>
					> 22</option>	
				</select> : 
					<select name="minutos" id="minutos" required>
					<option value="00" <?php if (isset($datosPeticion['minutos']) && $datosPeticion['minutos'] == "00") {
						echo "selected"; } ?>
					> 00</option>
					<option value="05"<?php if (isset($datosPeticion['minutos']) && $datosPeticion['minutos'] == "05") {
						echo "selected"; } ?>
					> 05</option>
					<option value="10"<?php if (isset($datosPeticion['minutos']) && $datosPeticion['minutos'] == "10") {
						echo "selected"; } ?>
					> 10</option>
					<option value="15"<?php if (isset($datosPeticion['minutos']) && $datosPeticion['minutos'] == "15") {
						echo "selected"; } ?>
					> 15</option>
					<option value="20"<?php if (isset($datosPeticion['minutos']) && $datosPeticion['minutos'] == "20") {
						echo "selected"; } ?>
					> 20</option>
					<option value="25"<?php if (isset($datosPeticion['minutos']) && $datosPeticion['minutos'] == "25") {
						echo "selected"; } ?>
					> 25</option>
					<option value="30"<?php if (isset($datosPeticion['minutos']) && $datosPeticion['minutos'] == "30") {
						echo "selected"; } ?>
					> 30</option>
					<option value="35"<?php if (isset($datosPeticion['minutos']) && $datosPeticion['minutos'] == "35") {
						echo "selected"; } ?>
					> 35</option>
					<option value="40"<?php if (isset($datosPeticion['minutos']) && $datosPeticion['minutos'] == "40") {
						echo "selected"; } ?>
					> 40</option>
					<option value="45"<?php if (isset($datosPeticion['minutos']) && $datosPeticion['minutos'] == "45") {
						echo "selected"; } ?>
					> 45</option>
					<option value="50"<?php if (isset($datosPeticion['minutos']) && $datosPeticion['minutos'] == "50") {
						echo "selected"; } ?>
					> 50</option>
					<option value="55"<?php if (isset($datosPeticion['minutos']) && $datosPeticion['minutos'] == "55") {
						echo "selected"; } ?>
					> 55</option>		
				</select> 
				</div>
				
				<div>
			    <label for="numPersonas"> Número de personas: <strong> * </strong> </label> 
				<input type="number" name="numPersonas" id="numPersonas" min="1" required
				<?php if (isset($datosPeticion["numPersonas"])) {
						echo "value='".$datosPeticion["numPersonas"]."'";
					}
					?>
				>
				</div>
				
		         <div>
		        <label for="ubicacion"> Ubicación de la mesa: <strong> * </strong> </label> 
				<select name="ubicacion" id="ubicacion">
					<option value="indiferente" <?php if (isset($datosPeticion['ubicacion']) && $datosPeticion['ubicacion'] == "indiferente") {
						echo "selected"; } ?>
					> Indiferente</option>
					<option value="interior"<?php if (isset($datosPeticion['ubicacion']) && $datosPeticion['ubicacion'] == "interior") {
						echo "selected"; } ?>
					> Interior</option>
					<option value="exterior"<?php if (isset($datosPeticion['ubicacion']) && $datosPeticion['ubicacion'] == "exterior") {
						echo "selected"; } ?>
					> Exterior</option>	
				</select>
				</div>
				
				<div>
			    <label for="tipoMesa"> Tipo de la mesa: <strong> * </strong> </label> 
				<select name="tipoMesa" id="tipoMesa">
					<option value="indiferente"<?php if (isset($datosPeticion['tipoMesa']) && $datosPeticion['tipoMesa'] == "indiferente") {
						echo "selected"; } ?>
					> Indiferente</option>
					<option value="fumadores"<?php if (isset($datosPeticion['tipoMesa']) && $datosPeticion['tipoMesa'] == "fumadores") {
						echo "selected"; } ?>
					> Para fumadores</option>
					<option value="noFumadores"<?php if (isset($datosPeticion['tipoMesa']) && $datosPeticion['tipoMesa'] == "noFumadores") {
						echo "selected"; } ?>
					> Para no fumadores</option>	
				</select>
				</div>
			<div><button class="botonAccion3" type="submit" name="anadirPeticion"> Añadir </button> </div>	
				</td>
		</tr>
		<?php }?>
		</form>
        
        
        
   
      <?php  }
         } ?>
        </table>
        </div>
        
        <?php } else { ?>
        <span class="texto4"> No se ha encontrado ningún elemento. </span>
        <br>
        <br>
         <?php }  ?>
        <br>
        
        <?php if(isset($_REQUEST["anadirCliente"]) || isset($erroresCliente)) { ?>
        
        <div class="texto5">
<form method="post" name = "introducirNuevoCliente" action="controlador_gestion_reservas.php">
	
	<div>
	<label for="nombreNuevoCliente"> Nombre <strong> * </strong> </label>
<input type="text" name="nombreNuevoCliente" id="nombreNuevoCliente" size ="30" maxlength="30" title="Introduzca aquí el nombre del nuevo cliente" placeholder="ej: Álvaro" required
<?php 
if (isset($datosCliente["nombreNuevoCliente"])) {
	echo "value='" .$datosCliente["nombreNuevoCliente"]. "'";
}
?>
>
</div>

<div>
<label for="apellidosNuevoCliente"> Apellidos <strong> * </strong> </label>
<input type="text" name="apellidosNuevoCliente" id="apellidosNuevoCliente" size ="50" maxlength="50" title="Introduzca aquí los apellidos del nuevo cliente" required placeholder="ej: López Postigo"
<?php 
if (isset($datosCliente["apellidosNuevoCliente"])) {
	echo "value='" .$datosCliente["apellidosNuevoCliente"]. "'";
}
?>

>
</div>

<div>
<label for="telefono"> Teléfono <strong> * </strong> </label>
<input type="tel" name="telefonoNuevoCliente" id="telefono" size ="12" maxlength="9" title="Introduzca aquí el teléfono del nuevo cliente" pattern="^[9|8|7|6]\d{8}$" placeholder="ej: 640209702" required
<?php 
if (isset($datosCliente["telefonoNuevoCliente"])) {
	echo "value='" .$datosCliente["telefonoNuevoCliente"]. "'";
}
?>

>
</div>

<button class="botonAccion3" type="submit" name="introducirCliente" > Añadir el cliente</button>
</form>
</div>

        
        
        
        
        <?php } else { ?>
        	<div class="texto7">
        <form method="post" name = "nuevoCliente" action="gestion_reservas.php">
		<button  class= "botonAccion5" type="submit" name ="anadirCliente"> Añadir nuevo cliente</button>
		</form>
		</div>
        <?php }  ?>
        
        </div>
        
        
        
<?php }  elseif (isset($_SESSION["menuPeticionesReserva"])) { ?>
			
        <div class="col-8">
		<h2 class="texto"> Peticiones de reserva: </h2>
	
	<?php if (isset($mensaje)) { ?>
		<div>
        	<span class="texto4"> <em> <?php echo $mensaje; ?></em> </span>
        	</div>
        	<br>
       <?php } 
       
       
       if (!empty($arrayPeticionesReserva)) {
       
       ?>	
       
       <br>
	<div class="tabla-responsive">
		<table>
		<tr>
			<th> <strong> Número identificador: </strong> </th>
			<th> <strong> Nombre del cliente: </strong> </th>
			<th> <strong> Apellidos del cliente: </strong> </th>
			<th> <strong> Fecha y hora de la reserva: </strong> </th>
			<th> <strong> Número de personas: </strong> </th>
			<th> <strong> Ubicación de la mesa: </strong> </th>
			<th> <strong> Tipo de la mesa: </strong> </th>
			<th>  Acciones:  </th>
		</tr>
        
       
        
      <?php  
      foreach($arrayPeticionesReserva as $peticion) { 
      		if (isset($peticion['OID_PR'])) { ?>
					
      <form method="post" action="controlador_gestion_reservas.php">
			
		<input type="hidden" name = "oidPeticion" id = "oidPeticion" value="<?php echo $peticion['OID_PR'] ?>">
		<tr>
			<td> <?php if (isset($marcar) && $marcar == $peticion['OID_PR']) { 
			
			echo "<mark>" .$peticion['OID_PR']. "</mark>"; 
			} else { echo $peticion['OID_PR']; } ?> </td>
			
			<td> <?php if (isset($marcar) && $marcar == $peticion['OID_PR']) { 
			
			echo "<mark>" .$peticion['NOMBRE']. "</mark>"; 
			} else { echo $peticion['NOMBRE']; } ?> </td>
			
			
			<td> <?php if (isset($marcar) && $marcar == $peticion['OID_PR']) { 
			
			echo "<mark>" .$peticion['APELLIDOS']. "</mark>"; 
			} else { echo $peticion['APELLIDOS']; } ?> </td>
			
			
			<td> <?php if (isset($marcar) && $marcar == $peticion['OID_PR']) { 
			
			echo "<mark>" .substr($peticion['FECHAYHORARESERVA'], 0, 14). "</mark>";  
			} else { echo substr($peticion['FECHAYHORARESERVA'], 0, 14); } ?> </td>
			
			
			
			
			<td> <?php if (isset($marcar) && $marcar == $peticion['OID_PR']) { 
			
			echo "<mark>" .$peticion['NUMPERSONAS']. "</mark>"; 
			} else { echo $peticion['NUMPERSONAS']; } ?> </td>
			
			
			<td> <?php if(isset($peticion['UBICACIONMESA'])) {
				if (isset($marcar) && $marcar == $peticion['OID_PR']) { 
			
			echo "<mark>" .$peticion['UBICACIONMESA']. "</mark>";
				} else { echo $peticion['UBICACIONMESA']; } 
			} else {
				if (isset($marcar) && $marcar == $peticion['OID_PR']) { 
			
			echo "<mark> indiferente </mark>";
				} else {
				echo "indiferente";	
				}
				 } ?> </td>
				 
				 
				<td> <?php if(isset($peticion['TIPOMESA'])) {
				if (isset($marcar) && $marcar == $peticion['OID_PR']) { 
			
			echo "<mark>" .$peticion['TIPOMESA']. "</mark>";
				} else { echo $peticion['TIPOMESA']; } 
			} else {
				if (isset($marcar) && $marcar == $peticion['OID_PR']) { 
			
			echo "<mark> indiferente </mark>";
				} else {
				echo "indiferente";	
				}
				 } ?> </td>
				 
	       <td> 
	       		
	       <button type="submit" name="encontrarMesa" <?php if(in_array($peticion['OID_C'], $idClientes) && in_array($peticion['FECHAYHORARESERVA'], $fechasYHoras) && in_array($peticion['OID_PR'], $codigos)) {
		   	     	echo " class='botonAccion3 botonNoActivo' disabled title='Ya se ha reservado mesa para esta petición.'"; } 
		   	     	else {
		   	     		echo "class='botonAccion3'";
		   	     	}?>  > Encontrar mesa </button> 
	       
	       
	       			
	   			 <button type="submit" name="incluirPedido" <?php if(in_array($peticion['OID_PR'], $idPR)) { echo " class='botonAccion5 botonNoActivo' disabled title='Ya se ha incluido pedido'"; } 
                 elseif(!(in_array($peticion['OID_C'], $idClientes) && in_array($peticion['FECHAYHORARESERVA'], $fechasYHoras) && in_array($peticion['OID_PR'], $codigos)))   { echo " class='botonAccion5 botonNoActivo' disabled title='Todavía no se ha reservado mesa.'";    } 
				 else { echo "class='botonAccion5'";}	 ?>  > Incluir Pedido </button>
	
	
	
				 <button  class="botonAccion1"  type="submit"  name="eliminarPeticion" <?php if (in_array($peticion['OID_PR'], $idPR)) {
				 	 echo "onclick='return aviso()'"; }  ?> > Eliminar petición </button> 
				 
				 
	
				 
				 
				 </td>
      		
      		
      	</tr>
      	</form>
     
      <?php	 } }?>





</table>
</div>

<br>

<?php if (isset($peticionReserva)) { ?>
<hr>


<h2 class="texto"> Mesas disponibles para la petición <strong> <?php echo $peticionReserva; ?> </strong> </h2>
<br>
<div class="texto2">
	
	<p> Lista de las mesas que cumplen los requisitos del cliente (se recomienda acomodar al cliente en la primera mesa disponible que aparezca en la lista: </p>
	<br>
	
	
	
	<?php  
	
	if (!empty($arrayMesasDisponibles)) { ?>
	<ul class="mesasDisponibles">
  <?php    foreach($arrayMesasDisponibles as $mesa) { 
      		if (isset($mesa['IDENTIFICADORMESA'])) { 
      			if (isset($mesa['MAXPERSONASMESA'])) { ?>
					
      <form method="post" action="controlador_gestion_reservas.php">
			
		<input type="hidden" name = "idMesa" id = "idMesa" value="<?php echo $mesa['IDENTIFICADORMESA'] ?>">
		<input type="hidden" name = "peticionReservaID" id = "peticionReservaID" value="<?php echo $peticionReserva ?>">
	<li class="afirmativo">
		
	<span> El cliente puede acomodarse en la mesa <?php echo $mesa['IDENTIFICADORMESA']; ?> que tiene capacidad <?php echo $mesa["MAXPERSONASMESA"]?> </span>
	<button class = "botonAccion3" type="submit" name="reservarMesa"> Reservar mesa </button>
	</li>
	</form>
	
	
	<?php } else {?>
	  
	  <li class="negativo">
		<form method="post" action="controlador_gestion_reservas.php">
			
		<input type="hidden" name = "idMesa" id = "idMesa" value="<?php echo $mesa['IDENTIFICADORMESA'] ?>">
		
	<span> La mesa <?php echo $mesa['IDENTIFICADORMESA']; ?> cumple los requisitos, pero no está disponible para esta fecha y hora. </span>
	<span> Pida al cliente que cambie la fecha o la hora. 
	</li>
	  </form>
	  
	  
	  
	<?php }  }  } ?> 
	</ul>
 <?php } else { ?>
		<span> No hay mesas en el restaurante que cumplan los requisitos. El problema no está en que las mesas ya estén reservadas, sino en que no existen tales mesas.</span>
	<?php } ?>
</div>






<?php } ?>

<?php } else { ?>
	 <span class="texto4"> No se ha encontrado ningún elemento. </span>
	<?php  }; ?>




</div>

<?php }  elseif (isset($_SESSION["menuReservas"])) { ?>
			
        <div class="col-8">
		<h2 class="texto"> Reservas: </h2>
	
	<?php if (!empty($arrayReservas)) { ?>
	
	
	<div class="tabla-responsive">
		<table>
		<tr>
			<th> <strong> Código: </strong> </th>
			<th> <strong> Nombre del cliente: </strong> </th>
			<th> <strong> Apellidos del cliente: </strong> </th>
			<th> <strong> Fecha y hora de la reserva: </strong> </th>
			<th> <strong> Mesa: </strong> </th>
			<th>  Acciones:  </th>
		</tr>

<?php  
      foreach($arrayReservas as $reserva) { 
      		if (isset($reserva['CODIGO'])) { ?>
					
      <form method="post" action="controlador_gestion_reservas.php">
			
		<input type="hidden" name = "codigoReserva" id = "codigoReserva" value="<?php echo $reserva['CODIGO'] ?>">
		<input type="hidden" name = "codigoFactura" id = "codigoFactura" value="<?php echo $reserva['CODIGOFACTURA'] ?>">
		<tr> 
		<td> <?php  if(isset($marcar) && $marcar == $reserva['CODIGO']) { echo "<mark>".$reserva["CODIGO"]. "</mark>"; 
			} else { echo $reserva["CODIGO"]; }?> </td>
        <td> <?php  if(isset($marcar) && $marcar == $reserva['CODIGO']) { echo "<mark>".$reserva["NOMBRE"]. "</mark>"; 
			} else { echo $reserva["NOMBRE"]; }?> </td>
        <td> <?php  if(isset($marcar) && $marcar == $reserva['CODIGO']) { echo "<mark>".$reserva["APELLIDOS"]. "</mark>"; 
			} else { echo $reserva["APELLIDOS"]; }?> </td>
        <td> <?php if(isset($marcar) && $marcar == $reserva['CODIGO']) { echo "<mark>" .substr($reserva['FECHAYHORA'], 0, 14). "</mark>";
		} else { echo substr($reserva["FECHAYHORA"], 0, 14); }?> </td>
		<?php if($reserva["TIPO"] == "fumadores") {
			 $tipoMesa = "fumadores"; 
		} else {
			$tipoMesa = "no fumadores";
		}?>
		
		<td title="<?php echo 'Mesa para ' .$reserva['MAXPERSONAS']. ' personas ' .$tipoMesa. ' situada en el ' .$reserva['UBICACION']. ' del restaurante';?>" > <?php  if(isset($marcar) && $marcar == $reserva['CODIGO']) { echo "<mark>".$reserva["IDENTIFICADOR"]. "</mark>"; 
			} else { echo $reserva["IDENTIFICADOR"]; }?> </td>
        <td> <button class="botonAccion5" type="submit" name="imprimirFactura"> Imprimir factura </button>
        	 <button class="botonAccion1" type="submit" name="eliminarReserva"> Eliminar reserva </button> </td>
        </tr>
        </form>


    	
    <?php	} } ?>



</table>
</div>

<?php } else {  ?>
 <span class="texto4"> No se ha encontrado ningún elemento. </span>
 <br>
<?php } ?>


<br>





</div>

<?php }  elseif (isset($_SESSION["menuPedidos"])) { ?>
			
       <div class="col-8">
        	

		<h2 class="texto"> Pedidos: </h2>
		
		
		<?php if (isset($mensaje)) { ?>
			<p class="texto4"> <?php echo $mensaje; ?>  </p>
			
			<?php } ?>
		
		
		
		<?php  
		
		if (!empty($arrayPedidos)) { ?>
			
		<div class="texto5">
    <?php  foreach($arrayPedidos as $pedido) { 
      		if (isset($pedido['CODIGOPEDIDO'])) { ?>
		
		 <form method="post" name="introducirNuevaLineaDePedido"  action="controlador_gestion_reservas.php">
		<input type="hidden" name = "oidPedido" id = "oidPedido" value="<?php echo $pedido['CODIGOPEDIDO'] ?>">
		
	<article >
		
	 <div> <strong> Código del pedido: </strong> <?php if (isset($marcar) && $marcar == $pedido['CODIGOPEDIDO']) {
	 echo "<mark>" .$pedido['CODIGOPEDIDO']. "</mark>"; } else {
	 echo $pedido['CODIGOPEDIDO']; } ?> </div> 
	 
	 
	  <div> <strong> Cliente: </strong> <?php if(isset($marcar) && $marcar == $pedido['CODIGOPEDIDO']) {
	  	 echo "<mark>" .$pedido['NOMBRE']. " "  .$pedido['APELLIDOS']. "</mark>";  } else {
	  	 	echo $pedido['NOMBRE']. " " .$pedido['APELLIDOS']; 
	  	 } ?>  </div> 
	  	 
	  	 
	  	 
	  	 <?php $arrayLineasConFiltro = array(); 
			foreach ($arrayLineas as $linea) {
				if ($linea['CODIGOPEDIDO'] == $pedido['CODIGOPEDIDO']) {
					array_push($arrayLineasConFiltro, $linea);
				}
			} ?>
	  	 
	  	 
	  	 
	  	 <div>
	  	 <?php if (!(isset($pedidoPlato) && $pedido['CODIGOPEDIDO'] == $pedidoPlato)) {   ?>
	      	 <button class="botonAccion3"  type="submit" name="pedirPlato"> Pedir plato </button>
	      	<?php } ?>
	      <button class = "botonAccion1" type="submit" name="eliminarPedido"> Eliminar pedido </button>
	      
	      <button 
	      <?php if (empty($arrayLineasConFiltro)) { echo "class='botonAccion5 botonNoActivo' disabled title='El pedido está vacío'"; } else {
	      	 echo "class='botonAccion5'"; } ?> 
	      < type="submit" name="imprimirPedido"> Imprimir pedido </button>
	     
	      </div>

	</article>
		
		
		
		<?php if ((isset($pedidoPlato) && $pedido['CODIGOPEDIDO'] == $pedidoPlato) || (isset($erroresPedido) && $pedido['CODIGOPEDIDO'] == $datosPedido["oidPedido"])) {   ?>
			<div class="texto3">
				
		<div>
					<label for="nombrePlato"> Nombre del plato: <strong> * </strong> </label>
					<select onchange="controlarRaciones(this);" name="nombrePlato" id="nombrePlato" required class='mySelect'>
					<?php foreach ($nombrePlatos as $np) { ?>
						<option id = "<?php echo key($nombrePlatos); ?>"  value="<?php echo $np; ?>" <?php if (isset($datosPedido['nombrePlato']) && $datosPedido['nombrePlato'] == $np) {
						echo "selected"; } ?>
							> <?php echo $np; ?></option>
						<?php } ?>
					</select> 
	
		</div>
		
		<div>
			
			<label for="racionPlato"> Ración: <strong> * </strong> </label>
					<select  name="racionPlato" id="racionPlato" required>
				<option id="media" value="media"> Media </option>
				<option id = "completa" value="completa" selected> Completa </option>
					</select> 
	
		</div>
		
		<div>
			    <label for="unidades"> Unidades: <strong> * </strong> </label> 
				<input type="number" name="unidades" id="unidades" min="1" required
				<?php 
			if (isset($datosPedido["unidades"])) {
				echo "value='" .$datosPedido["unidades"]. "'";
}

?>
				>
				</div>
				<br>
				<div>
				<button class="botonAccion3" type="submit" name="pedir"> Pedir </button>
				</div>
				</div>	
		
		
		
		<?php } ?>
	
	<?php
	/* $arrayLineasConFiltro = array(); 
	foreach ($arrayLineas as $linea) {
		if ($linea['CODIGOPEDIDO'] == $pedido['CODIGOPEDIDO']) {
			array_push($arrayLineasConFiltro, $linea);
		}
	}
	*/
	
	if (!empty($arrayLineasConFiltro)) { ?>
		
       <br>
			<div class="tabla-responsive">
		<table>
			<tr>
			<th> Nombre: </th>
			<th> Ración Pedida: </th>
			<th> Unidades Pedidas: </th>
			<th> Acciones: </th>
			</tr>
			
			<?php foreach ($arrayLineasConFiltro as $linea) { ?>
				<tr></tr>
				<td> <?php echo $linea['NOMBRE']; ?> </td>
				<input type="hidden" name = "nombre" value="<?php echo $linea['NOMBRE']; ?>">
				<td> <?php echo $linea['RACIONPEDIDA']; ?> </td>
				<input type="hidden" name = "racion" value="<?php echo $linea['RACIONPEDIDA']; ?>">
				<td> <?php echo $linea['UNIDADESPEDIDAS']; ?> </td>
			<td> <button class="botonAccion1" type="submit" name="eliminarPlatoPedido"> Eliminar Plato </button> </td>	
				</tr>
				
		<?php	}?> 
			
			
			
			
		</table>
		</div>
		
		<?php  } else { ?>
			<span>  El pedido está vacío.</span>
	<?php	} ?>
		
		</form>
		<hr>
		
		
	
	<?php } } ?>
</div>
 <?php } else { ?>
	<span class="texto4"> No se ha encontrado ningún elemento. </span>
	<?php  } ?>
		
</div>



<?php } ?>		
		
	
		
	</div>	
</main>


		
	</body>

</html>


<?php } else {
	Header("Location: index.php"); 
}


?>



