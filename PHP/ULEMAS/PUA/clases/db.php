<?php

class DB_mysql {

	/* variables de conexi�n */
	var $baseDatos;
	var $servidor;
	var $usuario;
	var $clave;
	var $numRegistros = 0;

	/* identificador de conexi�n y consulta */
	var $conexionId = 0;
	var $consultaId = 0; 

	/* n�mero de error y texto error */
	var $Errno = 0;
	var $Error = "";
	
	/* M�todo Constructor: Cada vez que creemos una variable
	de esta clase, se ejecutar� esta funci�n */

	function DB_mysql() {
			
			$this->baseDatos = _DB_NAME;
			$this->servidor = _DB_HOST;
			$this->usuario = _DB_USER;
			$this->clave = _DB_PASS;
			
	}

 	/*Conexi�n a la base de datos*/

	function conectar(){
		// Conectamos al servidor
		$this->conexionId = mysql_connect($this->servidor, $this->usuario, $this->clave);
		if (!$this->conexionId) {
			$this->Error = "Ha fallado la conexi�n.";
			return 0;
		}
 		//seleccionamos la base de datos
		if (!@mysql_select_db($this->baseDatos, $this->conexionId)) {
			$this->Error = "Imposible abrir ".$this->baseDatos ;
			return 0;
		}
		else {
			// Si hemos podido conectar establecemos una contexto utf8 para realizar b�squedas accent-insensitive sobre utf8_general_ci
			@mysql_query("SET NAMES 'utf8'", $this->conexionId); 
			//@mysql_query("SET CHARACTER SET 'utf-8'", $this->conexionId); 
		}
		/* Si hemos tenido �xito conectando devuelve 
		el identificador de la conexi�n, sino devuelve 0 */
		return $this->conexionId;
	}
 
	/* Ejecuta un consulta */
	function consulta($sql = ""){
		if ($sql == "") {
			$this->Error = "No ha especificado una consulta SQL";
			return 0;
		}
		
		if(defined('_DEBUG') && intval(_DEBUG)>0) {
			if(defined('_DEBUG_SQL_FILE') && strlen(_DEBUG_SQL_FILE)>0) {
				$myFile = _DEBUG_SQL_FILE;
				$fh = fopen($myFile, 'a+');
				$date = date('d/m/Y H:i:s', time());
				$stringData = $date.": ".$sql."\n";
				fwrite($fh, $stringData);
				fclose($fh);
			}
		}
		
	 	//ejecutamos la consulta
		$c = $this->consultaId = @mysql_query($sql, $this->conexionId);
		if (!$this->consultaId) {
			$this->Errno = mysql_errno();
			$this->Error = mysql_error();
		}
		/* Si hemos tenido �xito en la consulta devuelve 
		el identificador de la conexi�n, si no devuelve 0 */
		return $this->consultaId;
	}

 
	/* Devuelve el n�mero de campos de una consulta */

	function numcampos() {
		return mysql_num_fields($this->consultaId);
	}

 	/* Devuelve el n�mero de registros de una consulta */
	function numregistros(){
		return mysql_num_rows($this->consultaId);
	}
	
	/*
	 function filasconsulta(){
		return mysqli_result::fetch_assoc($this->consultaId);
	}*/
	
	//Deprecated mejor usar la de arriba pero estudiarlo
	function filasconsulta(){
		return mysql_fetch_assoc($this->consultaId);
	}

 	/* Devuelve el nombre de un campo de una consulta */
	function nombrecampo($numcampo) {
		return mysql_field_name($this->consultaId, $numcampo);
	}
	
	function ultimoID() {
		return mysql_insert_id();
	}

 	
	function consultaPaginada($sql, $n) { 
		$limite = "";
		if(!tieneAcceso($privilegios['admin'])) {//si el usuario es INVITADO s�lo ver� 10 registros de la BBDD
			$limite = " LIMIT 10";
		}
		
		$this->conectar();
		$result = $this->consulta($sql.$limite);
		if(isset($_GET['page'])) {
			$page = intval($_GET['page']);
		}
		else $page = 1;

		if(($page-1)*$n>$this->numregistros()) {
			$page = 1;
		}
		if(($n!=0)&&$this->numregistros()) {
			if(isset($_GET['numReg'])) {
				if($this->numregistros()>$_GET['numReg']) {
					$paginaActual = $page;
					mysql_data_seek($result,($paginaActual-1)*$n);	
				}
			} else {
				$paginaActual = $page;
				mysql_data_seek($result,($paginaActual-1)*$n);
			}
		}

		return $result;
	}

} //fin de la Clse DB_mysql
?>