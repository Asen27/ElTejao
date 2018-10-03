<?php
session_start();

require_once ("gestionBD.php");
require_once ("gestionarAlmacen.php");


	if (isset($_REQUEST["logout"])) {
		
		
	if (isset($_SESSION["menuGruposProductos"])) {
	unset($_SESSION["menuGruposProductos"]);
	}
		
	//	Header("Location: logout.php"); 
	}



if (isset($_REQUEST["gruposProductos"])) {

	$_SESSION["menuGruposProductos"] = "menuGruposProductos";
	 
}








if (isset($_SESSION["menuGruposProductos"])) {

	if (isset($_REQUEST["entregarProductos"])) {
		$_SESSION["grupoProductos"] = $_REQUEST["oidGrupo"];
		
		
	} elseif (isset($_REQUEST["tirarProductos"])) {
		$conexion = crearConexionBD();
		$grupo = $_REQUEST["oidGrupo"];
		$check = tirarProductos($conexion, $grupo);
		cerrarConexionBD($conexion);
		if ($check <> "correcto") {
			Header("Location: excepcion.php");
		}
	
	}  elseif (isset($_REQUEST["entregar"])) {
		$conexion = crearConexionBD();
		
		
		$datos["oidGrupo"] = $_REQUEST["oidGrupo"];
		$datos["cantidad"] = $_REQUEST["cantidad"];
		$datos["cantidadExistencia"] = $_REQUEST["cantidadExistencia"];
		
		// VALIDACIÓN PHP
		
		$errores = validacionFormularioEntregar($datos);

		if ( count ($errores) > 0 ) {
			$_SESSION["erroresEntregar"] = $errores;
			$_SESSION["formularioEntregar"]= $datos;
			} else {
		
		
		$check = entregarProductos($conexion, $datos["oidGrupo"], $datos["cantidad"]);
		cerrarConexionBD($conexion);
		if ($check <> "correcto") {
			Header("Location: excepcion.php");
		}
		}
	
	
	
		}  elseif (isset($_REQUEST["introducirGrupo"])) {
		$conexion = crearConexionBD();
		$datos["nombreProducto"] = $_REQUEST["nombreProducto"];
		$datos["cantidad"] = $_REQUEST["cantidad"];
		$datos["fechaCaducidad"] = $_REQUEST["fechaCaducidad"];
		
		// VALIDACIÓN PHP
		
		$errores = validacionFormularioIntroducirNuevoGrupoProductos($conexion, $datos);

		if ( count ($errores) > 0 ) {
			$_SESSION["erroresGrupoProductos"] = $errores;
			$_SESSION["formularioGrupoProductos"]= $datos;
			} else {


		$mensaje = introducirNuevoGrupoProductos($conexion, $datos["nombreProducto"], $datos["fechaCaducidad"], $datos["cantidad"]);
		cerrarConexionBD($conexion);
		if (!isset($mensaje)) {
			Header("Location: excepcion.php");
		} elseif(!$mensaje) {
			$error = array();
			$error[] = "¡Ya existe el mismo grupo de productos (mismo producto y la misma fecha de caducidad)!";
			$_SESSION["erroresGrupoProductos"] = $error;
			$_SESSION["formularioGrupoProductos"]= $datos;	
				
			
		} else {
			
		$_SESSION["mensaje"] = $mensaje;
		}
			}
	}  
		
		
		  elseif (isset($_REQUEST["comprobarExistencias"])) {
		$conexion = crearConexionBD();
		$check = comprobarExistencias($conexion);
		cerrarConexionBD($conexion);
		if ($check <> "correcto") {
			Header("Location: excepcion.php");
		}
		
		$_SESSION["subMenuComprobarExistencias"] = "subMenuComprobarExistencias";
	}

 		elseif (isset($_REQUEST["comprobarFechasCaducidad"])) {
		$conexion = crearConexionBD();
		$check = comprobarCaducidad($conexion);
		cerrarConexionBD($conexion);
		if ($check <> "correcto") {
			Header("Location: excepcion.php");
		}
		
		$_SESSION["subMenuComprobarCaducidad"] = "subMenuComprobarCaducidad";
	}



	Header("Location: gestion_almacen.php");


	
} else {

 	Header("Location: index.php");
}
?>

