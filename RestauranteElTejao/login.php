<?php
session_start();

include_once ("gestionBD.php");
include_once ("gestionarUsuarios.php");

if (isset($_POST["submit"])) {

	$nick = $_POST["nick"];
	$pass = $_POST["pass"];

	$conexion = crearConexionBD();

	$usuarios = comprobarUsuario($conexion, $nick, $pass);

	cerrarConexionBD($conexion);

	if ($usuarios == 0) {
		$login = "error";
	} else {

		$_SESSION["login"] = $nick;
		Header("Location: menu.php");
	}

}
?>

<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="UTF-8">
		<meta name="author" content="Asen Rangelov Baykushev">
		<meta name="description" content="Trabajo práctico para la asignatura IISSI">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" type="text/css" href="css/hojaDeEstilo.css" />
		<title>Login</title>
	</head>

	<body>

		<?php
		include_once ("cabecera.php");
		?>

		<main>
			<div class="row">
				<?php
				if (isset($login)) {
					echo "<div class='col-12 texto4 error'>";
					echo "El nombre de usuario o la contraseña es incorrecta";
					echo "</div>";
				}
				?>
			</div>
			<br>
			<div class="row">

				<div class="col-12 texto5">

					<form action="login.php" method="post" autocomplete>
						<div>
							<label for="nick">Nombre de usuario: </label>
							<input type="text" name="nick" id="nick" />
						</div>
						<div>
							<label for="pass">Contraseña: </label>
							<input type="password" name="pass" id="pass" />
						</div>
						<button class="botonAccion4" type="submit" name="submit"  >Inicia sesión </button>
					</form>

				</div>

			</div>

			<br>

			<div class="row">

				<div class="col-12 texto6">
					<p >
						¿No estás registrado? <a href="formulario_alta.php">¡Regístrate!</a>
					</p>

				</div>

			</div>
		</main>

	</body>
</html>

