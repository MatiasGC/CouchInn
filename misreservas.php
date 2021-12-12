<!DOCTYPE html>
<html lang="en">
<head>
	<?php 
		session_start();
	?>
	<meta charset="UTF-8">
	<title>Reservas</title>
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
			changecss('.activas','display','inline');
			changecss('.aceptadas','display','inline');
			changecss('.canceladas','display','inline');
			changecss('.finalizadas','display','inline');
			changecss('.activas2','display','none');
			changecss('.aceptadas2','display','none');
			changecss('.canceladas2','display','none');
			changecss('.finalizadas2','display','none');
			$("#act").removeClass("selected");
			$("#acp").removeClass("selected");
			$("#canc").removeClass("selected");	
			$("#fin").removeClass("selected");			
		}
		function showEnv(){
			document.getElementById('enviadas').style.display = 'inline';
			$("#env").addClass("selected");
			document.getElementById('recibidas').style.display = 'none';
			$("#rec").removeClass("selected");
			changecss('.activas','display','inline');
			changecss('.aceptadas','display','inline');
			changecss('.canceladas','display','inline');
			changecss('.finalizadas','display','inline');
			changecss('.activas2','display','none');
			changecss('.aceptadas2','display','none');
			changecss('.canceladas2','display','none');
			changecss('.finalizadas2','display','none');
			$("#act2").removeClass("selected");
			$("#acp2").removeClass("selected");
			$("#canc2").removeClass("selected");	
			$("#fin2").removeClass("selected");	
		}
		function activas(){
			changecss('.activas','display','inline');
			changecss('.aceptadas','display','none');
			changecss('.canceladas','display','none');
			changecss('.finalizadas','display','none');
			changecss('.activas2','display','inline');
			changecss('.aceptadas2','display','none');
			changecss('.canceladas2','display','none');
			changecss('.finalizadas2','display','none');
			$("#act").addClass("selected");
			$("#acp").removeClass("selected");
			$("#canc").removeClass("selected");	
			$("#fin").removeClass("selected");	
			$("#act2").addClass("selected");
			$("#acp2").removeClass("selected");
			$("#canc2").removeClass("selected");	
			$("#fin2").removeClass("selected");	
		}
		function aceptadas(){
			changecss('.activas','display','none');
			changecss('.aceptadas','display','inline');
			changecss('.canceladas','display','none');
			changecss('.finalizadas','display','none');
			changecss('.activas2','display','none');
			changecss('.aceptadas2','display','inline');
			changecss('.canceladas2','display','none');
			changecss('.finalizadas2','display','none');
			$("#act").removeClass("selected");
			$("#acp").addClass("selected");
			$("#canc").removeClass("selected");	
			$("#fin").removeClass("selected");	
			$("#act2").removeClass("selected");
			$("#acp2").addClass("selected");
			$("#canc2").removeClass("selected");	
			$("#fin2").removeClass("selected");	
		}
		function canceladas(){
			changecss('.activas','display','none');
			changecss('.aceptadas','display','none');
			changecss('.canceladas','display','inline');
			changecss('.finalizadas','display','none');
			changecss('.activas2','display','none');
			changecss('.aceptadas2','display','none');
			changecss('.canceladas2','display','inline');
			changecss('.finalizadas2','display','none');
			$("#act").removeClass("selected");
			$("#acp").removeClass("selected");
			$("#canc").addClass("selected");	
			$("#fin").removeClass("selected");	
			$("#act2").removeClass("selected");
			$("#acp2").removeClass("selected");
			$("#canc2").addClass("selected");	
			$("#fin2").removeClass("selected");	
		}
		function finalizadas(){
			changecss('.activas','display','none');
			changecss('.aceptadas','display','none');
			changecss('.canceladas','display','none');
			changecss('.finalizadas','display','inline');
			changecss('.activas2','display','none');
			changecss('.aceptadas2','display','none');
			changecss('.canceladas2','display','none');
			changecss('.finalizadas2','display','inline');
			$("#act").removeClass("selected");
			$("#acp").removeClass("selected");
			$("#canc").removeClass("selected");	
			$("#fin").addClass("selected");	
			$("#act2").removeClass("selected");
			$("#acp2").removeClass("selected");
			$("#canc2").removeClass("selected");	
			$("#fin2").addClass("selected");	
		}
	</script>
</head>
<body>
	<?php
		include_once('header.php');
		include_once('anuncioService.php');
		include_once('cuentaOptions.php');
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
						<button type=button id='env' class='btn1234' onclick='showEnv();'>Reservas solicitadas</button>
					</div>
				</div>
				<div class='col-xs-2 col-md-2'>
					<div class='centered'>
						<button type=button id='rec' onclick='showRec();' class='btn1234'>Reservas recibidas</button>
					</div>
				</div>
				<div class='col-xs-4 col-md-4'>
				</div>
			</div>
			<br>
		";
		$solic = $serv->reservasRecibidas($id);
		echo "
			<div id='recibidas'>
				<div class='row barra123'>
					<div class='col-xs-3 col-md-3'>
						<div class='centered'>
							<button type=button id='act' class='btn123' onclick='activas();'>Activas</button>
						</div>
					</div>
					<div class='col-xs-3 col-md-3'>
						<div class='centered'>
							<button type=button id='acp' class='btn123' onclick='aceptadas();'>Aceptadas</button>
						</div>
					</div>
					<div class='col-xs-3 col-md-3'>
						<div class='centered'>
							<button type=button id='canc' onclick='canceladas();' class='btn123'>Canceladas</button>
						</div>
					</div>
					<div class='col-xs-3 col-md-3'>
						<div class='centered'>
							<button type=button id='fin' onclick='finalizadas();' class='btn123'>Finalizadas</button>
						</div>
					</div>
				</div>
		";
		if($solic->num_rows>0){
			$acti=0;
			$acep=0;
			$canc=0;
			$fina=0;
			while($row = $solic->fetch_assoc()){
				switch ($row['estado']){
					case 'activa':
						echo "<div class='activas'>";
						$acti++;
					break;
					case 'aceptada':
						echo "<div class='aceptadas'>";
						$acep++;
					break;
					case 'cancelada':
						echo "<div class='canceladas'>";
						$canc++;
					break;
					case 'finalizada':
						echo "<div class='finalizadas'>";
						$fina++;
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
									<form action='reservaDetalle.php' method='POST' enctype='multipart/form-data'>
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
			if ($acti==0){
				echo"
					<div class='activas2'>
						<div class='row'>
							<div class='col-xs-2 col-md-2'>
							</div>
							<div class='col-xs-8 col-md-8'>
								<center><strong><span class='titulo2'>No hay reservas activas</span></strong></center>
							</div>
							<div class='col-xs-2 col-md-2'>
							</div>
						</div>
					</div>
				";
			}
			if ($acep==0){
				echo"
					<div class='aceptadas2'>
						<div class='row'>
							<div class='col-xs-2 col-md-2'>
							</div>
							<div class='col-xs-8 col-md-8'>
								<center><strong><span class='titulo2'>No hay reservas aceptadas</span></strong></center>
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
								<center><strong><span class='titulo2'>No hay reservas canceladas</span></strong></center>
							</div>
							<div class='col-xs-2 col-md-2'>
							</div>
						</div>
					</div>
				";
			}
			if ($fina==0){
				echo"
					<div class='finalizadas2'>
						<div class='row'>
							<div class='col-xs-2 col-md-2'>
							</div>
							<div class='col-xs-8 col-md-8'>
								<center><strong><span class='titulo2'>No hay reservas finalizadas</span></strong></center>
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
							<strong><span class='titulo2'>No hay reservas recibidas</span></strong>
						</div>
						<div class='col-xs-2 col-md-2'>
						</div>
					</div>
				</center>
			";
		}
		echo "</div>";
		$solic2 = $serv->reservasEnviadas($id);
		echo "
			<div id='enviadas'>
				<div class='row barra123'>
					<div class='col-xs-3 col-md-3'>
						<div class='centered'>
							<button type=button id='act2' class='btn123' onclick='activas();'>Activas</button>
						</div>
					</div>
					<div class='col-xs-3 col-md-3'>
						<div class='centered'>
							<button type=button id='acp2' class='btn123' onclick='aceptadas();'>Aceptadas</button>
						</div>
					</div>
					<div class='col-xs-3 col-md-3'>
						<div class='centered'>
							<button type=button id='canc2' onclick='canceladas();' class='btn123'>Canceladas</button>
						</div>
					</div>
					<div class='col-xs-3 col-md-3'>
						<div class='centered'>
							<button type=button id='fin2' onclick='finalizadas();' class='btn123'>Finalizadas</button>
						</div>
					</div>
				</div>
		";
		if($solic2->num_rows>0){
			$acti=0;
			$acep=0;
			$canc=0;
			$fina=0;
			while($row2 = $solic2->fetch_assoc()){
				switch ($row2['estado']){
					case 'activa':
						echo "<div class='activas'>";
						$acti++;
					break;
					case 'aceptada':
						echo "<div class='aceptadas'>";
						$acep++;
					break;
					case 'cancelada':
						echo "<div class='canceladas'>";
						$canc++;
					break;
					case 'finalizada':
						echo "<div class='finalizadas'>";
						$fina++;
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
									<form action='reservaDetalle.php' method='POST' enctype='multipart/form-data'>
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
			if ($acti==0){
				echo"
					<div class='activas2'>
						<div class='row'>
							<div class='col-xs-2 col-md-2'>
							</div>
							<div class='col-xs-8 col-md-8'>
								<center><strong><span class='titulo2'>No hay reservas activas</span></strong></center>
							</div>
							<div class='col-xs-2 col-md-2'>
							</div>
						</div>
					</div>
				";
			}
			if ($acep==0){
				echo"
					<div class='aceptadas2'>
						<div class='row'>
							<div class='col-xs-2 col-md-2'>
							</div>
							<div class='col-xs-8 col-md-8'>
								<center><strong><span class='titulo2'>No hay reservas aceptadas</span></strong></center>
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
								<center><strong><span class='titulo2'>No hay reservas canceladas</span></strong></center>
							</div>
							<div class='col-xs-2 col-md-2'>
							</div>
						</div>
					</div>
				";
			}
			if ($fina==0){
				echo"
					<div class='finalizadas2'>
						<div class='row'>
							<div class='col-xs-2 col-md-2'>
							</div>
							<div class='col-xs-8 col-md-8'>
								<center><strong><span class='titulo2'>No hay reservas finalizadas</span></strong></center>
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
							<strong><span class='titulo2'>No hay reservas solicitadas</span></strong>
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