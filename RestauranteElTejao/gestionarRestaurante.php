<?php

//SOBRA
function consultarPlatosRestaurante($conexion) {

	$consulta = "SELECT * FROM Platos";
	try {
		return $conexion -> query($consulta);
	} catch(PDOException $e) {
		$_SESSION["excepcion"] = $e -> GetMessage();
		Header("Location: excepcion.php");
	}

}

function insertarNuevoPlato($conexion, $nombre, $precioMediaRacion, $precioRacion) {

	$accion = "INSERT INTO Platos (nombre, precioMediaRacion, precioRacion) VALUES (:nombre, TO_NUMBER(:precioMediaRacion), TO_NUMBER(:precioRacion))";
	$statement = $conexion -> prepare($accion);

	$as1 = strval($precioMediaRacion);
	$as2 = strval($precioRacion);
	
	$as3 = str_replace('.', ',', $as1);
	$as4 = str_replace('.', ',', $as2);
	
	$statement -> bindParam(':nombre', $nombre);
	$statement -> bindParam(':precioMediaRacion', $as3);
	$statement -> bindParam(':precioRacion', $as4);

	try {
		$statement -> execute();
		return "Se ha añadido nuevo plato al menú del restaurante: " .$nombre;
	}  catch(PDOException $e) {
		$_SESSION["excepcion"] = $e -> GetMessage();
		Header("Location: excepcion.php");
		return FALSE;
	}
}

function eliminarPlato($conexion, $nombre) {
	
	try {
		$accion=$conexion->prepare('DELETE FROM Platos WHERE NOMBRE = :nombre');
		$accion->bindParam(':nombre', $nombre);
		$accion->execute();
		return "correcto";
}  catch(PDOException $e) {
		$_SESSION["excepcion"] = $e -> GetMessage();
		Header("Location: excepcion.php");
		}
	
}


function eliminarTipoMesa($conexion, $oidMesa) {
	
	try {
		$accion=$conexion->prepare('DELETE FROM Mesas WHERE OID_M = :oidMesa');
		$accion->bindParam(':oidMesa', $oidMesa);
		$accion->execute();
		return "correcto";
}  catch(PDOException $e) {
		$_SESSION["excepcion"] = $e -> GetMessage();
		Header("Location: excepcion.php");
    }
	
}

function introducirTipoMesa($conexion, $capacidad, $ubicacion, $tipo) {
	try {
	$aux = $conexion->query("SELECT OID_M FROM Mesas ORDER BY OID_M DESC");
	
	$fila = $aux->fetch();
	if($fila == FALSE) {
		(int) $oid = 1;
	} else {
	(int) $oid = $fila["OID_M"] + 1;
	} 
	
	}  catch(PDOException $e) {
		$_SESSION["excepcion"] = $e -> GetMessage();
		Header("Location: excepcion.php");	
	}
	
	
	
	$accion = $conexion->prepare('CALL introducirNuevoTipoMesa(' .$oid. ', :capacidad, :ubicacion, :tipo)');
	try {
		$accion->bindParam(':capacidad',$capacidad);
		$accion->bindParam(':ubicacion',$ubicacion);
		$accion->bindParam(':tipo',$tipo);
		$accion->execute();
    return "Se ha añadido nuevo tipo de mesa. Tipo: " .$oid;
	}  catch(PDOException $e) {
		$_SESSION["excepcion"] = $e -> GetMessage();
		Header("Location: excepcion.php");
		return FALSE;
	}
	
	
}


function eliminarMesa($conexion, $identificador) {
	
	try {
		$accion=$conexion->prepare('DELETE FROM MesasConcretas WHERE Identificador = :identificador');
		$accion->bindParam(':identificador', $identificador);
		$accion->execute();
		return "correcto";
}  catch(PDOException $e) {
		$_SESSION["excepcion"] = $e -> GetMessage();
		Header("Location: excepcion.php");
    }
	
}

function nuevaMesa($conexion, $oidMesa) {
	try {
	$aux = $conexion->query("SELECT IDENTIFICADOR FROM MesasConcretas ORDER BY IDENTIFICADOR DESC");
	
	$fila = $aux->fetch();
	if($fila == FALSE) {
		(int) $oid = 1;
	} else {
	(int) $oid = $fila["IDENTIFICADOR"] + 1;
	} 
	
	}  catch(PDOException $e) {
		$_SESSION["excepcion"] = $e -> GetMessage();
		Header("Location: excepcion.php");
	}
			
	
	try {
		$accion=$conexion->prepare("CALL nuevaMesa(" .$oid. ", :oidMesa)");
		$accion->bindParam(':oidMesa',$oidMesa);
		$accion->execute();
		return "Se ha añadido nueva mesa de tipo " .$oidMesa. " El número identificador de la nueva mesa es <form class='boton' method='post' action='controlador_gestion_ElTejao.php'>
		<input type='hidden' name='identificador' value='".$oid."'> <button type='submit' name='control'>" .$oid. "</button> </form>";
	}  catch(PDOException $e) {
		$_SESSION["excepcion"] = $e -> GetMessage();
		Header("Location: excepcion.php");
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

function consultarReservas($conexion) {
	$consulta = "SELECT * FROM RESERVAS";
	
	try {
    return $conexion->query($consulta);
	}
	catch(PDOException $e) {
		$_SESSION["excepcion"] = $e->GetMessage();
		Header("Location: excepcion.php");
	}
	
}


function consultarGruposProductos($conexion) {
	$consulta = "SELECT OID_GP, nombre, fechaCaducidad, cantidadExistencia, unidadMedida FROM GruposDeProductos NATURAL JOIN Productos";
	try {
    return $conexion->query($consulta);
	}
	catch(PDOException $e) {
		$_SESSION["excepcion"] = $e->GetMessage();
		Header("Location: excepcion.php");
	}
	
}


function insertarNuevoProducto($conexion, $nombre, $unidadMedida, $umbral) {
	$statement = $conexion -> prepare('CALL nuevoProducto(:nombre, :unidadMedida, :umbral)');

	$statement -> bindParam(':nombre', $nombre);
	$statement -> bindParam(':unidadMedida', $unidadMedida);
	$statement -> bindParam(':umbral', $umbral);

	try {
		$statement -> execute();
		return "Se ha añadido nuevo producto: " .$nombre;
		
	}  catch(PDOException $e) {
		$_SESSION["excepcion"] = $e -> GetMessage();
		Header("Location: excepcion.php");
		return FALSE;
	}
	
	
}


function eliminarProducto($conexion, $nombre) {
	
	try {
		$accion=$conexion->prepare('DELETE FROM Productos WHERE NOMBRE = :nombre');
		$accion->bindParam(':nombre', $nombre);
		$accion->execute();
		return "correcto";
		
}  catch(PDOException $e) {
		$_SESSION["excepcion"] = $e -> GetMessage();
		Header("Location: excepcion.php");
    }
	
}

function validacionFormularioIntroducirNuevoPlato($datos){
	
	
	$error = array();
	
	
	// Validación del nombre del plato
$permitidos = "áéíóúabcdefghijklmnñopqrstuvwxyzÁÉÍÓÚABCDEFGHIJKLMNÑOPQRSTUVWXYZ "; 
$nombrePlato = $datos["nombreNuevoPlato"];


if (empty($nombrePlato)) {
	$error[] = "Tiene que especificar el nombre del plato.";
}
elseif (strlen($nombrePlato) > 60) {
	$error[] = "El nombre del plato no puede exceder 60 caracteres.";
} else {

for ($i=0; $i<strlen($nombrePlato); $i++){ 
if (strpos($permitidos, substr($nombrePlato,$i,1))===false){ 
$error[] = "El nombre del plato no es válido.";
	break;
} 
}  
}  
	
	
	
	// Validación del Precio (media ración)		
	$precioMediaRacion = $datos['precioMediaRacion'];
	
	
	
	if (!empty($precioMediaRacion) && !is_numeric($precioMediaRacion)) {
		$error[] = "El precio de media ración tiene que ser un valor numérico.";
	} elseif ($precioMediaRacion < 0) {
		$error[] = "El precio de media ración no puede ser negativo.";
	}
	
	
	
		// Validación del Precio
		$precioRacion = $datos['precioRacion'];
		
		if(empty($precioRacion)) {
			$error[] = "Tiene que especificar el precio (ración completa).";
		}
		
		elseif (!is_numeric($precioRacion)) {
		$error[] = "El precio (ración completa) tiene que ser un valor numérico.";
	} elseif ($precioRacion < 0) {
		$error[] = "El precio (ración completa) no puede ser negativo.";
	}
		
		
			
	return $error;
}


function validacionFormularioIntroducirNuevoTipoMesa($datos){
	
	
	$error = array();
	
	
	// Validación de la capacidad

$capacidad = $datos["capacidad"];


if (strlen($capacidad) == 0) {
	$error[] = "Tiene que especificar la capacidad.";
}
elseif(!ctype_digit($capacidad)){
	$error[] = "La capacidad debe ser un número entero positivo";
} 
elseif(intval($capacidad) == 0){
	$error[] = "La capacidad debe ser un número entero positivo";
}
	
	
	
	// Validación de la ubicacion		
	$ubicacion = $datos['ubicacion'];
	
	if (empty($ubicacion)) {
	$error[] = "Tiene que especificar la ubicación.";
}
	
	
	 elseif (!($ubicacion == "interior" || $ubicacion == "exterior")) {
		$error[] = "La ubicación no es válida.";
	}
	
	
	
		// Validación del tipo 
$tipo = $datos['fumar'];
	
	if (empty($tipo)) {
	$error[] = "Tiene que especificar el tipo.";
}
	
	
	 elseif (!($tipo == "fumadores" || $tipo == "noFumadores")) {
		$error[] = "El tipo no es válido.";
	}
		
		
			
	return $error;
}

function validacionFormularioIntroducirNuevoProducto($datos){
	
	
	$error = array();
	
	
	// Validación del nombre del producto
$permitidos = "áéíóúabcdefghijklmnñopqrstuvwxyzÁÉÍÓÚABCDEFGHIJKLMNÑOPQRSTUVWXYZ "; 
$nombre = $datos["nombreNuevoProducto"];


if (empty($nombre)) {
	$error[] = "Tiene que especificar el nombre del producto.";
}
elseif(strlen($nombre) > 30){
	$error[] = "El nombre del producto no puede exceder 30 caracteres.";
} else {

for ($i=0; $i<strlen($nombre); $i++){ 
if (strpos($permitidos, substr($nombre,$i,1))===false){ 
$error[] = "El nombre del producto no es válido.";
	break;
} 
}  
} 

	
	
	
	// Validación de la unidad de medida		
	$medida = $datos['unidadMedida'];
	
	if (empty($medida)) {
	$error[] = "Tiene que especificar la unidad de medida.";
}
	
	if (!($medida == "gramos" || 
	$medida == "kilogramos" ||
	$medida == "botes" ||
	$medida == "botellas" ||
	$medida == "paquetes" ||
	$medida == "litros" ||
	$medida == "latas" ||
	$medida == "unidades" ||
	$medida == "bolsas")) {
		$error[] = "La unidad de medida no es válida.";
		
	}
	

		// Validación del umbral de existencias
	$umbral = $datos['umbral'];
	
if (strlen($umbral) == 0) {
	$error[] = "Tiene que especificar el umbral de existencias.";
}
elseif(!ctype_digit($umbral)){
	$error[] = "El umbral de existencias debe ser un número entero positivo";
} 
elseif(intval($umbral) == 0){
	$error[] = "El umbral de existencias debe ser un número entero positivo";
}
		
		
			
	return $error;
}












?>
