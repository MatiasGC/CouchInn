<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Editar perfil</title>
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel='stylesheet' href='style.css'/>
	<link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
	<script src='ajax/jquery.min.js'></script>
	<script src='ajax/jquery.validate.min.js'></script>
	<script type="text/javascript" src="js/jquery-1.2.6.min.js"></script>
	<script type="text/javascript">
		window.onload = function () {
			document.getElementById("pass").onchange = validatePassword;
			document.getElementById("pass2").onchange = validatePassword;
			document.getElementById("nombre").onchange = validateNombre;
			document.getElementById("apellido").onchange = validateApellido;
			document.getElementById("telefono").onchange = validateTelefono;
		}
		function validatePassword(){
			var pass2=document.getElementById("pass").value;
			var pass1=document.getElementById("pass2").value;
			if(pass1!=pass2)
				document.getElementById("pass2").setCustomValidity("Las contraseñas no coinciden");
			else
				document.getElementById("pass2").setCustomValidity('');	 
		}
		function validateNombre(){
			var nomb = document.getElementById("nombre").value;
			var letters = /^[A-Za-zñÑáéíóúÜ\s]+$/;
			if (!nomb.match(letters))
				document.getElementById("nombre").setCustomValidity("Utilice sólo letras");
			else{
				document.getElementById("nombre").setCustomValidity("");
			}
		}
		function validateApellido(){
			var ape = document.getElementById("apellido").value;
			var letters = /^[A-Za-zñÑáéíóúÜ\s]+$/;
			if (!ape.match(letters))
				document.getElementById("apellido").setCustomValidity("Utilice sólo letras");
			else{
				document.getElementById("apellido").setCustomValidity("");
			}
		}
		function validateTelefono(){
			var tel = document.getElementById('telefono').value;
			if (isNaN(tel)){
				document.getElementById("telefono").setCustomValidity("Ingrese solo números");
			}else{
				document.getElementById("telefono").setCustomValidity("");
			}
		}
		
		function validate(field){
			if (field.className!="touched")
				field.className += "touched";
		}
		
	</script>
</head>

<body>
	<?php
		include('header.php');
		include('anuncioService.php');
		session_start();
		if(isset($_SESSION['usuario'])){
			$service = new cabecera($_SESSION['usuario']);
			$service->buildHeader();
		}else{
			header('Location:index.html');
		}
		$serv = new aService();
		$us = $serv->levantarUsuario($_SESSION['id']);
		$row = $us->fetch_assoc();
	?>
	<form id='MyForm' action="editarUsuario.php" method="POST" onsubmit="return validacion();">
		<div class='row reg'>
			<div class='col-xs-5 col-md-5'>
			</div>
			<div class='col-xs-2 col-md-2'>
			<input type="text" name = 'nombre' id='nombre' placeholder='Nombre' onblur='validate(this)' value='<?php echo $row['Nombre'] ?>' required>
			</div>
			<div class='col-xs-5 col-md-5'>
			</div>
		</div>
		<div class='row reg'>
			<div class='col-xs-5 col-md-5'>
			</div>
			<div class='col-xs-2 col-md-2'>
				<input type="text" name = 'apellido' id='apellido' placeholder='Apellido' onblur='validate(this)' value='<?php echo $row['Apellido'] ?>'required>
			</div>
			<div class='col-xs-5 col-md-5'>
			</div>
		</div>
		<div class='row reg'>
			<div class='col-xs-5 col-md-5'>
			</div>
			<div class='col-xs-2 col-md-2'>
				<input type="text" name = 'telefono' id='telefono' placeholder='Teléfono' onblur='validate(this)' value='<?php echo $row['Telefono'] ?>' required>
			</div>
			<div class='col-xs-5 col-md-5'>
			</div>
		</div>
		<div class='row reg'>
			<div class='col-xs-5 col-md-5'>
			</div>
			<div class='col-xs-2 col-md-2'>
				<input type="password" name= 'pass' minlength= "6" id = 'pass' placeholder='Contraseña' onblur='validate(this)'>
			</div>
			<div class='col-xs-5 col-md-5'>
			</div>
		</div>
		<div class='row reg'>
			<div class='col-xs-5 col-md-5'>
			</div>
			<div class='col-xs-2 col-md-2'>
				<input type="password" minlength= "6" name= 'pass2' id = 'pass2' placeholder='Confirmar contraseña' onblur='validate(this)'>
			</div>
			<div class='col-xs-5 col-md-5'>
			</div>
		</div>
		<div class='row reg'>
			<div class='col-xs-3 col-md-3'>
			</div>
			<div class='col-xs-2 col-md-2'>
				<a href="index.html"><button class="btn">Volver</button></a>
			</div>
			<div class='col-xs-2 col-md-2'>
				<button type="submit" class="btn">Guardar cambios</button>
			</div>
			<div class='col-xs-2 col-md-2'>
				<button class="btn" type="reset" onClick="window.location.reload()">Limpiar</button>
			</div>
			<div class='col-xs-3 col-md-3'>
			</div>
		</div>
	</form>
</div>
</body>
</html>