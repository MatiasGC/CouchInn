<?php
	class cabecera{
		protected $user;
		public function __construct($us){
			$this->user = $us;
		}
		public function buildHeader(){
			include_once('anuncioService.php');
			$id=$_SESSION['id'];
			$serv = new aService();
			$preg=$serv->notificarPregunta($id);
			$resp=$serv->notificarRespuesta($id);
			$solic=$serv->notificarSolicitud($id); //nueva solicitud recibida
			$solicResp=$serv->notificarRespuestaSolicitud($id); //solicitud aceptada o rechazada por el dueño
			$solicCanc=$serv->notificarSolicitudCancelada($id); //solicitud cancelada por el huesped
			$resCancH=$serv->notificarReservaCanceladaH($id); //reserva cancelada por el huesped
			$resCancA=$serv->notificarReservaCanceladaA($id); //reserva cancelada por el dueño
			$califAnunc=$serv->notificarCalificacionAnuncio($id);
			$califUser=$serv->notificarCalificacionUser($id);
			$califPendAnunc=$serv->notificarCalificacionPendienteAnuncio($id);
			$califPendUser=$serv->notificarCalificacionPendienteUser($id);
			$cant=($preg->num_rows+$resp->num_rows+$solic->num_rows+$solicResp->num_rows+$solicCanc->num_rows+$resCancH->num_rows+$resCancA->num_rows+$califAnunc->num_rows+$califUser->num_rows+$califPendAnunc->num_rows+$califPendUser->num_rows);
			echo "	
					<script type='text/javascript'>
						$(document).ready(function()
						{
							$('#notificationLink').click(function(){
								$('#notificationContainer').fadeToggle(300);
								return false;
							});
							$(document).click(function(){
								$('#notificationContainer').hide();
							});
						});
					</script>
					<nav class='navbar navbar-default navbar-fixed-top'>
						<div class='header'>
							<div class='row'>
								<div id='logo' class='col-xs-4 col-md-4'>
									<a href='pagPrinc.php'><img src=logo.png width=200></a>
								</div>
								<div id='vertcentered2' class='col-xs-4 col-md-4'>
									<span>Bienvenido ", $_SESSION['usuario'], "</span>
								</div>
								<div id='opcionesuser' class='col-xs-4 col-md-4'>
									<div id='notification_li'>
			";
			if($cant>0){
				echo "
					<span id='notification_count'>".$cant."</span>
				";
			}
			echo"
										<a href=# id='notificationLink'>
											<button class='btn22' type='button'>
												<img src='theme/images/notif.png' width=30>
											</button>
										</a>
										<div id='notificationContainer'>
											<div id='notificationTitle'>Notificaciones</div>
											<div id='notificationsBody' class='notifications'>";
												if ($cant==0){
													echo "
														<span>No hay nuevas notificaciones</span>
													";
												}
												while ($row1=$preg->fetch_assoc()){
													echo "	<form action='anuncDetalle.php' method='POST' enctype='multipart/form-data'>
																<input class='hidden' name='anunc' value='".$row1['anuncio_ID']."'>
																<button type='submit' class='btn222'>Recibió una nueva pregunta</button>
															</form>
													";
												}
												while ($row2=$resp->fetch_assoc()){
													echo "	<form action='anuncDetalle.php' method='POST' enctype='multipart/form-data'>
																<input class='hidden' name='anunc' value='".$row2['anuncio_ID']."'>
																<button type='submit' class='btn222'>Una pregunta fue respondida</button>
															</form>
													";
												}
												while ($row3=$solic->fetch_assoc()){
													echo "	<form action='solicitudDetalle.php' method='POST' enctype='multipart/form-data'>
																<input class='hidden' name='idsol' value='".$row3['solicitud_ID']."'>
																<input class='hidden' name='tipo' value='recibidas'>
																<button type='submit' class='btn222'>Recibió una nueva solicitud de hospedaje</button>
															</form>
													";
												}
												while ($row9=$solicCanc->fetch_assoc()){
													echo "	<form action='solicitudDetalle.php' method='POST' enctype='multipart/form-data'>
																<input class='hidden' name='idsol' value='".$row9['solicitud_ID']."'>
																<input class='hidden' name='tipo' value='recibidas'>
																<button type='submit' class='btn222'>Una solicitud de hospedaje fue cancelada</button>
															</form>
													";
												}
												while ($row4=$solicResp->fetch_assoc()){
													if ($row4['estado']=='aceptada'){
														echo "	<form action='reservaDetalle.php' method='POST' enctype='multipart/form-data'>
																	<input class='hidden' name='idsol' value='".$row4['ID']."'>
																	<input class='hidden' name='tipo' value='enviadas'>
																	<button type='submit' class='btn222'>Una solicitud de hospedaje fue aceptada</button>
																</form>
														";
													}else{
														echo "	<form action='solicitudDetalle.php' method='POST' enctype='multipart/form-data'>
																	<input class='hidden' name='idsol' value='".$row4['ID']."'>
																	<input class='hidden' name='tipo' value='enviadas'>
																	<button type='submit' class='btn222'>Una solicitud de hospedaje fue rechazada</button>
																</form>
														";
													}
												}
												while ($row10=$resCancH->fetch_assoc()){
													echo "	<form action='reservaDetalle.php' method='POST' enctype='multipart/form-data'>
																<input class='hidden' name='idsol' value='".$row10['solicitud_ID']."'>
																<input class='hidden' name='tipo' value='recibidas'>
																<button type='submit' class='btn222'>Una reserva fue cancelada</button>
															</form>
													";
												}
												while ($row11=$resCancA->fetch_assoc()){
													echo "	<form action='reservaDetalle.php' method='POST' enctype='multipart/form-data'>
																<input class='hidden' name='idsol' value='".$row11['ID']."'>
																<input class='hidden' name='tipo' value='enviadas'>
																<button type='submit' class='btn222'>Una reserva fue cancelada</button>
															</form>
													";
												}
												while ($row5=$califAnunc->fetch_assoc()){
													echo "
														<form action='calificaciones.php' method='POST' enctype='multipart/form-data'>
															<input class=hidden name='tipo' value='nenviadas'></input>
															<button type='submit' class='btn222'>Un anuncio recibió una nueva calificación</button>
														</form>
													";
												}
												while ($row6=$califUser->fetch_assoc()){
													echo "	
														<form action='calificaciones.php' method='POST' enctype='multipart/form-data'>
															<input class=hidden name='tipo' value='nrecibidas'></input>
															<button type='submit' class='btn222'>Recibió una nueva calificación como huésped</button>
														</form>
													";
												}
												while ($row7=$califPendAnunc->fetch_assoc()){
													echo "	
															<form action='reservaDetalle.php' method='POST' enctype='multipart/form-data'>
																<input class='hidden' name='idsol' value='".$row7['solicitud_ID']."'>
																<input class='hidden' name='tipo' value='enviadas'>
																<button type='submit' class='btn222'>Tiene una calificación pendiente</button>
															</form>
													";
												}
												while ($row8=$califPendUser->fetch_assoc()){
													echo "
															<form action='reservaDetalle.php' method='POST' enctype='multipart/form-data'>
																<input class='hidden' name='idsol' value='".$row8['solicitud_ID']."'>
																<input class='hidden' name='tipo' value='recibidas'>
																<button type='submit' class='btn222'>Tiene una calificación pendiente</button>
															</form>
													";
												}
			echo"
											</div>
										</div>
									</div>
									<form method='POST' action='verPerfil.php' class='headerform'>
										<input class='hidden' name='id' value=".$_SESSION['id'].">
										<button class='btn22' type='submit'>Mi<br>cuenta</button>
									</form>
			";
			switch ($_SESSION['type']){
				case ("admin"):
					echo "				
									<a href='panelAdmin.php'><button type=button class='btn22'>Panel de<br>administrador</button></a>
					";
					break;
				case ("common"):
					echo "			
									<a href='infoPremium.php'><button type='button' class='btn22'>Comprar<br>premium</button></a>
					";
					break;
			}
			echo"					<a href='cerrarSesion.php'><button type=button class='btn22'>Cerrar<br>Sesión</button></a>
								</div>
							</div>
						</div>
					</nav>
			";
		}
	}
?>