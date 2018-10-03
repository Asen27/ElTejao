<?php	
	session_start();
	
 require_once("gestionBD.php");
 require_once("gestionarReservas.php");
	
	
	if (isset($_REQUEST["logout"])) {
		if (isset($_SESSION["menuPeticionesReserva"])) {
		unset($_SESSION["menuPeticionesReserva"]);
			
		} elseif (isset($_SESSION["menuReservas"])) {
		unset($_SESSION["menuReservas"]);
		} elseif (isset($_SESSION["menuPedidos"])) {
		unset($_SESSION["menuPedidos"]);
		}
		elseif (isset($_SESSION["menuClientes"])) {
		unset($_SESSION["menuClientes"]);
		}
		
		
	//	Header("Location: logout.php"); 
	}
	
	
	
	if (isset($_REQUEST["clientes"])) {
		
		$_SESSION["menuClientes"] = "menuClientes";
		
		if (isset($_SESSION["menuPeticionesReserva"])) {
		unset($_SESSION["menuPeticionesReserva"]);
			
		} elseif (isset($_SESSION["menuReservas"])) {
		unset($_SESSION["menuReservas"]);
		} elseif (isset($_SESSION["menuPedidos"])) {
		unset($_SESSION["menuPedidos"]);
		}
	}

	elseif (isset($_REQUEST["peticionesReserva"]) || isset($_REQUEST["control"])) {
	$_SESSION["menuPeticionesReserva"] = "menuPeticionesReserva";
		if (isset($_SESSION["menuClientes"])) {
		unset($_SESSION["menuClientes"]);
		}  elseif (isset($_SESSION["menuReservas"])) {
		unset($_SESSION["menuReservas"]);
		} elseif (isset($_SESSION["menuPedidos"])) {
		unset($_SESSION["menuPedidos"]);
		}
		
		if (isset($_REQUEST["control"])) {
			$_SESSION["marcar"] = $_REQUEST["oid"];
		}
		
	} elseif (isset($_REQUEST["reservas"]) || isset($_REQUEST["control2"])) {
		$_SESSION["menuReservas"] = "menuReservas";
		if (isset($_SESSION["menuClientes"])) {
		unset($_SESSION["menuClientes"]);
		}  elseif (isset($_SESSION["menuPeticionesReserva"])) {
		unset($_SESSION["menuPeticionesReserva"]);
		} elseif (isset($_SESSION["menuPedidos"])) {
		unset($_SESSION["menuPedidos"]);
		}
		
		if (isset($_REQUEST["control2"])) {
			$_SESSION["marcar"] = $_REQUEST["codigo"];
		}
	}
		
		elseif (isset($_REQUEST["pedidos"]) || isset($_REQUEST["control3"])) {
		$_SESSION["menuPedidos"] = "menuPedidos";
		if (isset($_SESSION["menuClientes"])) {
		unset($_SESSION["menuClientes"]);
		}  elseif (isset($_SESSION["menuPeticionesReserva"])) {
		unset($_SESSION["menuPeticionesReserva"]);
		}  elseif (isset($_SESSION["menuReservas"])) {
		unset($_SESSION["menuReservas"]);
		}
		
		if (isset($_REQUEST["control3"])) {
			$_SESSION["marcar"] = $_REQUEST["codigoPedido"];
		}
	}
		
		
		
		
	if (isset($_SESSION["menuClientes"])) {
		

	    
		
		if (isset($_REQUEST["introducirCliente"])) {
			$conexion = crearConexionBD();
			$datos["nombreNuevoCliente"] = $_REQUEST["nombreNuevoCliente"];
			$datos["apellidosNuevoCliente"] = $_REQUEST["apellidosNuevoCliente"];
	        $datos["telefonoNuevoCliente"] = $_REQUEST["telefonoNuevoCliente"];
			
			
			
			// VALIDACIÓN PHP
		
		$errores = validacionFormularioIntroducirNuevoCliente($datos);

		if ( count ($errores) > 0 ) {
			$_SESSION["erroresCliente"] = $errores;
			$_SESSION["formularioCliente"]= $datos;
			} else {
			$check = insertarNuevoCliente($conexion, $datos["nombreNuevoCliente"],$datos["apellidosNuevoCliente"],  $datos["telefonoNuevoCliente"]);
			cerrarConexionBD($conexion);
			
			if (!isset($check)) {
				Header("Location: excepcion.php"); 
			}elseif(!$check) {
				$error = array();
			$error[] = "¡Ya existe el mismo cliente (mismo nombre, mismos apellidos y mismo teléfono)!";
			$_SESSION["erroresCliente"] = $error;
			$_SESSION["formularioCliente"]= $datos;
				
			} 
		}
		
		
		
		} elseif (isset($_REQUEST["eliminarCliente"])) {
			$conexion = crearConexionBD();
			$cliente = $_REQUEST["oidCliente"];
			$check = eliminarCliente($conexion, $cliente);
			cerrarConexionBD($conexion);
			if ($check <> "correcto") {
				Header("Location: excepcion.php"); 
			}
		}
		
		elseif (isset($_REQUEST["peticionReserva"])) {
			$_SESSION["clienteReserva"] = $_REQUEST["oidCliente"];
		}
		
		
		elseif (isset($_REQUEST["anadirPeticion"])) {
			$conexion = crearConexionBD();
			$datos["oidCliente"] = $_REQUEST["oidCliente"];
			$datos["tipoMesa"] = $_REQUEST["tipoMesa"];
			$datos["ubicacion"] = $_REQUEST["ubicacion"];
			$datos["numPersonas"] = $_REQUEST["numPersonas"];
			$datos["fechaReserva"] = $_REQUEST["fechaReserva"];
			$datos["hora"] = $_REQUEST["hora"];
			$datos["minutos"] = $_REQUEST["minutos"];
			
			// VALIDACIÓN PHP
		
		$errores = validacionFormularioIntroducirNuevaPeticionReserva($datos);

		if ( count ($errores) > 0 ) {
			$_SESSION["erroresPeticion"] = $errores;
			$_SESSION["formularioPeticion"]= $datos;
			} else {
			
		
			$mensaje = nuevaPeticionReserva($conexion, $datos["numPersonas"], $datos["fechaReserva"], $datos["hora"], $datos["minutos"], $datos["ubicacionMesa"], $datos["tipoMesa"], $datos["oidCliente"]);	
			cerrarConexionBD($conexion);
			if (!isset($mensaje)) {
				Header("Location: excepcion.php"); 
			}
			$_SESSION["mensaje"] = $mensaje;
		}
		
		}
		
		
		Header("Location: gestion_reservas.php"); 

	
	} 
	
	
	
	elseif (isset($_SESSION["menuPeticionesReserva"])) {
	
		if (isset($_REQUEST["eliminarPeticion"])) {
			$conexion = crearConexionBD();
			$peticion = $_REQUEST["oidPeticion"];
			$check = eliminarPeticionReserva($conexion, $peticion);
			cerrarConexionBD($conexion);
			if ($check <> "correcto") {
				Header("Location: excepcion.php"); 
			}
		}
	
		elseif (isset($_REQUEST["encontrarMesa"])) {
			$conexion = crearConexionBD();
			$peticion = $_REQUEST["oidPeticion"];
			$check = encontrarMesasDisponibles($conexion, $peticion);
			cerrarConexionBD($conexion);
			if ($check <> "correcto") {
				Header("Location: excepcion.php"); 
			}
			$_SESSION["subMenuMesasDisponibles"] = $_REQUEST["oidPeticion"];
	
		}
	
		elseif (isset($_REQUEST["reservarMesa"])) {
			$conexion = crearConexionBD();
			$oidPR = $_REQUEST["peticionReservaID"];
			$identificadorMesa = $_REQUEST["idMesa"];
			$mensaje = realizarReserva($conexion, $oidPR, $identificadorMesa);
			cerrarConexionBD($conexion);
			if (!isset($mensaje)) {
				Header("Location: excepcion.php"); 
			}
			
			$_SESSION["mensaje"] = $mensaje;
			
			
			
		}
	
		elseif (isset($_REQUEST["incluirPedido"])) {
			$conexion = crearConexionBD();
			$oidPR = $_REQUEST["oidPeticion"];
			$mensaje = incluirNuevoPedido($conexion, $oidPR);
			cerrarConexionBD($conexion);
			if (!isset($mensaje)) {
				Header("Location: excepcion.php"); 
			}
			
			$_SESSION["mensaje"] = $mensaje;
				
		}
		
		
	
	Header("Location: gestion_reservas.php"); 
	
	} elseif (isset($_SESSION["menuReservas"])) {
		
		if (isset($_REQUEST["eliminarReserva"])) {
			$conexion = crearConexionBD();
			$codigo = $_REQUEST["codigoReserva"];
			$check = eliminarReserva($conexion, $codigo);
			cerrarConexionBD($conexion);
			if ($check <> "correcto") {
				Header("Location: excepcion.php"); 
			}
		}
		
		
		
		elseif (isset($_REQUEST["imprimirFactura"])) {
			$conexion = crearConexionBD();
			$codigo = $_REQUEST["codigoFactura"];
			$check = imprimirFactura($conexion, $codigo);
			cerrarConexionBD($conexion);
			if ($check <> "correcto") {
				Header("Location: excepcion.php"); 
			}
			
			$_SESSION["factura"] = "factura";
		}
		
		
		
		
		
		
		
		
		
		
		Header("Location: gestion_reservas.php"); 
		
		
		} elseif (isset($_SESSION["menuPedidos"])) {
		
		
			if (isset($_REQUEST["eliminarPedido"])) {
				$conexion = crearConexionBD();
				$codigo = $_REQUEST["oidPedido"];
				$check = eliminarPedido($conexion, $codigo);
				cerrarConexionBD($conexion);
				if ($check <> "correcto") {
				Header("Location: excepcion.php"); 
				}
			}
		
		
			elseif (isset($_REQUEST["imprimirPedido"])) {
				$conexion = crearConexionBD();
				$pedido = $_REQUEST["oidPedido"];		
				$check = imprimirPedido($conexion, $pedido);
				cerrarConexionBD($conexion);	
				if ($check <> "correcto") {
				Header("Location: excepcion.php"); 
			}
				
				$_SESSION["pedido"] = "pedido";
			}
			
	
			
			elseif (isset($_REQUEST["pedirPlato"])) {
				$_SESSION['pedidoPlato'] = $_REQUEST["oidPedido"];				
			}
			
			elseif (isset($_REQUEST["pedir"])) {
				$conexion = crearConexionBD();
				$datos["oidPedido"] = $_REQUEST["oidPedido"];		
				$datos["nombrePlato"] = $_REQUEST["nombrePlato"];	
				$datos["racionPlato"] = $_REQUEST["racionPlato"];		
				$datos["unidades"] = $_REQUEST["unidades"];	
				
				
				// VALIDACIÓN PHP
		
		$errores = validacionFormularioIntroducirNuevaLineaDePedido($conexion, $datos);

		if ( count ($errores) > 0 ) {
			$_SESSION["erroresPedido"] = $errores;
			$_SESSION["formularioPedido"]= $datos;
			} else {
				
				
				$mensaje = insertarNuevaLineaPedido($conexion, $datos["oidPedido"], $datos["nombrePlato"], $datos["racionPlato"], $datos["unidades"]);
				cerrarConexionBD($conexion);
				
				
					
				if (!isset($mensaje)) {
				Header("Location: excepcion.php"); 
			} elseif (!$mensaje) {
				
			$error = array();
			$error[] = "¡En este pedido ya existe la misma línea (mismo nombre de plato y misma ración)!";
			$_SESSION["erroresPedido"] = $error;
			$_SESSION["formularioPedido"]= $datos;	
			} else {
				
				$_SESSION["mensaje"] = $mensaje;
				
			}
			}
			}
			
			elseif (isset($_REQUEST["eliminarPlatoPedido"])) {
				$conexion = crearConexionBD();
				$pedido = $_REQUEST["oidPedido"];		
				$nombrePlato = $_REQUEST["nombre"];		
				$racionPlato = $_REQUEST["racion"];				
				$check = eliminarPlatoPedido($conexion, $pedido, $nombrePlato, $racionPlato);
				cerrarConexionBD($conexion);	
				if ($check <> "correcto") {
				Header("Location: excepcion.php"); 
				}
				
			}
			
			
		Header("Location: gestion_reservas.php"); 
	} else {
		
		
		
		
	
	
	
		Header("Location: index.php");
	}
?>