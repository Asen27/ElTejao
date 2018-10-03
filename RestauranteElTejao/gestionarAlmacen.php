<?php

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

function tirarProductos($conexion, $grupo) {
	
	try {
		$accion=$conexion->prepare('DELETE FROM GruposDeProductos WHERE OID_GP = :grupo');
		$accion->bindParam(':grupo', $grupo);
		$accion->execute();
		return "correcto";
}  catch(PDOException $e) {
		$_SESSION["excepcion"] = $e -> GetMessage();
		Header("Location: excepcion.php");
    }
	
}

function entregarProductos($conexion, $grupo, $cantidad) {
	
	$accion = $conexion->prepare('CALL entregarProductos(:grupo, :cantidad)');
	try {
		$accion->bindParam(':grupo',$grupo);
		$accion->bindParam(':cantidad',$cantidad);
		
		$accion->execute();
       return "correcto";
	}
	catch(PDOException $e) {
		$_SESSION["excepcion"] = $e->GetMessage();
		Header("Location: excepcion.php");
	}
	
}
	


function introducirNuevoGrupoProductos($conexion,$nombre, $fechaCaducidad, $cantidad) {


	
	try {
	$aux = $conexion->query("SELECT OID_GP FROM GruposDeProductos ORDER BY OID_GP DESC");
	
	$fila = $aux->fetch();
	if($fila == FALSE) {
		(int) $oid = 1;
	} else {
	(int) $oid = $fila["OID_GP"] + 1;
	} 
	
	}  catch(PDOException $e) {
		$_SESSION["excepcion"] = $e -> GetMessage();
		Header("Location: excepcion.php");	
	}
	
	
	try {
		
		
		
		 
		
	$accion = "CALL nuevoGrupoDeProductos(".$oid.", :nombre, :fecha, :cantidad)"; 
	$statement = $conexion->prepare($accion);
	

	$fecha = strtotime($fechaCaducidad);
	$fechaCaducidad = date('d/m/Y', $fecha);
	
	
	$statement->bindParam(':nombre', $nombre);
	$statement->bindParam(':fecha', $fechaCaducidad);
	$statement->bindParam(':cantidad', $cantidad);


	
	$statement->execute();
	return "En el almacén del restaurante se ha colocado nuevo grupo de productos - el grupo " .$oid;
}  catch(PDOException $e) {
		$_SESSION["excepcion"] = $e -> GetMessage();
		Header("Location: excepcion.php");
	return FALSE;

}
}

function consultarProductos($conexion) {
	$consulta = "SELECT NOMBRE FROM Productos";
	
	try {
    return $conexion->query($consulta);
	}
	catch(PDOException $e) {
		$_SESSION["excepcion"] = $e->GetMessage();
		Header("Location: excepcion.php");
	}
	
}


function consultarProductosAComprar($conexion) {
	
	
	$consulta = "SELECT * FROM TablaAuxiliar6";
	
	try {
    return $conexion->query($consulta);
	}
	catch(PDOException $e) {
		$_SESSION["excepcion"] = $e->GetMessage();
		Header("Location: excepcion.php");
	}
	
}


function terminarConsulta6($conexion) {
	$accion = $conexion->prepare("DELETE FROM TablaAuxiliar6");
	
	try {
    return $accion->execute();
	}
	catch(PDOException $e) {
		$_SESSION["excepcion"] = $e->GetMessage();
		Header("Location: excepcion.php");
	}
	
	 
}


function comprobarExistencias($conexion) {
	
	
	try {
		$accion = $conexion->prepare('CALL comprobarExistencias()');
		$accion->execute();
       return "correcto";
	}
	catch(PDOException $e) {
		$_SESSION["excepcion"] = $e->GetMessage();
		Header("Location: excepcion.php");
	}
	
}


function consultarProductosCaducados($conexion) {
	
	
	$consulta = "SELECT * FROM TablaAuxiliar7";
	
	try {
    return $conexion->query($consulta);
	}
	catch(PDOException $e) {
		$_SESSION["excepcion"] = $e->GetMessage();
		Header("Location: excepcion.php");
	}
	
}


function terminarConsulta7($conexion) {
	$accion = $conexion->prepare("DELETE FROM TablaAuxiliar7");
	
	try {
    return $accion->execute();
	}
	catch(PDOException $e) {
		$_SESSION["excepcion"] = $e->GetMessage();
		Header("Location: excepcion.php");
	}
	
	 
}


function comprobarCaducidad($conexion) {
	
	
	try {
		$accion = $conexion->prepare('CALL comprobarFechaCaducidad()');
		$accion->execute();
       return "correcto";
	}
	catch(PDOException $e) {
		$_SESSION["excepcion"] = $e->GetMessage();
		Header("Location: excepcion.php");
	}
	
}

function fecha_valida($fecha) {
   $componentes = explode('-', $fecha);
   // He puesto esto porque según dicen los desarrolladores de Google Chrome
   // The input.value always returns as yyyy-mm-dd regardless of the presentation format.
   return checkdate((int) $componentes[1], (int) $componentes[2], (int) $componentes[0]);
}





function validacionFormularioIntroducirNuevoGrupoProductos($conexion, $datos) {
$error = array();
	
	
	$arrayProductos = array();
		$productos = consultarProductos($conexion);	
		foreach ($productos as $producto) {
			array_push($arrayProductos, $producto['NOMBRE']);
		   }
		
		
	
	// Validacion del nombre del producto
	$nombreProducto = $datos["nombreProducto"];
	
	
	if (empty($nombreProducto)) {
		$error[] = "Tiene que especificar el nombre del producto.";
	}
	elseif (!in_array($nombreProducto, $arrayProductos)) {
		$error[] = "No existe producto con ese nombre.";
	}
	

// Validación de la fecha de caducidad
	$fechaCaducidad = $datos["fechaCaducidad"];
	
	if (empty($fechaCaducidad) || !fecha_valida($fechaCaducidad)) {
		$error[] = "La fecha no es válida.";
	}



// Validación de la cantidad
	$cantidad = $datos['cantidad'];
	
if (strlen($cantidad) == 0) {
	$error[] = "Tiene que especificar la cantidad.";
}
elseif(!ctype_digit($cantidad)){
	$error[] = "La cantidad debe ser un número entero positivo";
} 
elseif(intval($cantidad) == 0){
	$error[] = "La cantidad debe ser un número entero positivo";
}
	

return $error;

}


function validacionFormularioEntregar($datos) {
	 $cantidad = $datos["cantidad"];  
	 $cantidadExistencia = $datos["cantidadExistencia"];  
	 
	 
	$error = array();
	
	
	// Validación de la cantidad a entregar
	
	if (strlen($cantidad) == 0) {
	$error[] = "Tiene que especificar la cantidad a entregar.";
}
elseif(!ctype_digit($cantidad)){
	$error[] = "La cantidad a entregar debe ser un número entero positivo";
} 
elseif(intval($cantidad) == 0){
	$error[] = "La cantidad a entregar debe ser un número entero positivo";
} elseif(intval($cantidad) > intval($cantidadExistencia)) {
	$error[] = "No hay suficientes productos en este grupo.";
}
	
	
	
	return $error;
	
}


















?>