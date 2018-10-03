<?php	
	session_start();
	
	if (isset($_SESSION["usuario"])) {
		
		
			
		if (isset($_REQUEST["reservas"])) Header("Location: gestion_reservas.php"); 
		else if (isset($_REQUEST["almacen"])) Header("Location: gestion_almacen.php");
		else if (isset($_REQUEST["gerente"])) Header("Location: gestion_ElTejao.php"); 
	}
	
	
	else {
		Header("Location: index.php");
	}
?>