<?php
	include_once('dbManager.php');
	
	class aService{
		protected $titulo;
		protected $descripcion;
		protected $capacidad;
		protected $ciudad;
		protected $tipo;
		protected $imagen;
		protected $user;
		protected $date;
		public function levantarProv(){
			$conec = new dbManager();
			$conec->conectar();
			$consulta = "SELECT * FROM provincia";
			$resulSQL= $conec->ejecutarSQL($consulta);
			return $resulSQL;
		}
		public function levantarCiudad($idProv){
			$conec = new dbManager();
			$conec->conectar();
			$consulta = "SELECT * FROM ciudad WHERE ID_provincia=$idProv";
			$resulSQL= $conec->ejecutarSQL($consulta);
			return $resulSQL;
		}
		public function levantarTipos(){
			$conec = new dbManager();
			$conec->conectar();
			$consulta = "SELECT * FROM tipo_hospedaje WHERE deleted=0";
			$resulSQL= $conec->ejecutarSQL($consulta);
			return $resulSQL;
		}
		public function levantarNombreTipo($id){
			$conec = new dbManager();
			$conec->conectar();
			$consulta = "SELECT Nombre FROM tipo_hospedaje WHERE ID=$id";
			$resulSQL= $conec->ejecutarSQL($consulta);
			return $resulSQL;
		}		
		public function levantarNombreProvincia($id){
			$conec = new dbManager();
			$conec->conectar();
			$consulta = "SELECT Nombre FROM provincia WHERE ID=$id";
			$resulSQL= $conec->ejecutarSQL($consulta);
			return $resulSQL;
		}	
		public function levantarNombreCiudad($id){
			$conec = new dbManager();
			$conec->conectar();
			$consulta = "SELECT nombre FROM ciudad WHERE ID=$id";
			$resulSQL= $conec->ejecutarSQL($consulta);
			return $resulSQL;
		}	
		public function publicarAnuncio($titulo, $desc, $cap, $ciudad, $tipo, $imagen){
			session_start();
			$date = date("Y-m-d H:i:s");
			$user = $_SESSION['id'];
			$conec = new dbManager();
			$conec->conectar();	
			$consulta = ("INSERT INTO anuncio(Capacidad, Titulo, Descripcion, Fecha, ID_tipo_hospedaje, ID_ciudad,ID_usuario) VALUES ('$cap', '$titulo','$desc', '$date', '$tipo', '$ciudad', '$user')");
			$res = $conec->ejecutarSQL($consulta);
			$avisoid = $conec->lastId();
			$consulta = ("INSERT INTO imagen(enlace,ID_anuncio) VALUES ('$imagen' , '$avisoid')");
			$res = $conec->ejecutarSQL($consulta);
			return($res);
		}
		public function modificarAnuncio($titulo, $desc, $cap, $ciudad, $tipo, $imagen,$idA){
			session_start();
			$date = date("Y-m-d H:i:s");
			$user = $_SESSION['id'];
			$conec = new dbManager();
			$conec->conectar();	
			$consulta = ("UPDATE anuncio SET Capacidad = '$cap', Titulo = '$titulo', Descripcion = '$desc', Fecha = '$date', ID_tipo_hospedaje = '$tipo', ID_ciudad = '$ciudad', ID_usuario = '$user' WHERE ID = '$idA'");
			$res = $conec->ejecutarSQL($consulta);
			if ($imagen){
				$consulta = ("UPDATE imagen SET enlace='$imagen' WHERE ID_anuncio='$idA'");
				$res = $conec->ejecutarSQL($consulta);
			}
			return($res);
		}		
		public function levantarAnuncios($tipo, $ciudad, $provincia, $capacidad){
			$conec = new dbManager();
			$conec->conectar();
			$consulta = "SELECT *, anuncio.ID AS anuncio_ID
						FROM 	anuncio 
								INNER JOIN imagen ON imagen.ID_anuncio=anuncio.ID
								INNER JOIN usuario ON anuncio.ID_usuario=usuario.ID
								INNER JOIN ciudad ON ciudad.ID=anuncio.ID_ciudad
						WHERE 	(1=(CASE WHEN $tipo=-1 THEN 1 ELSE 0 END) OR anuncio.ID_tipo_hospedaje=$tipo)
						AND 	(1=(CASE WHEN $provincia=-1 THEN 1 ELSE 0 END) OR ciudad.ID_provincia=$provincia)
						AND 	(1=(CASE WHEN $ciudad=-1 THEN 1 ELSE 0 END) OR ciudad.ID=$ciudad)
						AND 	(1=(CASE WHEN $capacidad=-1 THEN 1 ELSE 0 END) OR anuncio.capacidad=$capacidad)
						ORDER BY anuncio.ID DESC";
			$resultSQL = $conec->ejecutarSQL($consulta);
			return $resultSQL;
		}		
		public function levantarReservas($idAnuncio){
			$conec=new dbManager();
			$conec->conectar();
			$consulta = "SELECT fecha_inicio, fecha_fin FROM solicitud_reserva where ID_Anuncio='$idAnuncio' and estado='aceptada' order by fecha_inicio;";
			$resultSQL = $conec->ejecutarSQL($consulta);
			return $resultSQL;
		}		
		public function enviarSolicitud($inicial, $final, $idAnunc, $cantidad, $comentario){
			session_start();
			$date = date("Y-m-d H:i:s");
			$inicial=date_create($inicial);
			$inicial=date_format($inicial,"Y-m-d");
			$final=date_create($final);
			$final=date_format($final,"Y-m-d");
			$user = $_SESSION['id'];
			$conec = new dbManager();
			$conec->conectar();	
			$consulta = ("INSERT INTO solicitud_reserva(fecha_solicitud, cantidad_personas, comentario, fecha_inicio, fecha_fin, ID_anuncio, ID_usuario, Visto_huesped) VALUES ('$date', '$cantidad','$comentario', '$inicial', '$final', '$idAnunc', '$user', 1)");
			$res = $conec->ejecutarSQL($consulta);
			return($res);
		}		
		public function preguntasEnviadas($idUser){
			$conec = new dbManager();
			$conec->conectar();	
			$consulta = ("SELECT *,pregunta.ID as pregunta_ID, pregunta.fecha AS pregunta_fecha	
									FROM pregunta 
									INNER JOIN anuncio ON pregunta.ID_anuncio = anuncio.ID
									WHERE pregunta.ID_usuario='$idUser'
									ORDER BY pregunta.ID;");
			return ($conec->ejecutarSQL($consulta));
		}		
		public function marcarPregLeida($id){
			$conec = new dbManager();
			$conec->conectar();	
			$consulta = ("SELECT *, pregunta.ID AS pregunta_ID FROM anuncio
									INNER JOIN pregunta ON pregunta.ID_anuncio = anuncio.ID
									WHERE anuncio.ID='$id';");
			$serv=$conec->ejecutarSQL($consulta);
			while($row=$serv->fetch_assoc()){
				$aux=$row['pregunta_ID'];
				$consulta=("UPDATE pregunta SET Visto='1' WHERE ID='$aux'");
				$conec->ejecutarSQL($consulta);
			}
			return (1);
		}		
		public function marcarLeidasPregRec($id){
			$conec = new dbManager();
			$conec->conectar();	
			$consulta = ("SELECT *, pregunta.ID AS pregunta_ID FROM anuncio
									INNER JOIN pregunta ON pregunta.ID_anuncio = anuncio.ID
									WHERE anuncio.ID_usuario='$id';");
			$serv=$conec->ejecutarSQL($consulta);
			while($row=$serv->fetch_assoc()){
				$aux=$row['pregunta_ID'];
				$consulta=("UPDATE pregunta SET Visto='1' WHERE ID='$aux'");
				$conec->ejecutarSQL($consulta);
			}
			return (1);
		}		
		public function marcarLeidasSolicAutor($id){
			$conec = new dbManager();
			$conec->conectar();	
			$consulta = ("SELECT *, solicitud_reserva.ID AS solicitud_reserva_ID FROM anuncio
									INNER JOIN solicitud_reserva ON solicitud_reserva.ID_anuncio = anuncio.ID
									WHERE anuncio.ID_usuario='$id';");
			$serv=$conec->ejecutarSQL($consulta);
			while($row=$serv->fetch_assoc()){
				$aux=$row['solicitud_reserva_ID'];
				$consulta=("UPDATE solicitud_reserva SET Visto_autor='1' WHERE ID='$aux'");
				$conec->ejecutarSQL($consulta);
			}
			return (1);
		}
		public function marcarLeidaSolicAutor($id){
			$conec = new dbManager();
			$conec->conectar();	
			$consulta=("UPDATE solicitud_reserva SET Visto_autor='1' WHERE ID='$id'");
			$conec->ejecutarSQL($consulta);
			return (1);
		}
		public function marcarLeidasSolicHuesped($id){
			$conec = new dbManager();
			$conec->conectar();	
			$consulta = ("SELECT *	FROM solicitud_reserva
									WHERE solicitud_reserva.ID_usuario='$id';");
			$serv=$conec->ejecutarSQL($consulta);
			while($row=$serv->fetch_assoc()){
				$aux=$row['ID'];
				$consulta=("UPDATE solicitud_reserva SET Visto_huesped='1' WHERE ID='$aux'");
				$conec->ejecutarSQL($consulta);
			}
			return (1);
		}
		public function marcarLeidaSolicHuesped($id){
			$conec = new dbManager();
			$conec->conectar();	
			$consulta=("UPDATE solicitud_reserva SET Visto_huesped='1' WHERE ID='$id'");
			$conec->ejecutarSQL($consulta);
			return (1);
		}		
		public function marcarRespLeida($id){
			$conec = new dbManager();
			$conec->conectar();	
			$consulta = ("SELECT *, respuesta.ID AS respuesta_ID FROM respuesta
									INNER JOIN pregunta ON pregunta.ID = respuesta.ID_pregunta
									WHERE pregunta.ID='$id';");
			$serv=$conec->ejecutarSQL($consulta);
			while($row=$serv->fetch_assoc()){
				$aux=$row['respuesta_ID'];
				$consulta=("UPDATE respuesta SET Visto='1' WHERE ID='$aux'");
				$conec->ejecutarSQL($consulta);
			}
			return (1);
		}		
		public function marcarLeidasPregEnv($id){
			$conec = new dbManager();
			$conec->conectar();	
			$consulta = ("SELECT *, respuesta.ID AS respuesta_ID FROM respuesta
									INNER JOIN pregunta ON pregunta.ID = respuesta.ID_pregunta
									WHERE pregunta.ID_usuario='$id';");
			$serv=$conec->ejecutarSQL($consulta);
			while($row=$serv->fetch_assoc()){
				$aux=$row['respuesta_ID'];
				$consulta=("UPDATE respuesta SET Visto='1' WHERE ID='$aux'");
				$conec->ejecutarSQL($consulta);
			}
			return (1);
		}
		public function marcarLeidaCalif($id){
			$conec = new dbManager();
			$conec->conectar();	
			$consulta=("UPDATE calificacion SET Visto='1' WHERE ID='$id'");
			$conec->ejecutarSQL($consulta);
			return (1);
		}		
		public function preguntasRecibidas($idUser){
			$conec = new dbManager();
			$conec->conectar();	
			$consulta = ("SELECT *, pregunta.ID as pregunta_ID, pregunta.fecha AS pregunta_fecha
									FROM pregunta 
									INNER JOIN anuncio ON pregunta.ID_anuncio = anuncio.ID
									INNER JOIN usuario ON anuncio.ID_usuario = usuario.ID
									WHERE anuncio.ID_usuario='$idUser'
									ORDER BY pregunta.ID;");
			return ($conec->ejecutarSQL($consulta));
		}		
		public function solicitudesEnviadas($idUser){
			$conec = new dbManager();
			$conec->conectar();	
			$consulta = ("SELECT *, solicitud_reserva.ID as solicitud_ID, anuncio.ID_usuario as anuncio_user
									FROM solicitud_reserva 
									INNER JOIN anuncio ON solicitud_reserva.ID_anuncio = anuncio.ID
									WHERE solicitud_reserva.ID_usuario='$idUser' AND solicitud_reserva.ID NOT IN (SELECT ID_solicitud FROM reserva)
									ORDER BY solicitud_reserva.estado ASC, solicitud_reserva.ID DESC;");
			return ($conec->ejecutarSQL($consulta));
		}		
		public function solicitudesRecibidas($idUser){
			$conec = new dbManager();
			$conec->conectar();	
			$consulta = ("SELECT *,	solicitud_reserva.ID as solicitud_ID, solicitud_reserva.ID_usuario as solicitud_user, anuncio.ID_usuario as anuncio_user
									FROM solicitud_reserva 
									INNER JOIN anuncio ON solicitud_reserva.ID_anuncio = anuncio.ID
									INNER JOIN usuario ON solicitud_reserva.ID_usuario = usuario.ID
									WHERE anuncio.ID_usuario='$idUser' 
									AND solicitud_reserva.ID NOT IN (SELECT ID_solicitud FROM reserva)
									ORDER BY solicitud_reserva.estado ASC, solicitud_reserva.ID DESC;");
			return ($conec->ejecutarSQL($consulta));
		}		
		public function reservasEnviadas($idUser){
			$conec = new dbManager();
			$conec->conectar();	
			$consulta = ("SELECT *, solicitud_reserva.ID as solicitud_ID, anuncio.ID_usuario as anuncio_user
									FROM solicitud_reserva 
									INNER JOIN anuncio ON solicitud_reserva.ID_anuncio = anuncio.ID
									WHERE solicitud_reserva.ID_usuario='$idUser'
									AND solicitud_reserva.ID IN (SELECT ID_solicitud FROM reserva)
									ORDER BY solicitud_reserva.estado ASC, solicitud_reserva.fecha_fin DESC;");
			return ($conec->ejecutarSQL($consulta));
		}		
		public function reservasRecibidas($idUser){
			$conec = new dbManager();
			$conec->conectar();	
			$consulta = ("SELECT *,	solicitud_reserva.ID as solicitud_ID, solicitud_reserva.ID_usuario as solicitud_user, anuncio.ID_usuario as anuncio_user
									FROM solicitud_reserva 
									INNER JOIN anuncio ON solicitud_reserva.ID_anuncio = anuncio.ID
									INNER JOIN usuario ON solicitud_reserva.ID_usuario = usuario.ID
									WHERE anuncio.ID_usuario='$idUser'
									AND solicitud_reserva.ID IN (SELECT ID_solicitud FROM reserva)
									ORDER BY solicitud_reserva.estado ASC, solicitud_reserva.fecha_fin DESC;");
			return ($conec->ejecutarSQL($consulta));
		}		
		public function solicitudesTodas(){
			$conec = new dbManager();
			$conec->conectar();	
			$consulta = ("SELECT *	FROM solicitud_reserva");
			return ($conec->ejecutarSQL($consulta));
		}		
		public function levantarSolicitud($id){
			$conec = new dbManager();
			$conec->conectar();	
			$consulta = ("SELECT * FROM solicitud_reserva WHERE ID='$id';");
			return ($conec->ejecutarSQL($consulta));
		}		
		public function levantarSolicitudesFecha($inicial, $final, $idS, $idA){
			$conec = new dbManager();
			$conec->conectar();	
			$consulta = ("SELECT *, solicitud_reserva.ID as solicitud_ID, solicitud_reserva.ID_usuario as solicitud_user
									FROM solicitud_reserva 
									INNER JOIN anuncio ON solicitud_reserva.ID_anuncio = anuncio.ID
									INNER JOIN usuario ON solicitud_reserva.ID_usuario = usuario.ID
									WHERE 	((estado='pendiente')
									AND 	(solicitud_reserva.ID_anuncio=$idA)
									AND 	(((fecha_inicio>='$inicial' AND fecha_inicio<='$final') OR (fecha_fin>='$inicial' AND fecha_fin<='$final'))
									OR		(('$inicial'>=fecha_inicio AND '$inicial'<=fecha_fin) OR ('$final'>=fecha_inicio AND '$final'<=fecha_fin)))
									AND 	(solicitud_reserva.ID!=$idS));");
			return ($conec->ejecutarSQL($consulta));
		}		
		public function cancelarSolicitudHuesped($id){
			$conec = new dbManager();
			$conec->conectar();	
			$consulta = ("UPDATE solicitud_reserva SET estado='cancelada', Visto_autor='0', Visto_huesped='1' WHERE ID='$id'");
			return ($conec->ejecutarSQL($consulta));
		}		
		public function cancelarSolicitudAutor($id){
			$conec = new dbManager();
			$conec->conectar();	
			$consulta = ("UPDATE solicitud_reserva SET estado='cancelada', Visto_autor='1', Visto_huesped='0' WHERE ID='$id'");
			$conec->ejecutarSQL($consulta);
			$consulta = ("DELETE FROM reserva WHERE ID_solicitud='$id'");
			return ($conec->ejecutarSQL($consulta));
		}
		public function rechazarSolicitud($id){
			$conec = new dbManager();
			$conec->conectar();	
			$consulta = ("UPDATE solicitud_reserva SET estado='rechazada', Visto_autor='1', Visto_huesped='0' WHERE ID='$id'");
			return ($conec->ejecutarSQL($consulta));
		}
		public function aceptarSolicitud($id){
			$conec = new dbManager();
			$conec->conectar();	
			$consulta = ("UPDATE solicitud_reserva SET estado='aceptada', Visto_autor='1', Visto_huesped='0' WHERE ID='$id'");
			$conec->ejecutarSQL($consulta);
			$date = date("Y-m-d");
			$consulta = ("INSERT INTO reserva(fecha_aceptacion,ID_solicitud) VALUES ('$date' , '$id')");
			return ($conec->ejecutarSQL($consulta));
		}
		public function solicitudEstadoActiva($id){
			$conec = new dbManager();
			$conec->conectar();	
			$consulta = ("UPDATE solicitud_reserva SET estado='activa' WHERE ID='$id'");
			return ($conec->ejecutarSQL($consulta));
		}
		public function solicitudEstadoFinalizada($id){
			$conec = new dbManager();
			$conec->conectar();	
			$consulta = ("UPDATE solicitud_reserva SET estado='finalizada' WHERE ID='$id'");
			return ($conec->ejecutarSQL($consulta));
		}
		public function notificarPregunta($id){
			$conec = new dbManager();
			$conec->conectar();
			$consulta = "SELECT *, anuncio.ID AS anuncio_ID
									FROM anuncio 
									INNER JOIN pregunta on anuncio.ID=pregunta.ID_anuncio
									WHERE anuncio.ID_usuario=$id
									AND pregunta.visto=0";
			$resultSQL = $conec->ejecutarSQL($consulta);
			return $resultSQL;
		}		
		public function notificarRespuesta($id){
			$conec = new dbManager();
			$conec->conectar();
			$consulta = "SELECT *,	anuncio.ID AS anuncio_ID
									FROM pregunta 
									INNER JOIN respuesta on pregunta.ID=respuesta.ID_pregunta
									INNER JOIN anuncio on pregunta.ID_anuncio=anuncio.ID
									WHERE pregunta.ID_usuario=$id
									AND respuesta.visto=0";
			$resultSQL = $conec->ejecutarSQL($consulta);
			return $resultSQL;
		}		
		public function notificarSolicitud($id){
			$conec = new dbManager();
			$conec->conectar();
			$consulta = "SELECT *, solicitud_reserva.ID as  solicitud_ID	
									FROM solicitud_reserva
									INNER JOIN anuncio on anuncio.ID=solicitud_reserva.ID_anuncio
									WHERE anuncio.ID_usuario=$id
									AND solicitud_reserva.estado='pendiente'
									AND solicitud_reserva.Visto_autor=0";
			$resultSQL = $conec->ejecutarSQL($consulta);
			return $resultSQL;
		}
		public function notificarSolicitudCancelada($id){
			$conec = new dbManager();
			$conec->conectar();
			$consulta = "SELECT *, solicitud_reserva.ID as solicitud_ID 	
									FROM solicitud_reserva
									INNER JOIN anuncio on anuncio.ID=solicitud_reserva.ID_anuncio
									WHERE anuncio.ID_usuario=$id
									AND solicitud_reserva.estado='cancelada'
									AND solicitud_reserva.ID NOT IN (SELECT ID_solicitud FROM reserva)
									AND solicitud_reserva.Visto_autor=0";
			$resultSQL = $conec->ejecutarSQL($consulta);
			return $resultSQL;
		}
		public function notificarReservaCanceladaH($id){
			$conec = new dbManager();
			$conec->conectar();
			$consulta = "SELECT * 	FROM solicitud_reserva
									INNER JOIN anuncio on anuncio.ID=solicitud_reserva.ID_anuncio
									WHERE anuncio.ID_usuario=$id
									AND solicitud_reserva.estado='cancelada'
									AND solicitud_reserva.ID IN (SELECT ID_solicitud FROM reserva)
									AND solicitud_reserva.Visto_autor=0";
			$resultSQL = $conec->ejecutarSQL($consulta);
			return $resultSQL;
		}
		public function notificarCalificacionAnuncio($id){
			$conec = new dbManager();
			$conec->conectar();
			$consulta = "SELECT *, anuncio.ID as anuncio_ID
									FROM calificacion
									INNER JOIN reserva on reserva.ID_calificacion_visitante=calificacion.ID
									INNER JOIN solicitud_reserva on reserva.ID_solicitud=solicitud_reserva.ID
									INNER JOIN anuncio on anuncio.ID=solicitud_reserva.ID_anuncio
									WHERE anuncio.ID_usuario=$id
									AND calificacion.Visto=0";
			$resultSQL = $conec->ejecutarSQL($consulta);
			return $resultSQL;
		}
		public function notificarCalificacionUser($id){
			$conec = new dbManager();
			$conec->conectar();
			$consulta = "SELECT * 	FROM calificacion
									INNER JOIN reserva on reserva.ID_calificacion_dueño=calificacion.ID
									INNER JOIN solicitud_reserva on reserva.ID_solicitud=solicitud_reserva.ID
									WHERE solicitud_reserva.ID_usuario=$id
									AND calificacion.Visto=0";
			$resultSQL = $conec->ejecutarSQL($consulta);
			return $resultSQL;
		}
		public function notificarCalificacionPendienteAnuncio($id){
			$conec = new dbManager();
			$conec->conectar();
			$consulta = "SELECT *, 	solicitud_reserva.ID as solicitud_ID
									FROM reserva
									INNER JOIN solicitud_reserva on reserva.ID_solicitud=solicitud_reserva.ID
									WHERE solicitud_reserva.ID_usuario=$id 
									AND solicitud_reserva.estado='Finalizada'
									AND (reserva.ID_calificacion_visitante IS NULL)";
			$resultSQL = $conec->ejecutarSQL($consulta);
			return $resultSQL;
		}
		public function notificarCalificacionPendienteUser($id){
			$conec = new dbManager();
			$conec->conectar();
			$consulta = "SELECT *, 	solicitud_reserva.ID as solicitud_ID
									FROM reserva
									INNER JOIN solicitud_reserva on reserva.ID_solicitud=solicitud_reserva.ID
									INNER JOIN anuncio on anuncio.ID=solicitud_reserva.ID_anuncio
									WHERE anuncio.ID_usuario=$id 
									AND solicitud_reserva.estado='Finalizada'
									AND (reserva.ID_calificacion_dueño IS NULL)";
			$resultSQL = $conec->ejecutarSQL($consulta);
			return $resultSQL;
		}
		public function notificarRespuestaSolicitud($id){
			$conec = new dbManager();
			$conec->conectar();
			$consulta = "SELECT * 	FROM solicitud_reserva
									WHERE solicitud_reserva.ID_usuario=$id
									AND solicitud_reserva.Visto_huesped=0";
			$resultSQL = $conec->ejecutarSQL($consulta);
			return $resultSQL;
		}
		public function notificarReservaCanceladaA($id){
			$conec = new dbManager();
			$conec->conectar();
			$consulta = "SELECT * 	FROM solicitud_reserva
									WHERE solicitud_reserva.ID_usuario=$id
									AND solicitud_reserva.Visto_autor=0
									AND solicitud_reserva.ID IN (SELECT ID_solicitud FROM reserva)";
			$resultSQL = $conec->ejecutarSQL($consulta);
			return $resultSQL;
		}
		public function levantarAnuncio($idAnuncio){
			$conec = new dbManager();
			$conec->conectar();
			$consulta = "	SELECT *, 	usuario.Username AS usuario_Username,
										usuario.ID AS usuario_ID,
										tipo_hospedaje.Nombre AS tipo_hospedaje_Nombre,
										ciudad.nombre AS ciudad_nombre,
										provincia.Nombre AS provincia_Nombre,
										provincia.ID AS provincia_ID
							FROM 	anuncio 	
									INNER JOIN tipo_hospedaje ON anuncio.ID_tipo_hospedaje=tipo_hospedaje.ID 
									INNER JOIN usuario ON anuncio.ID_usuario=usuario.ID 
									INNER JOIN ciudad ON anuncio.ID_ciudad=ciudad.ID
									INNER JOIN provincia ON ciudad.ID_provincia=provincia.ID
									INNER JOIN imagen ON imagen.ID_anuncio=anuncio.ID
							WHERE anuncio.ID=$idAnuncio";
			$resultSQL = $conec->ejecutarSQL($consulta);
			return $resultSQL;
		}
		public function levantarUsuario($idUsuario){
			$conec = new dbManager();
			$conec->conectar();
			$consulta = "SELECT * FROM usuario WHERE ID=$idUsuario";
			$resultSQL = $conec->ejecutarSQL($consulta);
			return $resultSQL;
		}		
		public function levantarImagen($id){
			$conec = new dbManager();
			$conec->conectar();
			$consulta = "SELECT * FROM imagen WHERE ID=$id";
			$resultSQL = $conec->ejecutarSQL($consulta);
		}		
		public function levantarAnuncioDeUsuario($idUser){
			$conec = new dbManager();
			$conec->conectar();
			$consulta = "SELECT *, anuncio.ID AS anuncio_ID
						FROM 	anuncio 
								INNER JOIN imagen ON imagen.ID_anuncio=anuncio.ID
								INNER JOIN usuario ON anuncio.ID_usuario=usuario.ID
						WHERE ID_usuario=$idUser
						ORDER BY anuncio.ID DESC";
			$resultSQL = $conec->ejecutarSQL($consulta);
			return $resultSQL;
		}
		public function publicarPregunta($idAnun, $idUser, $pregunta){
			$date = date("Y-m-d H:i:s");
			$conec = new dbManager();
			$conec->conectar();
			$consulta = ("INSERT INTO pregunta(fecha, texto, ID_anuncio, ID_usuario) VALUES('$date', '$pregunta', '$idAnun', '$idUser')");
			$resultSQL = $conec->ejecutarSQL($consulta);
			return $resultSQL;
		}		
		public function levantarPreguntasAnuncio($idAnuncio){
 			$conec = new dbManager();
 			$conec->conectar(); 
			$consulta = "SELECT *, pregunta.ID AS pregunta_ID, anuncio.ID_usuario AS autor_ID, pregunta.ID_usuario AS pregunta_ID_usuario, pregunta.fecha AS pregunta_fecha
 							FROM pregunta 
 								INNER JOIN usuario ON pregunta.ID_usuario=usuario.ID 
 								INNER JOIN anuncio ON pregunta.ID_anuncio=anuncio.ID
							WHERE ID_anuncio=$idAnuncio";
 			$resultSQL = $conec->ejecutarSQL($consulta);
 			return $resultSQL;
 		}
		public function publicarRespuesta($idPregunta, $respuesta){
			$date = date("Y-m-d H:i:s");
			$conec = new dbManager();
			$conec->conectar();
			$consulta = ("INSERT INTO respuesta(fecha, texto, ID_pregunta) VALUES('$date', '$respuesta', '$idPregunta')");	
			$resultSQL = $conec->ejecutarSQL($consulta);
			return $resultSQL;
		}
		public function levantarRespuestaAnuncio($idPregunta){
			$conec = new dbManager();
			$conec->conectar();
			$consulta = "SELECT *, respuesta.ID AS respuesta_ID, respuesta.texto AS respuesta_texto, respuesta.fecha AS respuesta_fecha
							FROM respuesta 
								INNER JOIN pregunta ON respuesta.ID_pregunta=pregunta.ID
								INNER JOIN anuncio ON pregunta.ID_anuncio=anuncio.ID
								INNER JOIN usuario ON anuncio.ID_usuario=usuario.ID 
							WHERE ID_pregunta=$idPregunta";
			$resultSQL = $conec->ejecutarSQL($consulta);
			return $resultSQL;
		}		
		public function darDeBajaAnuncio($idAnuncio){
			$conec = new dbManager();
			$conec->conectar();
			$consulta = ("UPDATE anuncio SET activo='0' WHERE ID='$idAnuncio'");
			$resultSQL = $conec->ejecutarSQL($consulta);
			return $resultSQL;
		}
		public function activarAnuncio($idAnuncio){
			$conec = new dbManager();
			$conec->conectar();
			$consulta = ("UPDATE anuncio SET activo='1' WHERE ID='$idAnuncio'");
			$resultSQL = $conec->ejecutarSQL($consulta);
			return $resultSQL;
		}
		public function levantarReserva($idSolicitud){
			$conec=new dbManager();
			$conec->conectar();
			$consulta = "SELECT * FROM reserva where ID_solicitud='$idSolicitud'";
			$resultSQL = $conec->ejecutarSQL($consulta);
			return $resultSQL;
		}		
		public function levantarCalifAnuncio($idReserva){
			$conec=new dbManager();
			$conec->conectar();
			$consulta = "SELECT *, calificacion.ID AS calificacion_ID, calificacion.comentario AS calificacion_comentario
							FROM calificacion 
							INNER JOIN reserva ON calificacion.ID_reserva=reserva.ID
							WHERE ID_reserva=$idReserva";
			$resultSQL = $conec->ejecutarSQL($consulta);
			return $resultSQL;
		}
		public function isCalificadoHospedaje($idSolicitud){
			$conec = new dbManager();
			$conec->conectar();
			$consulta = "SELECT ID_calificacion_visitante FROM reserva WHERE ID_solicitud = '$idSolicitud'";
			$res = $conec->ejecutarSQL($consulta);
			$aux = $res->fetch_assoc();
			return ($aux['ID_calificacion_visitante'] != NULL);
		}
		public function isCalificadoHuesped($idSolicitud){
			$conec = new dbManager();
			$conec->conectar();
			$consulta = "SELECT ID_calificacion_dueño FROM reserva WHERE ID_solicitud = '$idSolicitud'";
			$res = $conec->ejecutarSQL($consulta);
			$aux = $res->fetch_assoc();
			return ($aux['ID_calificacion_dueño'] != NULL);
		}
		public function calificarHospedaje($comment,$puntaje,$res){
			$conec = new dbManager();
			$conec->conectar();
			$consulta = "SELECT ID FROM reserva WHERE ID_solicitud = '$res'";
			$idreserva = $conec->ejecutarSQL($consulta);
			$row = $idreserva->fetch_assoc();
			$consulta = "INSERT INTO calificacion(comentario, puntaje, Visto) VALUES ('$comment', '$puntaje', '0')";
			$respuesta = $conec->ejecutarSQL($consulta);
			$idcalificacion = $conec->lastId();
			$consulta = "UPDATE reserva SET ID_calificacion_visitante = '$idcalificacion' WHERE ID_solicitud = '$res'";
			$respuesta = $conec->ejecutarSQL($consulta);
		}
		public function calificarHuesped($comment,$puntaje,$res){
			$conec = new dbManager();
			$conec->conectar();
			$consulta = "SELECT ID FROM reserva WHERE ID_solicitud = '$res'";
			$idreserva = $conec->ejecutarSQL($consulta);
			$row = $idreserva->fetch_assoc();
			$consulta = "INSERT INTO calificacion(comentario, puntaje, Visto) VALUES ('$comment', '$puntaje', '0')";
			$respuesta = $conec->ejecutarSQL($consulta);
			$idcalificacion = $conec->lastId();
			$consulta = "UPDATE reserva SET ID_calificacion_dueño = '$idcalificacion' WHERE ID_solicitud = '$res'";
			$respuesta = $conec->ejecutarSQL($consulta);
		}
		public function levantarCalificacionesAnuncio($idAnuncio){
			$conec = new dbManager();
			$conec->conectar();
			$consulta = "SELECT *, calificacion.ID as calificacion_ID
										FROM anuncio 
										INNER JOIN solicitud_reserva ON solicitud_reserva.ID_anuncio=anuncio.ID
										INNER JOIN reserva ON reserva.ID_solicitud= solicitud_reserva.ID
										INNER JOIN calificacion ON calificacion.ID = reserva.ID_calificacion_visitante
							WHERE anuncio.ID = '$idAnuncio'";
			return $conec->ejecutarSQL($consulta);
		}
		public function levantarCalificacionesUsuario($idUsuario){
			$conec = new dbManager();
			$conec->conectar();
			$consulta = "SELECT *, calificacion.ID as calificacion_ID
										FROM solicitud_reserva 
										INNER JOIN anuncio ON anuncio.ID=solicitud_reserva.ID_anuncio
										INNER JOIN usuario ON anuncio.ID_usuario=usuario.ID
										INNER JOIN reserva ON reserva.ID_solicitud=solicitud_reserva.ID
										INNER JOIN calificacion ON calificacion.ID=reserva.ID_calificacion_dueño
							WHERE solicitud_reserva.ID_usuario=$idUsuario";
			return $conec->ejecutarSQL($consulta);
		}
		public function levantarPuntajePromedioAnuncio($idAnuncio){
			$conec = new dbManager();
			$conec->conectar();
			$consulta = "SELECT * FROM anuncio 
										INNER JOIN solicitud_reserva ON solicitud_reserva.ID_anuncio=anuncio.ID
										INNER JOIN reserva ON reserva.ID_solicitud= solicitud_reserva.ID
										INNER JOIN calificacion ON calificacion.ID = reserva.ID_calificacion_visitante
							WHERE anuncio.ID = '$idAnuncio'";
			$anuncios = $conec->ejecutarSQL($consulta);
			$cantComent = 0;
			$puntaje = 0;
			if ($anuncios->num_rows>0){
				while ($row = $anuncios->fetch_assoc()) {
				 	$cantComent = $cantComent + 1;
				 	$puntaje = $puntaje + $row['puntaje'];
				 }
				 return($puntaje/$cantComent);
			 }else{
			 	return "--";
			 }	 		
		}
		public function levantarPuntajePromedioUsuario($idUsuario){
			$conec = new dbManager();
			$conec->conectar();
			$consulta = "SELECT * FROM usuario 
										INNER JOIN solicitud_reserva ON usuario.ID=solicitud_reserva.ID_usuario
										INNER JOIN reserva ON reserva.ID_solicitud= solicitud_reserva.ID
										INNER JOIN calificacion ON calificacion.ID = reserva.ID_calificacion_dueño
							WHERE usuario.ID = $idUsuario";
			$anuncios = $conec->ejecutarSQL($consulta);
			$cantComent = 0;
			$puntaje = 0;
			if ($anuncios->num_rows>0){
				while ($row = $anuncios->fetch_assoc()) {
				 	$cantComent = $cantComent + 1;
				 	$puntaje = $puntaje + $row['puntaje'];
				 }
				 return($puntaje/$cantComent);
			 }else{
			 	return "--";
			 }	 		
		}
		public function levantarUsuarioCalificador($idCalificacion){
			$conec = new dbManager();
			$conec->conectar();
			$consulta = "SELECT * FROM calificacion	
										INNER JOIN reserva ON reserva.ID_calificacion_visitante = calificacion.ID
										INNER JOIN solicitud_reserva ON reserva.ID_solicitud = solicitud_reserva.ID
										INNER JOIN usuario ON usuario.ID = solicitud_reserva.ID_usuario
							WHERE calificacion.ID = '$idCalificacion'";
			return $conec->ejecutarSQL($consulta);

		}
		public function levantarCalificadoresDeUnUsuario($idCalificacion){
			$conec = new dbManager();
			$conec->conectar();
			$consulta = "SELECT * FROM calificacion	
										INNER JOIN reserva ON reserva.ID_calificacion_dueño = calificacion.ID
										INNER JOIN solicitud_reserva ON reserva.ID_solicitud = solicitud_reserva.ID
										INNER JOIN anuncio ON solicitud_reserva.ID_anuncio = anuncio.ID
										INNER JOIN usuario ON usuario.ID = anuncio.ID_usuario
							WHERE calificacion.ID = '$idCalificacion'";
			return $conec->ejecutarSQL($consulta);
		}
	}
?>