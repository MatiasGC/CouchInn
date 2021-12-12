<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Solicitudes</title>
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel='stylesheet' href='style.css'/>
	<link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
	<script src="js/public_smo_scripts.js"></script>
	<script src="js/jquery.min.js"></script>
	<script type="text/javascript">
		function showRec(){
			document.getElementById('recibidas').style.display = 'inline';
			$("#rec").addClass("selected");
			document.getElementById('enviadas').style.display = 'none';
			$("#env").removeClass("selected");
			changecss('.pendientes','display','inline');
			changecss('.canceladas','display','inline');
			changecss('.rechazadas','display','inline');
			changecss('.pendientes2','display','none');
			changecss('.rechazadas2','display','none');
			changecss('.canceladas2','display','none');			
			$("#pend").removeClass("selected");
			$("#rech").removeClass("selected");
			$("#canc").removeClass("selected");		
		}
		function showEnv(){
			document.getElementById('enviadas').style.display = 'inline';
			$("#env").addClass("selected");
			document.getElementById('recibidas').style.display = 'none';
			$("#rec").removeClass("selected");
			changecss('.pendientes','display','inline');
			changecss('.canceladas','display','inline');
			changecss('.rechazadas','display','inline');
			changecss('.pendientes2','display','none');
			changecss('.rechazadas2','display','none');
			changecss('.canceladas2','display','none');
			$("#pend2").removeClass("selected");
			$("#rech2").removeClass("selected");
			$("#canc2").removeClass("selected");	
		}
		function pendientes(){
			changecss('.pendientes','display','inline');
			changecss('.rechazadas','display','none');
			changecss('.canceladas','display','none');
			changecss('.pendientes2','display','inline');
			changecss('.rechazadas2','display','none');
			changecss('.canceladas2','display','none');
			$("#pend").addClass("selected");
			$("#rech").removeClass("selected");
			$("#canc").removeClass("selected");	
			$("#pend2").addClass("selected");
			$("#rech2").removeClass("selected");
			$("#canc2").removeClass("selected");
		}
		function rechazadas(){
			changecss('.pendientes','display','none');
			changecss('.rechazadas','display','inline');
			changecss('.canceladas','display','none');
			changecss('.pendientes2','display','none');
			changecss('.rechazadas2','display','inline');
			changecss('.canceladas2','display','none');
			$("#pend").removeClass("selected");
			$("#rech").addClass("selected");
			$("#canc").removeClass("selected");	
			$("#pend2").removeClass("selected");
			$("#rech2").addClass("selected");
			$("#canc2").removeClass("selected");
		}
		function canceladas(){
			changecss('.pendientes','display','none');
			changecss('.rechazadas','display','none');
			changecss('.canceladas','display','inline');
			changecss('.pendientes2','display','none');
			changecss('.rechazadas2','display','none');
			changecss('.canceladas2','display','inline');
			$("#pend").removeClass("selected");
			$("#rech").removeClass("selected");
			$("#canc").addClass("selected");	
			$("#pend2").removeClass("selected");
			$("#rech2").removeClass("selected");
			$("#canc2").addClass("selected");
		}
	</script>
</head>
<body>
	<?php
		include('header.php');
		include('anuncioService.php');
		include('cuentaOptions.php');
		session_start();
		if((isset($_SESSION['usuario']))&&(isset($_POST['tipo']))){
			$tipo=$_POST['tipo'];
			if($tipo=='recibidas'){
				echo "	
					<script type='text/javascript'>
						window.onload = function mostrarR(){
							document.getElementById('rec').click();
						}	
					</script>
				";
			}
			if($tipo=='enviadas'){
				echo "	
					<script type='text/javascript'>
						window.onload = function mostrarE(){
							document.getElementById('env').click();
						}	
					</script>
				";
			}
			$serv = new aService();
			$id=$_SESSION['id'];
			$service = new cabecera($_SESSION['usuario']);
			$service->buildHeader();
			$display=new cuentaMenu();
		}else{
			header('Location:index.html');
		}
		echo "
			<div class='row barra1234''>
				<div class='col-xs-4 col-md-4'>
				</div>
				<div class='col-xs-2 col-md-2'>
					<div class='centered'>
						<button type=button id='env' class='btn1234' onclick='showEnv();'>Solicitudes enviadas</button>
					</div>
				</div>
				<div class='col-xs-2 col-md-2'>
					<div class='centered'>
						<button type=button id='rec' onclick='showRec();' class='btn1234'>Solicitudes recibidas</button>
					</div>
				</div>
				<div class='col-xs-4 col-md-4'>
				</div>
			</div>
			<br>
		";
		$solic = $serv->solicitudesRecibidas($id);
		echo "
			<div id='recibidas'>
				<div class='row barra123'>
					<div class='col-xs-4 col-md-4'>
						<div class='centered'>
							<button type=button id='pend' class='btn123' onclick='pendientes();'>Pendientes</button>
						</div>
					</div>
					<div class='col-xs-4 col-md-4'>
						<div class='centered'>
							<button type=button id='canc' class='btn123' onclick='canceladas();'>Canceladas</button>
						</div>
					</div>
					<div class='col-xs-4 col-md-4'>
						<div class='centered'>
							<button type=button id='rech' onclick='rechazadas();' class='btn123'>Rechazadas</button>
						</div>
					</div>
				</div>
		";
		if($solic->num_rows>0){
			$pend=0;
			$canc=0;
			$rech=0;
			while($row = $solic->fetch_assoc()){
				switch ($row['estado']){
					case 'pendiente':
						echo "<div class='pendientes'>";
						$pend++;
					break;
					case 'cancelada':
						echo "<div class='canceladas'>";
						$canc++;
					break;
					case 'rechazada':
						echo "<div class='rechazadas'>";
						$rech++;
					break;
				}
				$inicial = date("d/m/Y", strtotime($row['fecha_inicio']));
				$final = date("d/m/Y", strtotime($row['fecha_fin']));
				echo "	
					<div class='row'>
						<div class='col-xs-2 col-md-2'>
						</div>
						<div class='col-xs-8 col-md-8 solicitud'>
							<div class='row'>
								<div class='col-xs-4 col-md-4'>
									<strong><span>".$row['Titulo']."</span></strong>
								</div>
								<div class='col-xs-3 col-md-3'>
									<span>".$inicial." - ".$final."</span>
								</div>
								<div class='col-xs-3 col-md-3'>
									<span>Estado: ".$row['estado']."</span>
								</div>
								<div class='col-xs-2 col-md-2'>
									<form action='solicitudDetalle.php' method='POST' enctype='multipart/form-data'>
										<button type='submit' class='btn222'>
											<input class='hidden' name='idsol' value='".$row['solicitud_ID']."'>
											<input class='hidden' name='tipo' value='recibidas'>
											<span>Ver detalle</span>
										</button>
									</form>
								</div>
							</div>
						</div>
						<div class='col-xs-2 col-md-2'>
						</div>
					</div>
				</div>
				";
			}
			if ($pend==0){
				echo"
					<div class='pendientes2'>
						<div class='row'>
							<div class='col-xs-2 col-md-2'>
							</div>
							<div class='col-xs-8 col-md-8'>
								<center><strong><span class='titulo2'>No hay solicitudes pendientes</span></strong></center>
							</div>
							<div class='col-xs-2 col-md-2'>
							</div>
						</div>
					</div>
				";
			}
			if ($canc==0){
				echo"
					<div class='canceladas2'>
						<div class='row'>
							<div class='col-xs-2 col-md-2'>
							</div>
							<div class='col-xs-8 col-md-8'>
								<center><strong><span class='titulo2'>No hay solicitudes canceladas</span></strong></center>
							</div>
							<div class='col-xs-2 col-md-2'>
							</div>
						</div>
					</div>
				";
			}
			if ($rech==0){
				echo"
					<div class='rechazadas2'>
						<div class='row'>
							<div class='col-xs-2 col-md-2'>
							</div>
							<div class='col-xs-8 col-md-8'>
								<center><strong><span class='titulo2'>No hay solicitudes rechazadas</span></strong></center>
							</div>
							<div class='col-xs-2 col-md-2'>
							</div>
						</div>
					</div>
				";
			}
		}else{
			echo"
				<center>
					<div class='row'>
						<div class='col-xs-2 col-md-2'>
						</div>
						<div class='col-xs-8 col-md-8'>
							<br>
							<strong><span class='titulo2'>No hay solicitudes recibidas</span></strong>
						</div>
						<div class='col-xs-2 col-md-2'>
						</div>
					</div>
				</center>
			";
		}
		echo "</div>";
		$solic2 = $serv->solicitudesEnviadas($id);
		echo "
			<div id='enviadas'>
				<div class='row barra123'>
					<div class='col-xs-4 col-md-4'>
						<div class='centered'>
							<button type=button id='pend2' class='btn123' onclick='pendientes();'>Pendientes</button>
						</div>
					</div>
					<div class='col-xs-4 col-md-4'>
						<div class='centered'>
							<button type=button id='canc2' class='btn123' onclick='canceladas();'>Canceladas</button>
						</div>
					</div>
					<div class='col-xs-4 col-md-4'>
						<div class='centered'>
							<button type=button id='rech2' onclick='rechazadas();' class='btn123'>Rechazadas</button>
						</div>
					</div>
				</div>
		";
		if($solic2->num_rows>0){
			$pend=0;
			$canc=0;
			$rech=0;
			while($row2 = $solic2->fetch_assoc()){
				switch ($row2['estado']){
					case 'pendiente':
						echo "<div class='pendientes'>";
						$pend++;
					break;
					case 'cancelada':
						echo "<div class='canceladas'>";
						$canc++;
					break;
					case 'rechazada':
						echo "<div class='rechazadas'>";
						$rech++;
					break;
				}
				$inicial = date("d/m/Y", strtotime($row2['fecha_inicio']));
				$final = date("d/m/Y", strtotime($row2['fecha_fin']));
				echo "	
					<div class='row'>
						<div class='col-xs-2 col-md-2'>
						</div>
						<div class='col-xs-8 col-md-8 solicitud'>
							<div class='row'>
								<div class='col-xs-4 col-md-4'>
									<strong><span>".$row2['Titulo']."</span></strong>
								</div>
								<div class='col-xs-3 col-md-3'>
									<span>".$inicial." - ".$final."</span>
								</div>
								<div class='col-xs-3 col-md-3'>
									<span>Estado: ".$row2['estado']."</span>
								</div>
								<div class='col-xs-2 col-md-2'>
									<form action='solicitudDetalle.php' method='POST' enctype='multipart/form-data'>
										<button type='submit' class='btn222'>
											<input class='hidden' name='idsol' value='".$row2['solicitud_ID']."'>
											<input class='hidden' name='tipo' value='enviadas'>
											<span>Ver detalle</span>
										</button>
									</form>
								</div>
							</div>
						</div>
						<div class='col-xs-2 col-md-2'>
						</div>
					</div>
				</div>
				";
			}
			if ($pend==0){
				echo"
					<div class='pendientes2'>
						<div class='row'>
							<div class='col-xs-2 col-md-2'>
							</div>
							<div class='col-xs-8 col-md-8'>
								<center><strong><span class='titulo2'>No hay solicitudes pendientes</span></strong></center>
							</div>
							<div class='col-xs-2 col-md-2'>
							</div>
						</div>
					</div>
				";
			}
			if ($canc==0){
				echo"
					<div class='canceladas2'>
						<div class='row'>
							<div class='col-xs-2 col-md-2'>
							</div>
							<div class='col-xs-8 col-md-8'>
								<center><strong><span class='titulo2'>No hay solicitudes canceladas</span></strong></center>
							</div>
							<div class='col-xs-2 col-md-2'>
							</div>
						</div>
					</div>
				";
			}
			if ($rech==0){
				echo"
					<div class='rechazadas2'>
						<div class='row'>
							<div class='col-xs-2 col-md-2'>
							</div>
							<div class='col-xs-8 col-md-8'>
								<center><strong><span class='titulo2'>No hay solicitudes rechazadas</span></strong></center>
							</div>
							<div class='col-xs-2 col-md-2'>
							</div>
						</div>
					</div>
				";
			}
		}else{
			echo"
				<center>
					<div class='row'>
						<div class='col-xs-2 col-md-2'>
						</div>
						<div class='col-xs-8 col-md-8'>
							<strong><span class='titulo2'>No hay solicitudes enviadas</span></strong>
						</div>
						<div class='col-xs-2 col-md-2'>
						</div>
					</div>
				</center>
			";
		}
		echo "</div>";
	?>
</body>
</html>