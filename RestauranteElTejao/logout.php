<?php
	session_start();
    
    if (isset($_SESSION["login"])) {
        unset($_SESSION["login"]);
	}
	if (isset($_SESSION["usuario"])) {
        unset($_SESSION["usuario"]);
	}
    header("Location: index.php");
?>
