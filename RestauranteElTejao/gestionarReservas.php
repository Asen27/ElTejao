<?php
     
function consultarClientes($conexion) {
	$consulta = "SELECT * FROM Clientes";
	
	try {
    return $conexion->query($consulta);
	}  catch(PDOException $e) {
		$_SESSION["excepcion"] = $e -> GetMessage();
		Header("Location: excepcion.php");
	}
	
}

function insertarNuevoCliente($conexion, $nombre, $apellidos, $telefono) {
	try {
	$aux = $conexion->query("SELECT OID_C FROM Clientes ORDER BY OID_C DESC");
	
	$fila = $aux->fetch();
	if($fila == FALSE) {
		(int) $oid = 1;
	} else {
	(int) $oid = $fila["OID_C"] + 1;
	} 
	
	}  catch(PDOException $e) {
		$_SESSION["excepcion"] = $e -> GetMessage();
		Header("Location: excepcion.php");
	}
	
	
	$accion=$conexion->prepare('CALL nuevoCliente(' .$oid. ', :nombre, :apellidos, :telefono)');
		$accion->bindParam(':nombre',$nombre);
		$accion->bindParam(':apellidos',$apellidos);
		$accion->bindParam(':telefono',$telefono);
	
	try {
		
		$accion->execute();
		return "correcto";
	}  catch(PDOException $e) {
		$_SESSION["excepcion"] = $e -> GetMessage();
		Header("Location: excepcion.php");
		return FALSE;
	}
}


function eliminarCliente($conexion, $cliente) {
try {
		$accion=$conexion->prepare('DELETE FROM Clientes WHERE OID_C = :cliente');
		$accion->bindParam(':cliente',$cliente);
		$accion->execute();
		return "correcto";
}  catch(PDOException $e) {
		$_SESSION["excepcion"] = $e -> GetMessage();
		Header("Location: excepcion.php");
    }
}


function nuevaPeticionReserva($conexion, $numPersonas, $fechaReserva, $hora, $minutos, $ubicacionMesa, $tipoMesa, $oidCliente) {
	try {
	$aux = $conexion->query("SELECT OID_PR FROM PeticionesReserva ORDER BY OID_PR DESC");
	
	$fila = $aux->fetch();
	if($fila == FALSE) {
		(int) $oid = 1;
	} else {
	(int) $oid = $fila["OID_PR"] + 1;
	} 
	
	}  catch(PDOException $e) {
		$_SESSION["excepcion"] = $e -> GetMessage();
		Header("Location: excepcion.php");
	}
			
		
    $fecha = strtotime($fechaReserva);
	$fechaFormato = date('d/m/Y', $fecha);
	$fechaString = strval($fechaFormato);
	
	$fechaYHora = $hora.$minutos;
	
	
	
	if ($ubicacionMesa == "indiferente") {
		unset($ubicacionMesa);
	}
	
	if ($tipoMesa == "indiferente") {
		unset($tipoMesa);
	}
	
	try {
		$accion=$conexion->prepare("CALL requisitosMesa(" .$oid. ", to_timestamp('" .$fechaString. " " .$fechaYHora. "', 'dd/mm/yyyy hh24:mi'), :numPersonas, :ubicacionMesa, :tipoMesa, :oidCliente)");
		$accion->bindParam(':numPersonas',$numPersonas);
		$accion->bindParam(':ubicacionMesa',$ubicacionMesa);
		$accion->bindParam(':tipoMesa',$tipoMesa);
		$accion->bindParam(':oidCliente',$oidCliente);
		$accion->execute();
		return "Se ha creado nueva petición de reserva. Su número identificador es <form class='boton' method='post' action='controlador_gestion_reservas.php'>
		<input type='hidden' name='oid' value='".$oid."'> <button type='submit' name='control'>" .$oid. "</button> </form>";
	}  catch(PDOException $e) {
		$_SESSION["excepcion"] = $e -> GetMessage();
		Header("Location: excepcion.php");
    }
	
}


function consultarPeticionesReserva($conexion) {
	$consulta = "SELECT OID_PR, OID_C, Nombre, Apellidos, FechaYHoraReserva, NumPersonas, UbicacionMesa, TipoMesa FROM PETICIONESRESERVA NATURAL JOIN Clientes ORDER BY Nombre ASC";
	
	try {
    return $conexion->query($consulta);
	}
	catch(PDOException $e) {
		$_SESSION["excepcion"] = $e->GetMessage();
		Header("Location: excepcion.php");
	}
	
}

function eliminarPeticionReserva($conexion, $peticion) {
	try {
		$accion=$conexion->prepare('DELETE FROM PeticionesReserva WHERE OID_PR = :peticion');
		$accion->bindParam(':peticion',$peticion);
		$accion->execute();
		return "correcto";
}  catch(PDOException $e) {
		$_SESSION["excepcion"] = $e -> GetMessage();
		Header("Location: excepcion.php");
    }
	
	
}


function encontrarMesasDisponibles($conexion, $peticion) {
		try {
		$accion=$conexion->prepare('CALL llamadaAEncontrarMesa(:peticion)');
		$accion->bindParam(':peticion',$peticion);
		$accion->execute();
		return "correcto";
}  catch(PDOException $e) {
		$_SESSION["excepcion"] = $e -> GetMessage();
		Header("Location: excepcion.php");
    }
		
		
	
}

function consultarMesasDisponibles($conexion) {
	$consulta = "SELECT * FROM TablaAuxiliar1";
	
	try {
    return $conexion->query($consulta);
	}
	catch(PDOException $e) {
		$_SESSION["excepcion"] = $e->GetMessage();
		Header("Location: excepcion.php");
	}
	
	
}

function terminarConsulta($conexion) {
	$accion = $conexion->prepare("DELETE FROM TablaAuxiliar1");
	
	try {
    return $accion->execute();
	}
	catch(PDOException $e) {
		$_SESSION["excepcion"] = $e->GetMessage();
		Header("Location: excepcion.php");
	}
	
	
}

function realizarReserva($conexion, $oidPR, $identificadorMesa) {
	try {
	$aux = $conexion->query("SELECT Codigo FROM Reservas ORDER BY CODIGO DESC");
	
	$fila = $aux->fetch();
	if($fila == FALSE) {
		(int) $oid = 100;
	} else {
	(int) $oid = $fila["CODIGO"] + 1;
	} 
	
	}  catch(PDOException $e) {
		$_SESSION["excepcion"] = $e -> GetMessage();
		Header("Location: excepcion.php");
	}
	
	
	
	$accion = $conexion->prepare('CALL reservarMesa(' .$oid. ', :oidPR, :idMesa)');
	try {
		$accion->bindParam(':oidPR',$oidPR);
		$accion->bindParam(':idMesa',$identificadorMesa);
		$accion->execute();
    return "Se ha reservado la mesa " .$identificadorMesa. ". La reserva corresponde a la petición de reserva: " .$oidPR. ". El código de la reserva es:  <form class='boton' method='post' action='controlador_gestion_reservas.php'>
		<input type='hidden' name='codigo' value='".$oid."'> <button type='submit' name='control2'>" .$oid. "</button> </form>";
	}
	catch(PDOException $e) {
		$_SESSION["excepcion"] = $e->GetMessage();
		Header("Location: excepcion.php");
	}
	
	
}

function consultarReservas($conexion) {
	$consulta = "SELECT OID_C, Codigo, Nombre, Apellidos, FechaYHora, Identificador, Tipo, Ubicacion, MaxPersonas, CodigoFactura FROM Reservas NATURAL JOIN Facturas NATURAL JOIN Clientes NATURAL JOIN MesasConcretas NATURAL JOIN Mesas ORDER BY Nombre ASC";
	
	try {
    return $conexion->query($consulta);
	}
	catch(PDOException $e) {
		$_SESSION["excepcion"] = $e->GetMessage();
		Header("Location: excepcion.php");
	}
	
}

function eliminarReserva($conexion, $codigoReserva) {
	try {
		$accion=$conexion->prepare('DELETE FROM Reservas WHERE CODIGO = :codigo');
		$accion->bindParam(':codigo',$codigoReserva);
		$accion->execute();
		return "correcto";
}  catch(PDOException $e) {
		$_SESSION["excepcion"] = $e -> GetMessage();
		Header("Location: excepcion.php");
    }
	
	
}

function incluirNuevoPedido($conexion, $oidPR) {
	try {
	$aux = $conexion->query("SELECT codigoPedido FROM Pedidos ORDER BY codigoPedido DESC");
	
	$fila = $aux->fetch();
	if($fila == FALSE) {
		(int) $oid = 100;
	} else {
	(int) $oid = $fila["CODIGOPEDIDO"] + 1;
	} 
	
	}  catch(PDOException $e) {
		$_SESSION["excepcion"] = $e -> GetMessage();
		Header("Location: excepcion.php");
	}
	
	
	
	$accion = $conexion->prepare('CALL incluirPedido(' .$oid. ', :oidPR)');
	try {
		$accion->bindParam(':oidPR',$oidPR);
		$accion->execute();
    return "Se ha creado un nuevo pedido que corresponde a la petición de reserva " .$oidPR. ". El código del pedido es: <form class='boton' method='post' action='controlador_gestion_reservas.php'>
		<input type='hidden' name='codigoPedido' value='".$oid."'> <button type='submit' name='control3'>" .$oid. "</button> </form>";
	}
	catch(PDOException $e) {
		$_SESSION["excepcion"] = $e->GetMessage();
		Header("Location: excepcion.php");
	}
	
	
}

function consultarPedidos($conexion) {
	$consulta = "SELECT OID_C, codigoPedido, OID_PR, Nombre, Apellidos FROM Pedidos NATURAL JOIN PeticionesReserva NATURAL JOIN Clientes ORDER BY Nombre ASC";
	
	try {
    return $conexion->query($consulta);
	}
	catch(PDOException $e) {
		$_SESSION["excepcion"] = $e->GetMessage();
		Header("Location: excepcion.php");
	}
	
}

function eliminarPedido($conexion, $codigo) {
	
	try {
		$accion=$conexion->prepare('DELETE FROM Pedidos WHERE CODIGOPEDIDO = :codigo');
		$accion->bindParam(':codigo', $codigo);
		$accion->execute();
		return "correcto";
}  catch(PDOException $e) {
		$_SESSION["excepcion"] = $e -> GetMessage();
		Header("Location: excepcion.php");
    }
	
}

function consultarPlatos($conexion) {
	$consulta = "SELECT * FROM Platos ORDER BY Nombre ASC";
	
	try {
    return $conexion->query($consulta);
	}
	catch(PDOException $e) {
		$_SESSION["excepcion"] = $e->GetMessage();
		Header("Location: excepcion.php");
	}
	
}

function insertarNuevaLineaPedido($conexion, $pedido, $nombrePlato, $racionPlato, $unidadesPedidas) {
	
	
	$accion = $conexion->prepare('CALL pedirPlato(:pedido, :nombre, :racion, :unidades)');
	try {
		$accion->bindParam(':pedido',$pedido);
		$accion->bindParam(':nombre',$nombrePlato);
		$accion->bindParam(':racion',$racionPlato);
		$accion->bindParam(':unidades',$unidadesPedidas);
		
		$accion->execute();
       return "El plato se ha añadido correctamente al pedido del cliente";
	}  catch(PDOException $e) {
		$_SESSION["excepcion"] = $e -> GetMessage();
		Header("Location: excepcion.php");
		return FALSE;
	}
	
}


function consultarLineasDePedido($conexion) {
	
	
	$consulta = "SELECT * FROM LineasDePedido ORDER BY NOMBRE ASC";
	
	try {
    return $conexion->query($consulta);
	}
	catch(PDOException $e) {
		$_SESSION["excepcion"] = $e->GetMessage();
		Header("Location: excepcion.php");
	}
	
}


function eliminarPlatoPedido($conexion, $oidPedido, $nombrePlato, $racionPedida) {
		
		try {
		$accion=$conexion->prepare('DELETE FROM LineasDePedido WHERE CODIGOPEDIDO = :codigo AND NOMBRE = :nombre AND RACIONPEDIDA = :racion');
		$accion->bindParam(':codigo', $oidPedido);
		$accion->bindParam(':nombre', $nombrePlato);
		$accion->bindParam(':racion', $racionPedida);
		$accion->execute();
		return "correcto";
}  catch(PDOException $e) {
		$_SESSION["excepcion"] = $e -> GetMessage();
		Header("Location: excepcion.php");
    }
		
		
		
	}

function imprimirFactura($conexion, $codigo) {
	
	
	$accion = $conexion->prepare('CALL imprimirFactura(:codigo)');
	try {
		$accion->bindParam(':codigo',$codigo);
		$accion->execute();
       return "correcto";
	}
	catch(PDOException $e) {
		$_SESSION["excepcion"] = $e->GetMessage();
		Header("Location: excepcion.php");
	}
	
}


function obtenerDatosFactura($conexion) {
	
	
	$consulta = "SELECT * FROM TablaAuxiliar2";
	
	try {
    return $conexion->query($consulta);
	}
	catch(PDOException $e) {
		$_SESSION["excepcion"] = $e->GetMessage();
		Header("Location: excepcion.php");
	}
	
}


function obtenerDatosFactura2($conexion) {
	
	
	$consulta = "SELECT * FROM TablaAuxiliar3";
	
	try {
    return $conexion->query($consulta);
	}
	catch(PDOException $e) {
		$_SESSION["excepcion"] = $e->GetMessage();
		Header("Location: excepcion.php");
	}
	
}


function terminarConsulta2($conexion) {
	$accion = $conexion->prepare("DELETE FROM TablaAuxiliar2");
	
	try {
    return $accion->execute();
	}
	catch(PDOException $e) {
		$_SESSION["excepcion"] = $e->GetMessage();
		Header("Location: excepcion.php");
	}
	
	
}


function terminarConsulta3($conexion) {
	$accion = $conexion->prepare("DELETE FROM TablaAuxiliar3");
	
	try {
    return $accion->execute();
	}
	catch(PDOException $e) {
		$_SESSION["excepcion"] = $e->GetMessage();
		Header("Location: excepcion.php");
	}
	
	
}

function imprimirPedido($conexion, $pedido) {
	
	
	$accion = $conexion->prepare('CALL imprimirPedido(:pedido)');
	try {
		$accion->bindParam(':pedido',$pedido);
		$accion->execute();
       return "correcto";
	}
	catch(PDOException $e) {
		$_SESSION["excepcion"] = $e->GetMessage();
		Header("Location: excepcion.php");
	}
	
}


function obtenerDatosPedido($conexion) {
	
	
	$consulta = "SELECT * FROM TablaAuxiliar4";
	
	try {
    return $conexion->query($consulta);
	}
	catch(PDOException $e) {
		$_SESSION["excepcion"] = $e->GetMessage();
		Header("Location: excepcion.php");
	}
	
}


function obtenerDatosPedido2($conexion) {
	
	
	$consulta = "SELECT * FROM TablaAuxiliar5";
	
	try {
    return $conexion->query($consulta);
	}
	catch(PDOException $e) {
		$_SESSION["excepcion"] = $e->GetMessage();
		Header("Location: excepcion.php");
	}
	
}


function terminarConsulta4($conexion) {
	$accion = $conexion->prepare("DELETE FROM TablaAuxiliar4");
	
	try {
    return $accion->execute();
	}
	catch(PDOException $e) {
		$_SESSION["excepcion"] = $e->GetMessage();
		Header("Location: excepcion.php");
	}
	
	
}


function terminarConsulta5($conexion) {
	$accion = $conexion->prepare("DELETE FROM TablaAuxiliar5");
	
	try {
    return $accion->execute();
	}
	catch(PDOException $e) {
		$_SESSION["excepcion"] = $e->GetMessage();
		Header("Location: excepcion.php");
	}
	
	
}


function consultarFacturas($conexion) {
	
	
	$consulta = "SELECT CODIGOFACTURA FROM FACTURAS";
	
	try {
    return $conexion->query($consulta);
	}
	catch(PDOException $e) {
		$_SESSION["excepcion"] = $e->GetMessage();
		Header("Location: excepcion.php");
	}
	
}


function validacionFormularioIntroducirNuevoCliente($datos){
	
	
	$error = array();
	
	
	// Validación del nombre del cliente
$permitidos = "áéíóúabcdefghijklmnñopqrstuvwxyzÁÉÍÓÚABCDEFGHIJKLMNÑOPQRSTUVWXYZ "; 
$nombre = $datos["nombreNuevoCliente"];


if (empty($nombre)) {
	$error[] = "Tiene que especificar el nombre del cliente.";
}
elseif(strlen($nombre) > 30){
	$error[] = "El nombre del cliente no puede exceder 30 caracteres.";
} else {

for ($i=0; $i<strlen($nombre); $i++){ 
if (strpos($permitidos, substr($nombre,$i,1))===false){ 
$error[] = "El nombre del cliente no es válido.";
	break;
} 
}  
} 

	
	
	
	// Validación de los apellidos 
	
	
	$apellidos = $datos["apellidosNuevoCliente"];


if (empty($apellidos)) {
	$error[] = "Tiene que especificar los apellidos del cliente.";
}
elseif(strlen($apellidos) > 50){
	$error[] = "Los apellidos del cliente no pueden exceder 50 caracteres.";
} else {

for ($i=0; $i<strlen($apellidos); $i++){ 
if (strpos($permitidos, substr($apellidos,$i,1))===false){ 
$error[] = "Los apellidos del cliente no son válidos.";
	break;
} 
}  
} 
	

// validacion del telefono
	$telefono = $datos["telefonoNuevoCliente"];

	if (empty($telefono)) {
		$error[] = "Tiene que especificar el teléfono del cliente."; 
		
	} else if(!preg_match("/^[9|6|7][0-9]{8}$/", $telefono)) {
		$error[] = "El teléfono no es válido.";
	}
	
			
	return $error;
}





function fecha_valida($fecha) {
   $componentes = explode('-', $fecha);
   // He puesto esto porque según dicen los desarrolladores de Google Chrome
   // The input.value always returns as yyyy-mm-dd regardless of the presentation format.
   return checkdate((int) $componentes[1], (int) $componentes[2], (int) $componentes[0]);
}



function validacionFormularioIntroducirNuevaPeticionReserva($datos) {
	
	$error = array();
	
	
	// Validación de la fecha
	$fechaPeticionReserva = $datos["fechaReserva"];
	
	

	if (empty($fechaPeticionReserva) || !fecha_valida($fechaPeticionReserva)) {
		$error[] = "La fecha no es válida.";
	}
	
	
	// Validación del número de personas
	$numPersonas = $datos['numPersonas'];
	
if (strlen($numPersonas) == 0) {
	$error[] = "El numero de personas no puede estar vacío.";
}
elseif(!ctype_digit($numPersonas)){
	$error[] = "El número de personas debe ser un número entero positivo";
} 
elseif(intval($numPersonas) == 0){
	$error[] = "El número de personas debe ser un número entero positivo";
}
	
	
	
	// Validación de la ubicacion		
	$ubicacion = $datos['ubicacion'];

	
	 if (!($ubicacion == "interior" || $ubicacion == "exterior" || $ubicacion == "indiferente")) {
		$error[] = "La ubicación no es válida.";
	}
	
	
	
		// Validación del tipo 
	$tipo = $datos['tipoMesa'];
	
	if (!($tipo == "fumadores" || $tipo == "noFumadores" || $tipo == "indiferente")) {
		$error[] = "El tipo no es válido.";
	}
		
		
	
	// Validación de la hora		
	$hora = $datos['hora'];
	$minutos = $datos['minutos'];
	
	if (empty($hora) || empty($minutos)) {
	$error[] = "Tiene que especificar la hora de la reserva.";
}
	
	
	if (!($hora == "08:" || 
	$hora == "09:" ||
	$hora == "10:" ||
	$hora == "11:" ||
	$hora == "12:" ||
	$hora == "13:" ||
	$hora == "14:" ||
	$hora == "15:" ||
	$hora == "16:" ||
	$hora == "17:" ||
	$hora == "18:" ||
	$hora == "19:" ||
	$hora == "20:" ||
	$hora == "21:" ||
	$hora == "22:")
	&&
	($minutos == "00" || 
	$minutos == "05" ||
	$minutos == "10" ||
	$minutos == "15" ||
	$minutos == "20" ||
	$minutos == "25" ||
	$minutos == "30" ||
	$minutos == "35" ||
	$minutos == "40" ||
	$minutos == "45" ||
	$minutos == "50" ||
	$minutos == "55")
	) {
		$error[] = "La hora no es válida.";
		
	}	
		
		
		
		
	return $error;
	
}


function validacionFormularioIntroducirNuevaLineaDePedido($conexion, $datos) {
	$error = array();
	
	
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
	
	// Validacion del nombre del plato pedido
	$platoPedido = $datos["nombrePlato"];
	
	
	if (empty($platoPedido)) {
		$error[] = "Tiene que especificar el nombre del plato";
	}
	elseif (!in_array($platoPedido, $nombrePlatos)) {
		$error[] = "No existe plato con ese nombre.";
	}
	
	
	// Validacion de la racion pedida
	$racionPedida = $datos["racionPlato"];
	

	
	
	if (!empty($platoPedido) && in_array($platoPedido, $nombrePlatos) && $racionPedida == "media" &&  $seOfreceMediaRacion[array_search($platoPedido, $nombrePlatos)] == 0) {
		$error[] = "Este plato no se ofrece en media ración.";
	}  elseif (empty($racionPedida)) {
		$error[] = "Tiene que especificar la racíon del plato.";
	} elseif (!($racionPedida == "media" || $racionPedida == "completa")) {
		$error[] = "La ración no es válida.";
	}
	
	
			// Validación de la cantidad pedida
	$unidades = $datos['unidades'];
	
if (strlen($unidades) == 0) {
	$error[] = "Tiene que especificar las unidades pedidas.";
}
elseif(!ctype_digit($unidades)){
	$error[] = "Las unidades pedidas debe ser un número entero positivo";
} 
elseif(intval($unidades) == 0){
	$error[] = "Las unidades pedidas debe ser un número entero positivo";
}
		
	
	
	
	
	return $error;
}











?>