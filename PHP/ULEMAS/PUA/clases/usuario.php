<?php

class Usuario {
	
	/* variables de usuario */
	var $nick;
	var $nombreu;
	var $email;
	
	/*La jerarqu�a de susuarios es la siguiente:
		admin: 0
		Administrador: 2
		Usuarios registrados: 4
		Guests/Invitados: 6
	*/
	var $tipoUsuario;
	var $estado;

	/* n�mero de error y texto error */
	var $Errno = 0;
	var $Error = "";
	
	/* M�todo Constructor: Cada vez que creemos una variable
	de esta clase, se ejecutar� esta funci�n */
	function Usuario($nick = "", $pass = "", $nom = "", $e = "", $tipoUsu = "",$est = "") {
		global $privilegios;
		if($_SESSION['login']) { //Usuario logueado
			$this->nick = $_SESSION['nick'];
			$this->nombreu = $_SESSION['nombreu'];
			$this->email = $_SESSION['email'];
			$this->tipoUsuario = $_SESSION['tipoUsuario'];
			$this->estado = $_SESSION['estado'];
		} else { //Usuario invitado
			$this->nick = "";
			$this->password = "";
			$this->nombreu = "Invitado";
			$this->email = "";
			$this->tipoUsuario = $privilegios['guest'];
			$this->estado = "";
			if($_SESSION['nombreu']=="" && $_SESSION['tipoUsuario']=="") {
				$_SESSION['nombreu']=$this->nombreu;
				$_SESSION['tipoUsuario']=$this->tipoUsuario;
			}			
		}		
	}

	/* Devuelve el nick */
	function getNick() {
		return ($this->nick);
	}
	
	/* Establece el nuevo login que se pasa por par�metro */
	function setNick($n) {
		$this->nick=$n;
	}
	
	/* Establece el nuevo password que se pasa por par�metro */
	function setPassword($passOld,$passNew) {
		$this->password=$pass;
	}
	
	/* Devuelve el nombreu */
	function getNombre() {
		return ($this->nombreu);
	}
	
	/* Establece el nuevo nombreu que se pasa por par�metro */
	function setNombre($nom) {
		$this->nombreu=$nom;
	}
	
	/* Devuelve el email */
	function getEmail() {
		return ($this->email);
	}
	
	/* Establece el nuevo email que se pasa por par�metro */
	function setEmail($e) {
		$this->email=$e;
	}
	
	/* Devuelve el tipoUsuario */
	function getTipoUsuario() {
		return ($this->tipoUsuario);
	}
	
	/* Establece el nuevo tipoUsuario que se pasa por par�metro */
	function setTipoUsuario($t) {
		$this->tipoUsuario=$t;
	}
	
	/* Devuelve el estado */
	function getEstado() {
		return ($this->estado);
	}
	
	/* Establece el nuevo estado que se pasa por par�metro */
	function setEstado($e) {
		$this->estado=$e;
	}
	
	function setSessionRecordar($valor) {
		$_SESSION['recordar']=true;
	}
	
	function mostrarLogin() {
	global $privilegios;
	?>
	<div id="uses">
		<?php echo _USR_SESSION_INTRO; ?> <a href="<?php echo $_SESSION['Config_diractual'];?>usuarios/perfil_usuario.php">
		<?php	switch($this->tipoUsuario) {					
					case $privilegios['admin']: echo _USR_SESSION_ADMIN; break;
					case $privilegios['researcher']: echo _USR_SESSION_RESEARCHER; break;
					case $privilegios['guest']: echo _USR_SESSION_GUEST; break;
					default: ;
				}
		?></a> | 
		<?php	if($this->tipoUsuario==$privilegios['guest']) { ?>
			<img src="<?php echo $_SESSION["Config_diractual"]; ?>img/flecha.png" alt="flecha" width="9" height="9" /><a href="<?php echo $_SESSION['Config_diractual'];?>usuarios/login.php"><?php echo _USR_SESSION_INIT; ?></a>	
		<?php } else {?>
		<img src="<?php echo $_SESSION["Config_diractual"]; ?>img/flechax.png" alt="flecha" width="9" height="9" /><a href="<?php echo $_SESSION["Config_diractual"];?>usuarios/logout.php"><?php echo _USR_SESSION_CLOSE; ?></a>
		<?php } ?>
	</div>
	<?php
	}
	
	function getTipoEscrito() {
		global $privilegios;
		switch($this->tipoUsuario) {
			case $privilegios['admin']: echo _USR_SESSION_ADMIN; break;
			case $privilegios['researcher']: echo _USR_SESSION_RESEARCHER; break; //_USR_SESSION_REGUSER
			case $privilegios['guest']: echo _USR_SESSION_GUEST; break;
			default: $resultado = "";
		}
		return $resultado;
	}
	
	function printEstrellas() {
		global $privilegios;
		$simbolo = "<img src='". $_SESSION["Config_diractual"] . "img/comunes/nivel_on.png' alt='&middot;' width='18' height='18' />";
		$simbolo2 = "<img src='". $_SESSION["Config_diractual"] . "img/comunes/nivel_off.png' alt='&middot;' width='18' height='18' />";
		$marcados = 0;
		$n_marcados = 3;
		switch($this->tipoUsuario) {
			case $privilegios['admin']:
				$marcados = 3;
				$n_marcados = 0;
				break;
			case $privilegios['researcher']: 
				$marcados = 2;
				$n_marcados = 1;
				break;
			case $privilegios['guest']:
				$marcados = 1;
				$n_marcados = 2;
				break;
			/*case $privilegios['guest']:
				$marcados = 1;
				$n_marcados = 4;
				break;*/
			default: break;
		}
		?>
		<span class="marcado">
<?php for($i=0;$i<$marcados;$i++) echo $simbolo; ?><?php if($n_marcados) { ?><?php for($i=0;$i<$n_marcados;$i++) echo $simbolo2; ?>
		<?php } ?>
		</span>
		<?php

	}
	
	function hashPass($pass,$salt) {
		return md5($salt.$pass);
	}
	
	function login($nick,$pass,$rec,$salt='') { //funcion para loguearse
		//aqu� hay que controlar magic_quotes
		$hashedPass=$this->hashPass($pass,$salt);
		global $privilegios;
		$conexion = new DB_mysql();
		$conexion->conectar();
		$result=$conexion->consulta("SELECT * FROM usuarios WHERE nick='$nick' AND password='$hashedPass' LIMIT 1");
		$usuario=$conexion->filasconsulta();
		
		if (!$usuario) return 0; // No se ha encontrado en la BD.
		
		if($result) {
			if($usuario['estado']=='activo' || ($usuario['tipoUsuario']=='admin' && $usuario['estado']=='inactivo')) {
				//Actualizamos las variables de SESION
				$_SESSION['login']=true;
				$_SESSION['nick'] = $usuario["nick"];
				$_SESSION['nombreu'] = $usuario["nombre"];
				$_SESSION['email'] = $usuario["email"];
				$_SESSION['timestamp']=microtime(true);
				if($rec=="1") {
					$_SESSION['recordar'] = true;
				} else {
					$_SESSION['recordar'] = false;
				}
				switch($usuario["tipoUsuario"]) {					
					case 'admin': $tp=$privilegios['admin']; break;
					case 'investigador': $tp=$privilegios['researcher']; break;
					case 'invitado': $tp=$privilegios['guest']; break;
					default: ;
				}
				$_SESSION['tipoUsuario'] = $tp;
				$_SESSION['estado'] = $usuario["estado"];
							
				$this->nick = $usuario["nick"];
				$this->nombreu = $usuario["nombreu"];
				$this->email = $usuario["email"];
				$this->tipoUsuario = $tp;
				$this->estado = $usuario["estado"];
				$respuesta = 2;
			} else {
				$respuesta = 1;
			}
		} else {
			$respuesta = 0;
		}
		return $respuesta;
	}
	
	function logout() { //esta funcion cierra la sesi�n de un usuario
		$_SESSION['tipoUsuario']=$privilegios['nolog'];
		//if($_SESSION['recordar']=="no") {
			$_SESSION['nombreu']="Invitado";
			$_SESSION['nick']="invitado";
			$_SESSION['email']="";
			$_SESSION['estado']="";
			$_SESSION['login']=false;
			unset($_SESSION['recordar']);
			session_destroy();
			/*
		} else {
			$_COOKIE['login'] = $_SESSION['login'];
			$_COOKIE['nick'] = $_SESSION['nick'];
			$_COOKIE['nombreu'] = $_SESSION['nombreu'];
			$_COOKIE['email'] = $_SESSION['email'];
			$_COOKIE['recordar'] = "si";
			$_COOKIE['estado'] = $_SESSION['estado'];
			session_destroy();
		}
		*/
	}
	
	function busquedasFavoritas() {
		$nick = $_SESSION['nick'];
		if($nick!="") {
			$conexion = new DB_mysql();
			$conexion->conectar();
			$result=$conexion->consulta("SELECT * FROM busqueda WHERE nick='$nick' ORDER BY fecha LIMIT 4");
			for($i=1; $row=mysql_fetch_row($result); $i++) {
				?><li><a href="<?php echo $row[4];?>"><?php echo $row[3];?></a></li><?php
			}	
		}
	}
		

} //fin de la Clase Usuario
?>
