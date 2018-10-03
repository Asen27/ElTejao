<?php
session_start();

if (!isset($_SESSION["formularioAlta"])) {


	$formularioAlta["puesto"] = "RESERVAS";

	$formularioAlta["nombre"] = "";
	$formularioAlta["apellidos"] = "";
	$formularioAlta["nif"] = "";
	$formularioAlta["fechaNacimiento"] = "";

	$formularioAlta["email"] = "";
	$formularioAlta["telefono"] = "";
	$formularioAlta["provincia"] = "";
	$formularioAlta["direccion"] = "";

	$_SESSION["formularioAlta"] = $formularioAlta;
}


else {
	$formularioAlta = $_SESSION["formularioAlta"];
}


if (isset($_SESSION["errores"])) {
	$errores = $_SESSION["errores"];
}

?>



<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="UTF-8">
		<meta name="author" content="Asen Rangelov Baykushev">
		<meta name="description" content="Trabajo práctico para la asignatura IISSI">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title> Formulario El Tejao </title>
		<link href="css/hojaDeEstilo.css" type="text/css" rel="stylesheet"> 

	</head>

	<body>



<?php 
include_once("cabecera.php");
?>

		<div class="row">
			<?php

			if (isset($errores)) {
				foreach ($errores as $error) {
					print("<div class='col-12 texto4 error'>");
					print("$error");
					print("</div>");
				}
			}
			?>
		</div>



		<form method="get" action="accion_formulario_alta.php" id="alta" autocomplete>


		<div class="row">
			<p class = " col-12 texto4">
				<em>Los campos obligatorios están marcados con </em><strong>*</strong>
			</p>
		</div>


<div class="row">
			<div class=" col-12 texto6">
				<fieldset form="alta">
				<label title="Elija el puesto que tendrás en el restaurante">Puesto: <strong>*</strong></label>
				<br>
				<label>
					<input name="puesto" type="radio" value="RESERVAS"  <?php
					if ($formularioAlta['puesto'] == 'RESERVAS')
						echo ' checked ';
				?>>
					Encargado de reservas</label>

				<label>
					<input name="puesto" type="radio" value="ALMACEN" <?php
					if ($formularioAlta['puesto'] == "ALMACEN")
						echo ' checked ';
				?>>
					Encargado de almacén</label>


				<label>
					<input name="puesto" type="radio" value="GERENTE" <?php
					if ($formularioAlta['puesto'] == 'GERENTE')
						echo ' checked ';
				?>>
					Gerente</label>
					</fieldset>
			</div>
</div>

<br>

		<div class="row">
			<div class="col-12 texto5 ">
				
				<fieldset form="alta">
				<label for="clave"><a title="¿Qué es la clave?" href="#ancla">Clave<sup>[1]</sup></a> del restaurante El Tejao:<strong>*</strong></label>
				<input type="password" id="clave" name="clave" title="Introduzca la clave proporcionada por la gerente en este campo.">
				</fieldset>
				
			</div>
</div>

<br>

<div class="row">
	
	<div class="col-6 texto3 tabla-responsive" >
			<fieldset   form="alta">
				<legend>
					<em> Datos personales: </em>
				</legend>

				<div>
					<label for="nombre">Nombre<strong>*</strong> </label>
					<input type="text" id="nombre" name="nombre"  placeholder="ej: Álvaro" size="25" maxlength="25" title="Introduzca su nombre en este campo. El nombre no puede exceder 25 caracteres."  value="<?php echo $formularioAlta['nombre']; ?>" >
				</div>

				<div>
					<label for="apellidos">Apellidos<strong>*</strong></label>
					<input type="text" id="apellidos" name="apellidos"  placeholder="ej: López Postigo" size="50" maxlength="50" required title="Introduzca sus apellidos en este campo. Los apellidos no pueden exceder 50 caracteres."
					value="<?php echo $formularioAlta['apellidos']; ?>" >
				</div>

				<div>
					<label for="nif">NIF<strong>*</strong></label>
					<input type="text" id="nif" name="nif"  placeholder="ej: 02679606Z" size="13"  maxlength="9" pattern="(([X-Z]{1})([-]?)(\d{7})([-]?)([A-Z]{1}))|((\d{8})([-]?)([A-Z]{1}))" required title="Introduzca su NIF en este campo. El NIF debe ser válido."
					value="<?php echo $formularioAlta['nif']; ?>" >
				</div>

				<div>
					<label for="fechaNacimiento">Fecha de nacimiento <strong>*</strong></label>
					<input type="date" id="fechaNacimiento" name="fechaNacimiento" required placeholder="yyyy-mm-dd" title="Introduzca su fecha de nacimiento en este campo. La fecha de nacimiento debe seguir el patrón yyyy-mm-dd."
					value="<?php echo $formularioAlta['fechaNacimiento']; ?>"/>
				</div>

			</fieldset>

</div>

<div class="col-6 texto3 tabla-responsive" >

			<fieldset  form="alta">
				<legend>
					<em> Datos de contacto: </em>
				</legend>

				<div>
					<label for="email">Correo electrónico</label>
					<input type="email" id="email" name="email"  placeholder="ej: alvaro@yahoo.es" title="Introduzca su correo electrónico en este campo. El email debe ser válido." 
					value="<?php echo $formularioAlta['email']; ?>" >
				</div>

				<div>
					<label for="telefono">Número de teléfono<strong>*</strong></label>
					<input type="tel" id="telefono" name="telefono" pattern="^[9|8|7|6]\d{8}$" placeholder="ej: 640209702" title="Introduzca su número de teléfono en este campo. El télefono debe ser español. No incluya el prefijo y no separe los dígitos."  
					value="<?php echo $formularioAlta['telefono']; ?>" required>
				</div>

				<div>
					<label for="provincia">Provincia</label>
					<input list="provincias" id="provincia" name="provincia" value="<?php
						if ($formularioAlta['provincia'] != "") {
							echo $formularioAlta['provincia'];
					 } ?>" placeholder="ej: Sevilla" size="30" maxlength="30"
					title="Introduzca su provincia en este campo. La provincia no puede exceder 30 caracteres."  >

					<datalist id="provincias">
						<option value="1">A Coruña</option>
						<option value="2">Álava</option>
						<option value="3">Albacete</option>
						<option value="4">Alicante</option>
						<option value="5">Almería</option>
						<option value="6">Asturias</option>
						<option value="7">Ávila</option>
						<option value="8">Badajoz</option>
						<option value="9">Baleares</option>
						<option value="10">Barcelona</option>
						<option value="11">Burgos</option>
						<option value="12">Cáceres</option>
						<option value="13">Cádiz</option>
						<option value="14">Cantabria</option>
						<option value="15">Castellón</option>
						<option value="16">Ciudad Real</option>
						<option value="17">Córdoba</option>
						<option value="18">Cuenca</option>
						<option value="19">Girona</option>
						<option value="20">Granada</option>
						<option value="21">Guadalajara</option>
						<option value="22">Gipuzkoa</option>
						<option value="23">Huelva</option>
						<option value="24">Huesca</option>
						<option value="25">Jaén</option>
						<option value="26">La Rioja</option>
						<option value="27">Las Palmas</option>
						<option value="28">León</option>
						<option value="29">Lérida</option>
						<option value="30">Lugo</option>
						<option value="31">Madrid</option>
						<option value="32">Málaga</option>
						<option value="33">Murcia</option>
						<option value="34">Navarra</option>
						<option value="35">Orense</option>
						<option value="36">Palencia</option>
						<option value="37">Pontevedra</option>
						<option value="38">Salamanca</option>
						<option value="39">Segovia</option>
						<option value="40">Sevilla</option>
						<option value="41">Soria</option>
						<option value="42">Tarragona</option>
						<option value="43">Santa Cruz de Tenerife</option>
						<option value="44">Teruel</option>
						<option value="45">Toledo</option>
						<option value="46">Valencia</option>
						<option value="47">Valladolid</option>
						<option value="48">Vizcaya</option>
						<option value="49">Zamora</option>
						<option value="50">Zaragoza</option>
						<option value="51">Ceuta</option>
						<option value="52">Melilla</option>
					</datalist>

				</div>

				<div>
					<label for="direccion">Dirección</label>
					<input type="text" id="direccion" name="direccion"  placeholder="ej: C/ Castillo de Constantina, 7" size="60" maxlength="60" title="Introduzca su dirección en este campo. La dirección no puede exceder 60 caracteres." 
					value="<?php echo $formularioAlta['direccion']; ?>" >
				</div>

			</fieldset>

</div>

</div>



<br>

<div class="row">
<div class="col-12 texto2 tabla-responsive">
			<fieldset  form="alta">

				<legend>
					<em> Datos de perfil: </em>
				</legend>

				<div>
					<label for="nick"> Nombre de usuario<strong>*</strong> </label>
					<input type="text" id="nick" name="nick" maxlength="20" size="20" placeholder="ej: alvaro7"  title="Elija un nombre de usuario. El nombre de usuario no puede exceder 20 caracteres."  required>
				</div>

				<div>
					<label for="pass">Contraseña<strong>*</strong></label>
					<input type="password" id="pass" name="pass"  placeholder="123456aA" pattern="(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$" title="Elija una contraseña. La contraseña debe contener al menos 8 caracteres entre letras (minúsculas y mayúsculas) y dígitos." required>
				</div>

				<div>
					<label for="confirmarPass">Confirmar la contraseña<strong>*</strong></label>
					<input type="password" id="confirmarPass" placeholder="123456aA" pattern="(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$" name="confirmarPass" title="Vuelva a escribir la contraseña en este campo" required>
				</div>

			</fieldset>
</div>


</div>

<br>
			<div class="row">
			<div class="col-12 texto7">
				<button class="botonAccion3" type="submit" name="enviar"> Enviar </button>
			</div>
			</div>


		</form>

<br>


<div class="row">
	<div class="col-3 texto4">
		<p id="ancla">
			<sup>[1]</sup>La clave es la contraseña proporcionada a los empleados del restaurante por el gerente
		</p>
		
	</div>
	
	</div>


	</body>

</html>