<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Calificaciones</title>
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel='stylesheet' href='theme/rateit.css'/>
	<link rel='stylesheet' href='style.css'/>
	<script src="js/jquery.min.js"></script>
	<script src="theme/jquery.rateit.min.js"></script>
	<link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
</head>
<body>
	<?php
		include('header.php');
		include('anuncioService.php');
		session_start();
		if(isset($_SESSION['usuario'])){
			$service = new cabecera($_SESSION['usuario']);
			$service->buildHeader();
			$serv = new aService();
			$idAnun=$_POST['anunc'];
			$anun = $serv->levantarAnuncio($idAnun);
			$row = $anun->fetch_assoc();
			$calif = $serv->levantarCalificacionesAnuncio($idAnun);
			$id=$_SESSION['id'];
		}else{
			header('Location:index.html');
		}
	?>
	<center>
		<h2 class='calif'><?php echo $row['Titulo'];?></h2>
		<div class='row'>
			<?php 
				if($calif->num_rows>0){
					echo "	<strong>Valoración promedio</strong><br>
							<div class='rateit' data-rateit-value='".$serv->levantarPuntajePromedioAnuncio($idAnun)."' data-rateit-readonly='true' data-rateit-step='0.1' data-rateit-resetable='false'  data-rateit-ispreset='true'></div>
					";
					while ($rowComent = $calif->fetch_assoc()){
						if ($_SESSION['id']==$row['ID_usuario']){
							$serv->marcarLeidaCalif($rowComent['calificacion_ID']);
						}
						$usuarioQueCalifico = $serv->levantarUsuarioCalificador($rowComent['ID_calificacion_visitante']);
						$rowUsCal = $usuarioQueCalifico->fetch_assoc();
						$fechainicio=date('d/m/Y', strtotime($rowComent['fecha_inicio']));
						$fechafin=date('d/m/Y', strtotime($rowComent['fecha_fin']));
						echo "		
								<div class='row'>
									<div class='col-xs-2 col-md-2'>
									</div>
									<div class='col-xs-8 col-md-8 calificacion'>
										<div class='col-xs-2 col-md-2'>
										</div>
										<div class='col-xs-8 col-md-8'>
											<span>".$fechainicio." - ".$fechafin."</span>
											<br><br><div class='rateit' data-rateit-value='".$rowComent['puntaje']."' data-rateit-readonly='true' data-rateit-step='1' data-rateit-resetable='false'  data-rateit-ispreset='true'></div>
											<br><br><span class='content'>".$rowComent['comentario']."</span>
											<br><br><span>".$rowUsCal['Username']."</span><br>
										</div>
										<div class='col-xs-2 col-md-2'>
										</div>
									</div>
									<div class='col-xs-2 col-md-2'>
									</div>
								</div> <br>
						";
					}
				}else{
					echo "	<div class='row'>
								<div class='col-xs-2 col-md-2'>
								</div>
								<div class='col-xs-8 col-md-8'>
									<br>
									<strong><span class='titulo2'>No hay calificaciones para este anuncio</span></strong>
								</div>
								<div class='col-xs-2 col-md-2'>
								</div>
							</div>
					";	
				}
					echo "
					<form action='anuncDetalle.php' method='POST' enctype='multipart/form-data'>
					<input class=hidden name='anunc' value='".$idAnun."'>
					<center><button type='submit' class='btn22'>Volver</button></center>
				</form>";
			?>
		</div>
	</center>
	<?php 
		if(!isset($_POST['loaded'])){
			echo "	
				<form id='back' action='verCalificAnuncio.php' method='POST' enctype='multipart/form-data'>
					<input class='hidden' name='loaded' value='true'>	
					<input class='hidden' name='anunc' value=".$idAnun.">	
				</form>
				<script type='text/javascript'>
					function submitForm() {
						document.getElementById('back').submit();
					}
					window.onload = submitForm;
				</script>
			";
		}
	?>
</body>
</html>