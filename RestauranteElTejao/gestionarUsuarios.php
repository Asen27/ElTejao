<?php
 
 function alta_usuario($conexion,$usuario) {


	
	try {
	$aux = $conexion->query("SELECT OID_USUARIO FROM USUARIOS ORDER BY OID_USUARIO DESC");
	
	$fila = $aux->fetch();
	if($fila == FALSE) {
		(int) $oid = 1;
	} else {
	(int) $oid = $fila["OID_USUARIO"] + 1;
	} 
	
	}catch(PDOException $e){
		$_SESSION['excepcion'] = $e->GetMessage();
		header("Location: excepcion.php");
	}
	
	
	
		
		
		
		 
		
	$consulta = "INSERT INTO USUARIOS (NOMBRE, APELLIDOS, NIF, FECHA_NACIMIENTO, EMAIL, TELEFONO, PROVINCIA, DIRECCION, NICK, PASS, PUESTO, OID_USUARIO)
  VALUES (:nombre, :apellidos, :nif, :fechaNacimiento, :email, :telefono, :provincia, :direccion, :nick, :pass, :puesto, " .$oid. ")"; 
	$statement = $conexion->prepare($consulta);
	

	$fecha = strtotime($usuario["fechaNacimiento"]);
	$fechaNacimiento = date('d/m/Y', $fecha);
	
	
	$statement->bindParam(':nombre', $usuario["nombre"]);
	$statement->bindParam(':apellidos', $usuario["apellidos"]);
	$statement->bindParam(':nif', $usuario["nif"]);
	$statement -> bindParam(':fechaNacimiento', $fechaNacimiento);
	$statement -> bindParam(':email', $usuario["email"]);
	$statement -> bindParam(':telefono', $usuario["telefono"]);
	$statement -> bindParam(':provincia', $usuario["provincia"]);
	$statement -> bindParam(':direccion', $usuario["direccion"]);
	$statement -> bindParam(':nick', $usuario["nick"]);
	$statement -> bindParam(':pass', $usuario["pass"]);
	$statement -> bindParam(':puesto', $usuario["puesto"]);

	try {
	$statement->execute();
	return true;


}catch(PDOException $e){
		$_SESSION['excepcion'] = $e->GetMessage();
		header("Location: excepcion.php");
		return false;
	}
}





function comprobarUsuario($conexion, $nick, $pass) {
	
 	
 	try {
	$consulta = "SELECT COUNT(*) AS TOTAL FROM USUARIOS WHERE NICK=:nick AND PASS=:pass";
	
	$statement = $conexion->prepare($consulta);
	$statement->bindParam(':nick', $nick);
	$statement->bindParam(':pass', $pass);
	$statement->execute();
	return $statement->fetchColumn();
	}
 	
 	catch(PDOException $e) {
		$_SESSION["excepcion"] = $e->GetMessage();
		Header("Location: excepcion.php");
	}


 	
}


function obtenerDatosUsuario ($conexion, $nick) {
	
	
	try {
		$statement = $conexion->prepare("SELECT NOMBRE, APELLIDOS, NIF, FECHA_NACIMIENTO, EMAIL, TELEFONO, PROVINCIA, DIRECCION, PUESTO FROM USUARIOS WHERE NICK=:nick");
		$statement->bindParam(':nick', $nick);
		$statement->execute();
	    $fila = $statement->fetch();
	    $login = array();
		$login["nombre"] = $fila["NOMBRE"];
		$login["apellidos"] = $fila["APELLIDOS"];
		$login["nif"] = $fila["NIF"];
		if (isset($fila["FECHA_NACIMIENTO"])) {
		$login["fechaNacimiento"] = $fila["FECHA_NACIMIENTO"]; }
		if (isset($fila["EMAIL"])) {
		$login["email"] = $fila["EMAIL"]; }
		$login["nick"] = $nick; 
		$login["telefono"] = $fila["TELEFONO"];
		if (isset($fila["PROVINCIA"])) {
		$login["provincia"] = $fila["PROVINCIA"]; }
		if (isset($fila["DIRECCION"])) {
		$login["direccion"] = $fila["DIRECCION"]; }
		$login["puesto"] = $fila["PUESTO"];
		return $login;
		
  } catch (PDOException $e) {
		$_SESSION["excepcion"] = $e->GetMessage();
		Header("Location: excepcion.php");
	}

}













?>
