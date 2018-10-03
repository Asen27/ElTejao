<?php
session_start();




if (isset($_SESSION["formularioAlta"])) {
	

	
	$usuario["puesto"] = $_REQUEST["puesto"];
	$usuario["clave"] = $_REQUEST["clave"];
	
	$usuario["nombre"] = $_REQUEST["nombre"];
	$usuario["apellidos"] = $_REQUEST["apellidos"];
	$usuario["nif"] = $_REQUEST["nif"];
	
	
	$usuario["fechaNacimiento"] = $_REQUEST["fechaNacimiento"];
	
	
	
	$usuario["email"] = $_REQUEST["email"];
	
	
	$usuario["telefono"] = $_REQUEST["telefono"];
	
	$provincias = array("", "A Coruña", "Álava", "Albacete", "Alicante", "Almería", "Asturias", "Ávila", "Badajoz", "Baleares", "Barcelona", "Burgos", "Cáceres",
	"Cádiz", "Cantabria", "Castellón", "Ciudad Real", "Córdoba", "Cuenca", "Girona", "Granada", "Guadalajara", "Gipuzkoa", "Huelva", "Huesca", "Jaén", "La Rioja",
	"Las Palmas", "León", "Lérida", "Lugo", "Madrid", "Málaga", "Murcia", "Navarra", "Orense", "Palencia", "Pontevedra", "Salamanca", "Segovia", "Sevilla",
	"Soria", "Tarragona", "Santa Cruz de Tenerife", "Teruel", "Toledo", "Valencia", "Valladolid", "Vizcaya", "Zamora", "Zaragoza", "Ceuta", "Melilla"); 
	

		if (array_key_exists($_REQUEST["provincia"], $provincias)) {
			$usuario["provincia"] = $provincias[$_REQUEST["provincia"]];
		} else {
			$usuario["provincia"] = $_REQUEST["provincia"];
	}
		
	

	$usuario["direccion"] = $_REQUEST["direccion"];
	
	
	$usuario["nick"] = $_REQUEST["nick"];
	$usuario["pass"] = $_REQUEST["pass"];
	$usuario["confirmarPass"] = $_REQUEST["confirmarPass"];
	
	

	
	$_SESSION["formularioAlta"]= $usuario;
	
	
	$errores = validacionPHP($usuario);
	
	
	if ( count ($errores) > 0 ) {
	$_SESSION["errores"] = $errores;
	Header("Location: formulario_alta.php");
	} else {
	Header("Location: exito_alta.php");
	}

}else{
	Header("Location: formulario_alta.php");   
}	





function fecha_valida($fecha) {
   $componentes = explode('-', $fecha);
   // He puesto esto porque según dicen los desarrolladores de Google Chrome
   // The input.value always returns as yyyy-mm-dd regardless of the presentation format.
   return checkdate((int) $componentes[1], (int) $componentes[2], (int) $componentes[0]);
}



function nif_valido($nif) {
    $nif = strtoupper($nif);
     
    $nifRegEx = '/^[0-9]{8}[A-Z]$/i';
    $nieRegEx = '/^[XYZ][0-9]{7}[A-Z]$/i';
     
    $letras = "TRWAGMYFPDXBNJZSQVHLCKE";
     
    if (preg_match($nifRegEx, $nif)) return ($letras[(substr($nif, 0, 8) % 23)] == $nif[8]);
    else if (preg_match($nieRegEx, $nif)) {
        if ($nif[0] == "X") $nif[0] = "0";
        else if ($nif[0] == "Y") $nif[0] = "1";
        else if ($nif[0] == "Z") $nif[0] = "2";
 
        return ($letras[(substr($nif, 0, 8) % 23)] == $nif[8]);
    }
    else return false;
}


function validacionPHP($usuario){
	
	
	$error = array();
	

	// Validación del Nombre			
	if (empty($usuario["nombre"])) {
		$error[] = "El nombre no puede estar vacío.";
	} elseif (strlen($usuario["nombre"]) > 25) {
		$error[] = "El nombre no puede exceder 25 caracteres.";
	}
	
	// Validación de los apellidos			
	if (empty($usuario["apellidos"])) {
		$error[] = "Los apellidos no pueden estar vacíos.";
	} elseif (strlen($usuario["nombre"]) > 50) {
		$error[] = "Los apellidos no pueden exceder 50 caracteres.";
	}
	
	
	
	// Validación del NIF
	if (empty($usuario["nif"])) {
		$error[] = "El NIF no puede estar vacío."; 
		
	} else if(!nif_valido($usuario["nif"])) {
		$error[] = "El NIF no es válido.";
	}


	
	$fecha = $usuario["fechaNacimiento"];
	
	

	if (empty($fecha) || !fecha_valida($fecha)) {
		$error[] = "La fecha de nacimiento no es válida.";
	}
	
	
	
	// Validación del email
	if (!empty($usuario["email"]) && !filter_var($usuario["email"], FILTER_VALIDATE_EMAIL)) {
		$error[] = "El email no es válido.";
	}
	
	
	// validacion del telefono
	if (empty($usuario["telefono"])) {
		$error[] = "El teléfono no puede estar vacío."; 
		
	} else if(!preg_match("/^[9|6|7][0-9]{8}$/", $usuario["telefono"])) {
		$error[] = "El teléfono no es válido.";
	}
	
	
	
	
	

	// Validacion de la direccion
		if (!empty($usuario["direccion"]) && strlen($usuario["direccion"])>60) {
		$error[] = "La dirección no puede exceder 60 caracteres.";
	}
	
	
	// Validacion del nick
		if (empty($usuario["nick"])) {
		$error[] = "El nombre de usuario no puede estar vacío."; 
		
	} else if(strlen($usuario["nick"]) > 20) {
		$error[] = "El teléfono no es válido.";
	}
	
	
	// Validación de la contraseña
	
		if (empty($usuario["pass"])) {
		$error[] = "La contraseña no puede estar vacía.";
	}
	
		else if (strlen($usuario["pass"])<8) {
		$error[] = "La contraseña debe contener al menos 8 caracteres.";
	}
	
		else if (!preg_match("~[0-9]~", $usuario["pass"])) {
		$error[] = "La contraseña debe contener al menos un dígito.";
	}
	
		else if (!preg_match("/[A-Z]+/", $usuario["pass"])) {
		$error[] = "La contraseña debe contener al menos un carácter en mayúscula.";
	}
	
		else if (!preg_match("/[a-z]+/", $usuario["pass"])) {
		$error[] = "La contraseña debe contener al menos un carácter en minúscula.";
	}
	
	
	if (empty($usuario["confirmarPass"])) {
		$error[] = "La confirmación de la contraseña no puede estar vacía.";
	}
	
		else if (strlen($usuario["confirmarPass"])<8) {
		$error[] = "La confirmación de la contraseña debe contener al menos 8 caracteres.";
	}
	
		else if (!preg_match("~[0-9]~", $usuario["confirmarPass"])) {
		$error[] = "La confirmación de la contraseña debe contener al menos un dígito.";
	}
	
		else if (!preg_match("/[A-Z]+/", $usuario["confirmarPass"])) {
		$error[] = "La confirmación de la contraseña debe contener al menos un carácter en mayúscula.";
	}
	
		else if (!preg_match("/[a-z]+/", $usuario["confirmarPass"])) {
		$error[] = "La confirmación de la contraseña debe contener al menos un carácter en minúscula.";
	}
	
	
		else if ($usuario["confirmarPass"] <> $usuario["pass"]) {
		$error[] = "La confirmación de la contraseña debe coincidir con la contraseña.";
	}
	
	
	
	
	
	
	// Validación del puesto
	
 if ($usuario["puesto"] <> "RESERVAS" && $usuario["puesto"] <> "ALMACEN" && $usuario["puesto"] <> "GERENTE") {
 	$error[] = "El puesto no es válido.";
 }
	
	
	// Validación de la clave

// AQUÍ TENEMOS QUE COMPROBAR SI LA CLAVE INTRODUCIDA COINCIDE CON LA QUE GERENTE HAYA ESTABLECIDO
// HASTA QUE LA GERENTE ESTABLEZCA LA CLAVE, LA APLICACIÓN ESTARÁ DISPONIBLE PARA TODO EL MUDNO
	
	
	

	
			
	return $error;
}

?>


