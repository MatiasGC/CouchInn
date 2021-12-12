<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Detalle de Solicitud</title>
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel='stylesheet' href='style.css'/>
	<script src="js/jquery.min.js"></script>
	<link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
</head>
<body>
	<?php
		include('anuncioService.php');
		include('header.php');
		session_start();
		if(isset($_SESSION['usuario'])){
			if(isset($_POST['idsol'])){
				$id=$_POST['idsol'];
				$tipo = $_POST['tipo'];
				$service = new cabecera($_SESSION['usuario']);
				$service->buildHeader();
				$serv = new aService();
				$sol = $serv->levantarSolicitud($id);
				$row = $sol->fetch_assoc();
				$anun = $serv->levantarAnuncio($row['ID_anuncio']);
				$anun_row = $anun->fetch_assoc();
				$inicial = date("d/m/Y", strtotime($row['fecha_inicio']));
				$final = date("d/m/Y", strtotime($row['fecha_fin']));
				$fecha = date("d/m/Y H:i", strtotime($row['fecha_solicitud']));
				$user = $serv->levantarUsuario($row['ID_usuario']);
				$user_row = $user->fetch_assoc();
				$host = $serv->levantarUsuario($anun_row['ID_usuario']);
				$host_row = $host->fetch_assoc();
				if ($tipo=='recibidas'){
					$serv->marcarLeidaSolicAutor($id);
				}else{
					$serv->marcarLeidaSolicHuesped($id);
				}
				if($row['cantidad_personas']==1){
					$persona='persona';
				}else{
					$persona='personas';
				}
			}else{
				header('Location:pagPrinc.php');
			}
		}else{
			header('Location:index.html');
		} 
	?>
	<center>
		<div class='anunciodet'>
			<div class='row row-titulo'>
				<h2><span><strong><?php echo $anun_row['Titulo'];?></strong> </span></h2>
			</div>
			<div>
			<br>
			<div>
				<span class='content'>Reserva para <?php echo $row['cantidad_personas']." ".$persona; ?> entre el 
				<?php echo $inicial." y el ".$final.", pedida por ".$user_row['Username']?>
					<br> <?php echo $fecha ?></span>
					<br>
					<span>Estado: <?php echo $row['estado']?></span>
					<br>
					<p class='text'>Comentarios: <?php echo $row['comentario']?></p>	
			</div>
			<div class='row'>
				<?php
					if ($tipo =='enviadas'){
						if ($row['estado']=='pendiente'){
							echo"
									<form action='cancelarSolic.php' method='POST' enctype='multipart/form-data'>
										<input class=hidden name='solic' value='".$row['ID']."'></input>
										<center><button type='submit' class='btn22'>Cancelar solicitud</button></center>
									</form>
							";
						}
					}elseif ($tipo == 'recibidas' ) {
						if ($row['estado']=='pendiente'){
							echo"
									<div class='col-xs-2 col-md-2'>
									</div>
									<div class='col-xs-2 col-md-2'>
										<form method='POST' action='verPerfil.php'>
											<input class='hidden' name='id' value='".$row['ID_usuario']."'>
											<button class='btn22' type='submit'>Ver perfil</button>
										</form>
									</div>
									<div class='col-xs-2 col-md-2'>
										<form action='responderSolicitud.php' method='POST' enctype='multipart/form-data'>
											<input class=hidden name='resp' value='aceptar'></input>
											<input class=hidden name='solic' value='".$row['ID']."'></input>
											<button type='submit' class='btn22'>Aceptar</button>
										</form>
									</div>
									<div class='col-xs-2 col-md-2'>
										<form action='responderSolicitud.php' method='POST' enctype='multipart/form-data'>
											<input class=hidden name='resp' value='rechazar'></input>
											<input class=hidden name='solic' value='".$row['ID']."'></input>
											<button type='submit' class='btn22'>Rechazar</button>
											</form>
									</div>
									<div class='col-xs-2 col-md-2'>
										<form action='verCalificUsuario.php' method='POST' enctype='multipart/form-data'>
											<input class=hidden name='solicUser' value='".$row['ID_usuario']."'></input>
											<input class=hidden name='retsol' value='".$row['ID']."'></input>
											<button type='submit' class='btn22'>Ver calificación ".$user_row['Username']."</button>
										</form>
									</div>
									<div class='col-xs-2 col-md-2'>
									</div>
							";
						}
					}
				?>
			</div>
			<div class='row'>
				<form action='solicitudes.php' method='POST' enctype='multipart/form-data'>
					<input class=hidden name='tipo' value='<?php echo $tipo;?>'>
					<center><button type='submit' class='btn22'>Volver</button></center>
				</form>
			</div>
		</div>
	</center>
</body>
</html>