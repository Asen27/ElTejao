<?php
session_start();

require_once ("gestionBD.php");
require_once ("gestionarRestaurante.php");

	if (isset($_REQUEST["logout"])) {
		
		
		if (isset($_SESSION["menuTiposMesa"])) {
		unset($_SESSION["menuTiposMesa"]);
	} elseif (isset($_SESSION["menuMesas"])) {
	 unset($_SESSION["menuMesas"]);
	} elseif (isset($_SESSION["menuPlatos"])) {
		unset($_SESSION["menuPlatos"]);
	} 	 elseif (isset($_SESSION["menuProductos"])) {
		unset($_SESSION["menuProductos"]);
	}
		
		
	//	Header("Location: logout.php"); 
	}


if (isset($_REQUEST["platos"])) {

	$_SESSION["menuPlatos"] = "menuPlatos";

	if (isset($_SESSION["menuTiposMesa"])) {
		unset($_SESSION["menuTiposMesa"]);
	}
	
	  elseif (isset($_SESSION["menuMesas"])) {
	 unset($_SESSION["menuMesas"]);
	 } 
	 
	  elseif (isset($_SESSION["menuProductos"])) {
	 unset($_SESSION["menuProductos"]);
	 } 
	 
	
} elseif (isset($_REQUEST["tiposMesa"])) {

	$_SESSION["menuTiposMesa"] = "menuTiposMesa";

	if (isset($_SESSION["menuPlatos"])) {
		unset($_SESSION["menuPlatos"]);
	}
	elseif (isset($_SESSION["menuMesas"])) {
	 unset($_SESSION["menuMesas"]);
	 } 
	  elseif (isset($_SESSION["menuProductos"])) {
	 unset($_SESSION["menuProductos"]);
	 } 
	
	

}elseif (isset($_REQUEST["mesas"])  || isset($_REQUEST["control"])) {

	$_SESSION["menuMesas"] = "menuMesas";

	if (isset($_SESSION["menuPlatos"])) {
		unset($_SESSION["menuPlatos"]);
	}
	elseif (isset($_SESSION["menuTiposMesa"])) {
	 unset($_SESSION["menuTiposMesa"]);
	 } 
	
	 elseif (isset($_SESSION["menuProductos"])) {
	 unset($_SESSION["menuProductos"]);
	 } 
	

if (isset($_REQUEST["control"])) {
			$_SESSION["marcar"] = $_REQUEST["identificador"];
		}

		
}  elseif (isset($_REQUEST["productos"])) {

	$_SESSION["menuProductos"] = "menuProductos";

	if (isset($_SESSION["menuPlatos"])) {
		unset($_SESSION["menuPlatos"]);
	}
	elseif (isset($_SESSION["menuMesas"])) {
	 unset($_SESSION["menuMesas"]);
	 } 
	  elseif (isset($_SESSION["menuTiposMesa"])) {
	 unset($_SESSION["menuTiposMesa"]);
	 } 


}





if (isset($_SESSION["menuPlatos"])) {

	if (isset($_REQUEST["introducirPlato"])) {
		$conexion = crearConexionBD();
		$datos["nombreNuevoPlato"] = $_REQUEST["nombreNuevoPlato"];
		$datos["precioMediaRacion"] = $_REQUEST["precioMediaRacion"];
		$datos["precioRacion"] = $_REQUEST["precioRacion"];

		// VALIDACIÓN PHP
		
		$errores = validacionFormularioIntroducirNuevoPlato($datos);

		if ( count ($errores) > 0 ) {
			$_SESSION["erroresPlato"] = $errores;
			$_SESSION["formularioPlato"]= $datos;
			} else {
		$mensaje = insertarNuevoPlato($conexion, $datos["nombreNuevoPlato"], $datos["precioMediaRacion"], $datos["precioRacion"]);
		cerrarConexionBD($conexion);
		if (!isset($mensaje)) {
			Header("Location: excepcion.php");
		} elseif(!$mensaje) {
			$error = array();
			$error[] = "¡Ya existe plato con este nombre!";
			$_SESSION["erroresPlato"] = $error;
			$_SESSION["formularioPlato"]= $datos;
		} else {
		
		
		$_SESSION["mensaje"] = $mensaje;
			}
		}
		
		
		
	} elseif (isset($_REQUEST["eliminarPlato"])) {
		$conexion = crearConexionBD();
		$nombre = $_REQUEST["nombre"];
		$check = eliminarPlato($conexion, $nombre);
		cerrarConexionBD($conexion);
		if ($check <> "correcto") {
			Header("Location: excepcion.php");
		}

	}

	Header("Location: gestion_ElTejao.php");




} elseif (isset($_SESSION["menuTiposMesa"])) {


	if (isset($_REQUEST["eliminarTipoMesa"])) {
		$conexion = crearConexionBD();
		$oidMesa = $_REQUEST["oidMesa"];
	
		$check = eliminarTipoMesa($conexion, $oidMesa);
		cerrarConexionBD($conexion);
		if ($check <> "correcto") {
			Header("Location: excepcion.php");
		}

		
		
	} elseif (isset($_REQUEST["introducirMesa"])) {
		$conexion = crearConexionBD();
		$oidMesa = $_REQUEST["oidMesa"];
		$mensaje = nuevaMesa($conexion, $oidMesa);
		cerrarConexionBD($conexion);
		if (!isset($mensaje)) {
			Header("Location: excepcion.php");
		}
		$_SESSION["mensaje"] = $mensaje;

		}
		
		
	elseif (isset($_REQUEST["introducirTipoMesa"])) {
		$conexion = crearConexionBD();
		$datos["capacidad"] = $_REQUEST["capacidad"];
		$datos["ubicacion"] = $_REQUEST["ubicacion"];
		$datos["fumar"] = $_REQUEST["fumar"];
		
		
		// VALIDACIÓN PHP
		
		$errores = validacionFormularioIntroducirNuevoTipoMesa($datos);

		if ( count ($errores) > 0 ) {
			$_SESSION["erroresTipoMesa"] = $errores;
			$_SESSION["formularioTipoMesa"]= $datos;
			} else {		
		$mensaje = introducirTipoMesa($conexion, $datos["capacidad"], $datos["ubicacion"], $datos["fumar"]);
		cerrarConexionBD($conexion);
		if (!isset($mensaje)) {
			Header("Location: excepcion.php");
		}elseif(!$mensaje) {
			$error = array();
			$error[] = "¡Ya existe el mismo tipo de mesa (misma capacidad, misma ubicación y mismo tipo)!";
			$_SESSION["erroresTipoMesa"] = $error;
			$_SESSION["formularioTipoMesa"]= $datos;
		} else {
		
		
		$_SESSION["mensaje"] = $mensaje;
			}
		
			}
	}
	


	Header("Location: gestion_ElTejao.php");

} elseif (isset($_SESSION["menuMesas"])) {



	if (isset($_REQUEST["eliminarMesa"])) {
		$conexion = crearConexionBD();
		$identificador = $_REQUEST["id"];
	
		$check = eliminarMesa($conexion, $identificador);
		cerrarConexionBD($conexion);
		if ($check <> "correcto") {
			Header("Location: excepcion.php");
		}
		
	}
	
	
	
	Header("Location: gestion_ElTejao.php");
	
	
	} elseif (isset($_SESSION["menuProductos"])) {
				
				
			if (isset($_REQUEST["introducirProducto"])) {
		$conexion = crearConexionBD();
		$datos["nombreNuevoProducto"] = $_REQUEST["nombreNuevoProducto"];
		$datos["unidadMedida"] = $_REQUEST["unidadMedida"];
		$datos["umbral"] = $_REQUEST["umbral"];


		// VALIDACIÓN PHP
		
		$errores = validacionFormularioIntroducirNuevoProducto($datos);

		if ( count ($errores) > 0 ) {
			$_SESSION["erroresProducto"] = $errores;
			$_SESSION["formularioProducto"]= $datos;
			} else {

		$mensaje = insertarNuevoProducto($conexion, $datos["nombreNuevoProducto"], $datos["unidadMedida"], $datos["umbral"]);
		cerrarConexionBD($conexion);
		if (!isset($mensaje)) {
			Header("Location: excepcion.php");
		} elseif(!$mensaje) {
			$error = array();
			$error[] = "¡Ya existe producto con el mismo nombre!";
			$_SESSION["erroresProducto"] = $error;
			$_SESSION["formularioProducto"]= $datos;
			
			
		} else {
		$_SESSION["mensaje"] = $mensaje;
		}
		
			}
		
		
		
	} elseif (isset($_REQUEST["eliminarProducto"])) {
		$conexion = crearConexionBD();
		$nombre = $_REQUEST["nombreProducto"];
		$check = eliminarProducto($conexion, $nombre);
		cerrarConexionBD($conexion);
		if ($check <> "correcto") {
			Header("Location: excepcion.php");
		}

	}
			
		
		
		
	
	Header("Location: gestion_ElTejao.php");
	
	
	
} else {

	Header("Location: index.php");
}
?>

