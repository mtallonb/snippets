<?php

include_once('class.breadcrumb.inc.php');

class Vista {
	var $mostrarOpciones = true; // Por defecto se mostrará el menu del usuario en todas las páginas
	var $tituloPagina = "";
	var $headAdd = "";
	var $kb = true;
	var $kb_target = null;

	function Vista() {
		$config=$this->getConfiguracion("descripcion,palabrasClave,tituloSitio");
		$_SESSION['Config_MetaDesc'] = $config['descripcion'];
		$_SESSION['Config_MetaKeys'] = $config['palabrasClave'];
		$_SESSION['Config_sitename'] = $config['tituloSitio'];
		$this->setKB();
	}

	function setHeadAdd($cadena) {
		$this->headAdd = $cadena;
	}

	function setMostrarOpciones($valor) {
		$this->mostrarOpciones = $valor;
	}

	function setTituloPagina($valor) {
		$this->tituloPagina = $valor;
	}

	function setKB($activado=true,$target='txtbusqueda') {
		$this->kb=$activado;
		$this->kb_target=$target;
	}

	function cabecera($css,$dirlang,$subtitulo="") {

		$lang = new Idioma();
		include_once($dirlang . $_SESSION['Config_lang'] . '.php');

		?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="description"
	content="<?php echo $_SESSION['Config_MetaDesc']; ?>">
<meta name="keywords"
	content="<?php echo $_SESSION['Config_MetaKeys']; ?>">
<title><?php 
$cadena_subt = "";
if(defined($subtitulo)) {
		$cadena_subt = constant($subtitulo);
	}
	else {
		$cadena_subt = $subtitulo;
	}
	if($cadena_subt != "") {
		echo($_SESSION['Config_sitename']." - ".$cadena_subt);
	}
	else {
		echo($_SESSION['Config_sitename']);
	}
	?>
</title>
<link href="<?php echo $css; ?>.css" rel="stylesheet" type="text/css" />
<script type="text/javascript"
	src="<?php echo $_SESSION['Config_diractual'];?>javascripts/desplegar.js"></script>
<script type="text/javascript"
	src="<?php echo $_SESSION['Config_diractual'];?>javascripts/jquery/jquery-1.4.2.js"></script>
<script type="text/javascript"
	src="<?php echo $_SESSION['Config_diractual'];?>javascripts/jquery/jquery.tablesorter.min.js"></script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript"
	src="<?php echo $_SESSION['Config_diractual'];?>javascripts/generales.js"></script>
<script type="text/javascript" 
    src="<?php echo $_SESSION['Config_diractual'];?>javascripts/vkboard/1-vkboard/vkboard.js"></script>
<?php 
// Cabeceras adicionales:
echo $this->headAdd;
?>
</head>

<?php
	}

	function cabeceraArabe($titulo,$css) {
	?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<meta http-equiv="Content-Type"
	content="text/html; charset=windows-1256" />
<title><?php echo $titulo; ?>
</title>
<link href="<?php echo $css; ?>.css" rel="stylesheet" type="text/css" />
<!--[if gte IE 5]>
<link href="<?php echo $css; ?>_ie.css" rel="stylesheet" type="text/css" />
<![endif]-->
</head>
<?php 
	}

	function scriptsAJAX() {?>
<script language="javascript"
	src="<?php echo $_SESSION['Config_diractual'];?>javascripts/prototype.js"
	type="text/javascript"></script>
<script language="javascript"
	src="<?php echo $_SESSION['Config_diractual'];?>javascripts/scriptaculous.js"
	type="text/javascript"></script>
<script language="javascript"
	src="<?php echo $_SESSION['Config_diractual'];?>javascripts/effects.js"
	type="text/javascript"></script>
<script language="javascript"
	src="<?php echo $_SESSION['Config_diractual'];?>javascripts/unittest.js"
	type="text/javascript"></script>
<?php }

function iniciarPagina($menu=null,$bodyParam=null) {
		global $menu;
		global $privilegios;
		$lang = new Idioma();
		$usr = new Usuario();
		//$usr->mostrarLogin();
		//$lang->mostrarFormularioCambioIdioma();
		?>
<body <?php if($bodyParam!=null) echo $bodyParam;?>>
	<!-- Cabecera de Logo ULEMAS -->
	<div class="centrado">
		<div id="topcnt">
			<a href="<?php echo $_SESSION['Config_live_site'];?>"
				title="<?php echo _ULEMAS;?>">
				<div id="cabecera"></div>
			</a>
			<?php global $eea_url; ?>
			<a href="<?php echo $eea_url;?>" title="<?php echo _EEA_WEB;?>">
				<div id="cabecera_eea"></div>
			</a>

			<?php global $csic_url; ?>
			<a href="<?php echo $csic_url;?>" title="<?php echo _CSIC_WEB;?>">
				<div id="cabecera_csic"></div>
			</a>
		</div>

		<div id="css_buttons">
			<a href=<?php echo $_SESSION['Config_live_site'];?>
				onclick="changeFontSize(1);return false;" title="Agrandar texto"> <img
				border="0" alt="Agrandar"
				src="<?php echo $_SESSION['Config_diractual']?>/img/famfamicons/font_add.png"></img>
			</a> <a href=<?php echo $_SESSION['Config_live_site'];?>
				onclick="changeFontSize(-1);return false;" title="Reducir texto"> <img
				border="0" alt="Reducir"
				src="<?php echo $_SESSION['Config_diractual']?>/img/famfamicons/font_delete.png"></img>
			</a> <a href=<?php echo $_SESSION['Config_live_site'];?>
				onclick="revertStyles(); return false;"
				title="Reiniciar estilos por defecto"> <img border="0"
				alt="Restablecer"
				src="<?php echo $_SESSION['Config_diractual']?>/img/famfamicons/font.png"></img>
			</a>
		</div>

		<div id="idioma">
			<?php $lang->mostrarFormularioCambioIdioma(); ?>
		</div>
	</div>
	<!-- ### Cabecera -->
	<!-- Menu -->
	<div id="menu">
		<div class="centrado">
			<ul>
				<?php 
					
				$count = 0;
				$npagreg = 1; // El elemento "1" de $menu es registrado
				if(!tieneAcceso($privilegios['guest'])) { //no tiene permisos
			$menu_privado = false; // Menu para visitantes
		}
		else {
			$menu_privado = true; // Menu para usuarios registrados y loggueados correctamente
		}

		$urls = array_keys($menu);

		$existe = 0;
		$count = 0;
		$item = null;
		$itemd = null;
		$defecto = "ulemas";
		global $Config_base_site;
		$ct = $Config_base_site;
		//echo substr($_SERVER["PHP_SELF"],strlen($ct)) . " --> ";

		foreach ($menu as $nombre => $valor) {
			//echo $nombre . "<br/>";
			if (substr($_SERVER["PHP_SELF"],strlen($ct)) == "/".$nombre) {
				$existe = true; // La página actual existe en el menu
				$item = $valor;
				//echo "---existe---";
			}

			if ($valor == $defecto) {
				$itemd = $valor;
			}
			$count++;
		}
		//echo $existe;

		$countp = 0;
		$existep = 0;
		global $menu_noap_menupreferente;
		foreach ($menu_noap_menupreferente as $nombrep => $valorp) {
			//echo $nombrep;
			if (substr($_SERVER["PHP_SELF"],strlen($ct)) == $nombrep) {
				$existep = true; // La página actual existe en el menu
				$itemd = $valorp;
			}
			$countp++;

		}
		//echo "[".$itemd."]{".$item."}";
		//echo $existep;
		$tipo = 0; // default
		if ($existep) {
			// menu forzado;
			//echo "menu forzado ";
			$tipo = 2;
			$item_marcado = $itemd;
		}
		else {
			if ($existe) {
				// menu principal normal;
				//echo "menu principal normal ";
				$tipo = 1; // Normal
				$item_marcado = $item;
			}
			else {
				// default;
				//echo "menu default ";
				$tipo = 0; // defecto
				$item_marcado = $itemd;
			}
		}
		//echo "ITEM MARCADO: " . $item_marcado;
		$count = 0;
		foreach ($menu as $nombre => $valor) {

			// Si es la pagina actual lo marcamos en el menu
			switch($tipo) {
				case 2: // Pestaña forzada
					break;

				case 1: // Pestaña normal
					break;

				case 0: // Pestaña por defecto
				default:
					break;
			}

			if ($valor == $item_marcado) $clase = "_act";
			else $clase = "_inact";

			$href = "href=\"".$_SESSION['Config_diractual'].$urls[$count] . "\""; // url a la que apunta el link
			$idioma = $lang -> getIdioma();
			if ( $idioma == "") {
				$idioma = $_SESSION['Config_lang'];
			}

			// Ocultaremos las áreas privadas del menú a usuarios no loggueados
			$mostrar = true;

			if($count == $npagreg) {
				if ($menu_privado) $mostrar = true;
				else $mostrar = false;
			}

			if ($mostrar) {
		?>
				<li><a <?php echo $href; ?>><img
						src="<?php echo $_SESSION['Config_diractual']; ?>img/<?php echo $idioma; ?>/<?php echo $valor.$clase; ?>.png"
						alt="<?php echo $nombre; ?>" border="0" /> </a></li>
				<?php
			}
			$count++;
		} //fin de foreach
			if (!$existe) { ?>
				<li><img
					src="<?php echo $_SESSION['Config_diractual']; ?>img/comunes/menu_transparente.png"
					alt="" border="0" /></li>
				<?php 
			}
			?>
			</ul>
		</div>
	</div>
	<!-- ### Menu -->

	<!-- Contenedor principal -->
	<div id="contenedor">

		<!-- Contenido -->
		<div id="contenido">

			<?php //$this->mostrarBreadCrumb();?>

			<?php 
			$f = explode("/",$_SERVER['PHP_SELF']);
			$f = array_reverse($f);
			$ficheroActual = $f[0];
			if(tieneAcceso($privilegios['guest']) && $this->mostrarOpciones) {
			$this->menuUsuario($usr, $opciones_global);
			//$this->menuTags();
			//$this->menuLateral();
			?>
			<!--<div id="cuerpo">-->
			<?php
		}

	} // Fin de iniciarPagina()

	function iniciarContenido() {
	?>
			<?php if($this->tituloPagina!='') { ?>
			<div class="lineaTitulo">
				<div class="tituloPagina">
					<?php echo $this->tituloPagina; ?>
				</div>
				<?php if(($this->kb)==true) { ?>
				<div class="kb_selector">
					<a class="teclado_selector" title="<?php echo _TECLADO_SWITCH;?>"
						href="#" id="kb_switch"><img
						src='<?php echo $_SESSION['Config_diractual']?>img/kb.png' /> </a>
				</div>
				<?php } ?>
				<div class="volver">
					<a href="javascript:history.back();"><?php echo _BACK; ?> </a>
				</div>
				<div class="limpieza linea"></div>

			</div>
			<?php } ?>
			<div class="limpieza"></div>
			<div class="interior">
				<div id="kb_container" class="teclado"></div>
				<?php
	}

	function terminarContenido() {
	?>
			</div>
			<!-- Fin del contenido Interior -->
			<div class="limpieza"></div>
			<?php
	}

	function terminarPagina() {
	?>
		</div>
	</div>
	<!-- ### Fin Contenedores principales -->
	<?php
	$f = explode("/",$_SERVER['PHP_SELF']);
	$f = array_reverse($f);
	$ficheroActual = $f[0];

		if($ficheroActual!="index.php" && $_SESSION['tipoUsuario']<6)	{ ?>
	<!--</div>-->
	<?php } ?>

	<!-- Fin de página -->
	<div id="finpagina">
		<div id="textofinpagina">
			<?php echo _ULEMAS;?>
			&copy; 2006-
			<?php echo date("Y"); ?>
			<?php echo _ULEMAS_RIGHTS;?>
			| <a href="#"><?php echo _ULEMAS_LIC;?> </a> | <a href="#"><?php echo _ULEMAS_TERMS;?>
			</a>
		</div>
	</div>
	<!-- ### Fin de página -->
	<?php
	if(intval(_DEBUG)>0) {
		echo "SESSION: ";
		print_r($_SESSION);

		echo "POST: ";
		print_r($_POST);

		echo "GET: ";
		print_r($_GET);
	}
	?>
</body>
</html>
<?php
	}

	function seccion($titulo,$foto) { /*
		?>
		<div class="tablaseccion"><img src="img/<?php echo $foto ?>" width="48" height="48"><?php echo $titulo; ?></div>
		<?php */
	}

	function mensajeInsert() {
		$ack=$_GET['ack'];
		$ack=$_GET['ack'];
		$stamp = "<tt>{".date(DATE_RFC822)."}</tt>";
		if($ack!="") {
			if($ack=="1")	{
				echo "<div id=\"tablamensajeok\">"._MSG_INSERT_OK." ".$stamp."</div>";
			} else {
				echo "<div id=\"tablamensajebad\">"._MSG_INSERT_BAD." ".$stamp."</div>";
			}
		}
	}

	function mensajeUpdate() {
		$ack=$_GET['ack'];
		$stamp = "<tt>{".date(DATE_RFC822)."}</tt>";
		if($ack!="") {
			if($ack=="1")	{
				echo "<div id=\"tablamensajeok\">"._MSG_UPDATE_OK." ".$stamp."</div>";
			} else {
				echo "<div id=\"tablamensajebad\">"._MSG_UPDATE_BAD." ".$stamp."</div>";
			}
		}
	}

	function mensajeDelete() {
		$ack=$_GET['ack'];
		$stamp = "<tt>{".date(DATE_RFC822)."}</tt>";
		if($ack!="") {
			if($ack=="1")	{
				echo "<div id=\"tablamensajeok\">"._MSG_DELETE_OK." ".$stamp."</div>";
			} else {
				echo "<div id=\"tablamensajebad\">"._MSG_DELETE_BAD." ".$stamp."</div>";
			}
		}
	}

	function errorGenerico() {
		?>
<div>
	<?php echo _ERR_404; ?>
</div>
<a href="./" target="_parent"><?php echo _BACK_SECC; ?> </a>
<?php
	}

	function mostrarBreadCrumb() {
		$breadcrumb = new breadcrumb;
		$breadcrumb->homepage='Inicio'; // sets the home directory name
		$breadcrumb->dirformat='ucfirst'; // Show the directory in this style
		$breadcrumb->symbol=' >> '; // set the separator between directories
		$breadcrumb->unlinkCurrentDir=TRUE;
		$breadcrumb->changeName;
		$breadcrumb->showfile=TRUE; // shows the file name in the path
		$breadcrumb->linkFile=FALSE; // Link the file to itself
		$breadcrumb->_toSpace=TRUE; // converts underscores to spaces
		$breadcrumb->hideFileExt=TRUE;
		echo $breadcrumb->show_breadcrumb();
	}


	function paginacion($sql, $n, $tipo="", $getparams="") { //Este método pagina los registros de la tabla de la BBDD que se le pasa por parámetro, y en el segundo argumento  se le pasa el numero de registros a mostrar por página
		$limite = "";
		if(!tieneAcceso($privilegios['admin'])) {//si el usuario tiene un ROL inferior a Admin sólo verá _MAX_RESULTS_GUEST registros de la BBDD
			$limite = " LIMIT "._MAX_RESULTS_GUEST;
		}
		$conexion = new DB_mysql();
		$conexion->conectar();
		$result = $conexion->consulta($sql . $limite);
		$numPaginas=0;
		$paginaActual = 1;
		if($n!=0) { //Si hay que mostrar todos los campos

			$numFilas = $conexion->numregistros();
			$numPaginas = intval($numFilas/$n);
			if($numFilas%$n>0) {
				$numPaginas++;
			}
			if(isset($_GET['page']) && $_GET['page']!="") {
				$paginaActual = intval($_GET['page']);
			}
			else {
				$paginaActual = 1;
			}

			if($paginaActual<1) {
				$paginaActual = 1;
			}
			else {
				if($paginaActual > $numPaginas) $paginaActual=$numpaginas;
			}
			?>
<div id="paginacion">
	<?php 

	$p_ini=floor($paginaActual-(_MAX_LINKS/2));
	$p_fin=floor($paginaActual+(_MAX_LINKS/2));
	if ($p_ini < 1) {
					$p_fin = $p_fin + abs($p_ini);
					$p_ini = 1;
			}
			if ($p_ini>1) {
				$mostrar_laquo = 1;
			}
			if ($p_fin<$numPaginas) {
				$mostrar_raquo = 1;
			}

			if($numPaginas>1) {
				//echo "P&aacute;gina";
				$txt="";
				$campo="";
				$modo="";
				if($tipo==1) {
					$txt = "&txtbusqueda=".$_GET['txtbusqueda'];
					$campo = "&campo=".$_GET['campo'];
					$modo = "&modo=".$_GET['modo'];
					$pagpo = "&numReg=".$n;
					$orden = "&ord=".$_GET['ord'];
				}

				if($paginaActual>1) {
					$j = $paginaActual-1;
					if($tipo==1 && $mostrar_laquo==1) {
						?>
	<a
		href="<?php echo $PHP_SELF."?page=".$j.$txt.$campo.$modo.$pagpo.$orden.$getparams;?>">&laquo;</a>
	<?php
					}
					if ($tipo==2) {
						$cadena = $this->actualiza_get_param("page",$j);
						//echo $_SERVER['QUERY_STRING']."<br/>";
						//echo $cadena;
						?>
	<a href="<?php echo $PHP_SELF."?".$cadena;?>">&laquo;</a>
	<?php
					}
				}

				for($i=$p_ini; $i<=$numPaginas && $i<=$p_fin; $i++) {
					if($i==$paginaActual) {
						?>
	<a class="actual"><?php echo $i;?> </a>
	<?php
					} else {
							switch($tipo){
								case 1:
							?>
	<a
		href="<?php echo $PHP_SELF."?page=".$i.$txt.$campo.$modo.$pagpo.$orden.$getparams;?>"><?php echo $i;?>
	</a>
	<?php						break;
								case 2: 
							$cadena = $this->actualiza_get_param("page",$i);
							?>
	<a href="<?php echo $PHP_SELF."?".$cadena."&page=".$i;?>"><?php echo $i;?>
	</a>
	<?php						break;
								case 3: 
							$cadena = $this->get_param();
							//echo "CADENA: ",$cadena;
							?>
	<a href="<?php echo $PHP_SELF."?".$cadena."&page=".$i;?>"><?php echo $i;?>
	</a>
	<?php
								break;
						}
						
					}
				}

				if($paginaActual<$numPaginas) {
						$j = $paginaActual+1;
						if($tipo==1 && $mostrar_raquo==1) {
							?>
	<a
		href="<?php echo $PHP_SELF."?page=".$j.$txt.$campo.$modo.$pagpo.$getparams;?>">&raquo;</a>
	<?php
						}
						if ($tipo==2) {
								$cadena = $this->actualiza_get_param("page",$j);
								?>
	<a href="<?php echo $PHP_SELF."?".$cadena;?>">&raquo;</a>
	<?php
						}
				}
			} ?>
</div>
<?php 
		} else { // Si  hay que mostrar todos los campos
			//echo "Se muestran todos los registros";
		}
	}
	
	function get_param() {
		$cadena = null;
		$total_gv = count($_GET);
		$i=0;
		$match=false;
		foreach ($_GET as $k => $v) {			
				$cadena.= $k. "=" . $v."&";
		}
		return $cadena;
	}

	function actualiza_get_param($campo,$valor) {
		$cadena = null;
		$total_gv = count($_GET);
		$i=0;
		$match=false;
		foreach (array_flip($_GET) as $getparam) {
			//echo $getparam . "=" . $_GET[$getparam]."<br/>";
			$i++;
			if($getparam == $campo) {
				$cadena.= $getparam . "=" . $valor;
				$match = true;
			} else {
				$cadena.= $getparam . "=" . $_GET[$getparam];
			}
			if ($i < $total_gv) $cadena.="&";
		}
		if ($match==false) $cadena.="&".$campo."=".$valor;

		return $cadena;
	}

	function mostrarFormGuardarBusqueda($actual,$strbusqueda='') {
		$consultaBF = $actual;
		$conexion = new DB_mysql();
		$conexion->conectar();
		$result=$conexion->consulta("SELECT * FROM busqueda WHERE consulta='$consultaBF'");
		if(($conexion->numregistros())==0) {
		?>
<div id="lineaBusquedaFavorita">
	<form id="formBusquedaFavorita" name="formBusquedaFavorita"
		method="post" action="../usuarios/procesar_insertar_busqueda.php">
		<input name="tituloBF" id="tituloBF"
			value="<?php echo _SAVEFS_TEXT; ?>" size="35" maxlength="50"
			onclick="this.value=''"
			onblur="if(this.value=='') this.value='Inserte un t&iacute;tulo para la B&uacute;squeda';" />
		<input id="consultaBF" name="consultaBF" type="hidden"
			value="<?php echo $consultaBF; ?>" />
		<button>
			<?php echo _SAVEFS; ?>
		</button>
	</form>
	<hr />
	<?php $this->mostrarFormExportacion($strbusqueda); ?>
</div>
<?php		
		}
	}

	function mostrarFormExportacion($sql) {
		$consultaEXP = $sql; ?>
<div id="lineaExportacion">
	<form id="formExportacion" name="formExportacion" method="post"
		action="../usuarios/procesar_exportacion.php">
		<input id="consultaEXP" name="consultaEXP" type="hidden"
			value="<?php echo $consultaEXP; ?>" /> Seleccione una
		exportaci&oacute;n: <input id="pdf" name="tipoexport" value="pdf"
			type="image" src="../img/pdf.png" alt="PDF" /> <input id="excel"
			name="tipoexport" value="excel" type="image" src="../img/excel.png"
			alt="Excel" />
	</form>
</div>
<?php 
	}

	function cargarContenidoEstatico($dirlang, $menu=null) {
		global $menu;
		global $menu_noap;

		$f = explode("/",$_SERVER['PHP_SELF']);
		$f = array_reverse($f);
		$ficheroActual = $f[0];
		if($menu[$ficheroActual]!="") {
			$nombreA =  $_SESSION['Config_diractual'] .$dirlang . $_SESSION['Config_lang'] . '_' . $menu[$ficheroActual] .'.php';
		}
		else {
			$nombreA =  $_SESSION['Config_diractual'] . $dirlang . $_SESSION['Config_lang'] . '_' . $menu_noap[$ficheroActual] .'.php';
		}
		if(file_exists($nombreA)) {
			include($nombreA);
		}
		else {
			if (_DEBUG) print_r ($f);
			if (_DEBUG) echo "$nombreA <br />";
			echo _ERR_404;
		}

	}

	function tag($tag,$descripcion,$texto,$linkurl,$clase='') {
		if($texto=='') {
			$texto = _NDEF;
			$linkurl = '';
		}
		if($tag=='') {
			$tag = $descripcion;
		}
		echo "<abbr title='". $descripcion ."'>". $tag ."</abbr>&nbsp;";
		if($linkurl!='') {
			$texto = "<a href='". $linkurl ."' title='". $descripcion ."'>". $texto . "</a>";
		}
		if($clase!='') {
			$texto = "<span class='".  $clase ."'>". $texto . "</span>";
		}
		// Agregamos un espacio al final para facilitar el Word-Wrap del navegador.
		echo $texto." ";


	}

	function menuUsuario($usr) {
	global $opciones_global; //tabla global de opciones de usuarios
	global $archivo_ulemas; //nombre del archivo del gestor
	//require_once('..\config.php');
	//global $archivo_ulemas;
	//if (basename($_SERVER['SCRIPT_NAME'])==$archivo_ulemas) {

	?>
<!-- Cajón superior -->

<div id="cajonsup_mini">
	&darr; <a href="#" onclick="javascript:despliega_switch()"><?php echo _MOSTRAR_MENU?>
	</a> | <a
		href="<?php echo $_SESSION["Config_diractual"].$archivo_ulemas;?>"
		title="<?php echo _PANEL_ULEMAS_INFO?>"><?php echo _PANEL_ULEMAS?> </a>
	| <a
		href="<?php echo $_SESSION["Config_diractual"];?>usuarios/perfil_usuario.php"
		title="<?php echo _VER_PERFIL?>"><?php echo $usr->getNombre(); ?> </a>
	(
	<?php echo $usr->getTipoEscrito(); ?>
	) &raquo; <a
		href="<?php echo $_SESSION["Config_diractual"];?>usuarios/logout.php"
		title="<?php echo _SALIR?>"><?php echo _DESCONECTAR?> </a>
</div>
<div id="cajonsup">
	<ul class="inicioalt usrbox">
		<li>&uarr; <a href="#" onclick="javascript:despliega_switch()"
			id="moswitch_mini"><?php echo _OCULTAR_MENU;?> </a>
		</li>
		<li class="titulo"><?php echo _BIENVENIDO_A?> <a
			href="<?php echo $_SESSION["Config_diractual"];?>usuarios/perfil_usuario.php"
			title="Ver perfil"><?php echo $usr->getNombre(); ?> </a></li>
		<li><?php echo _EN_EL_SISTEMA_COMO?> <?php echo  $usr->getTipoEscrito(); ?>
		</li>
		<li><?php $usr->printEstrellas(); ?></li>
		<li><a
			href="<?php echo $_SESSION["Config_diractual"];?>usuarios/logout.php"
			title="Salir del sistema"><?php echo _DESCONECTAR?> </a></li>
	</ul>
	<div class="menu_usuario">

		<?php

		$nivel_login = $usr->getTipoUsuario();
		foreach($opciones_global as $opcion_principal) {
		if(tieneAcceso($opcion_principal['nivel'])) {
				$cadena_tit = "";
				if(!defined($opcion_principal['texto'])) {
					$cadena_tit=$opcion_principal['texto'];
				}
				else {
					$cadena_tit=CONSTANT($opcion_principal['texto']);
				}
				?>
		<ul>
			<li class="titulo"><?php echo $cadena_tit; ?></li>
			<?php
			/* <li><a href="<?php echo $opcion_principal['url']; ?>"><?php echo $opcion_principal['texto']; ?></a></li> */
			foreach( $opcion_principal['submenu'] as $opcion) {
			if(tieneAcceso($opcion['nivel'])) {
				$cadena = "";
				if(!defined($opcion['titulo'])) {
					$cadena=$opcion['titulo'];
				}
				else {
					$cadena=CONSTANT($opcion['titulo']);
				}
				?>
			<li><a
				href="<?php echo $_SESSION["Config_diractual"].$opcion['url']; ?>"><?php echo $cadena;?>
			</a></li>
			<?php
			}
		}
		?>
		</ul>
		<?php
		}
	}
	?>
	</div>
	<div class="limpieza"></div>
</div>

<?php
	}

	/**************** FUNCIONES AJAX ***********/
	function ajaxObjeto() {
		$this->scriptsAJAX();
		?>
<script language="javascript" type="text/javascript">
			function objetoAjax(){
				var xmlhttp=false;
				try {
					xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
				} catch (e) {
					try {
						xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
					} catch (E) {
						xmlhttp = false;
					}
				}
				if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
					xmlhttp = new XMLHttpRequest();
				}
				return xmlhttp;
			}
	<?php }
	
	function ajaxTablaBusqueda() {?>
			<?php $this->ajaxObjeto(); ?>
			function mostrarTablaBusqueda(){
				var divResultado = document.getElementById('divBusqueda');
				var campo1 = document.getElementById('tablaBusqueda').value;
				var campo2 = document.getElementById('tipoBusqueda').value;
				//divResultado.innerHTML= '<img src="anim.gif">';
				ajax=objetoAjax();
				ajax.open("GET", "procesar_tabla_busqueda.php?tabla="+campo1+"&tipoB="+campo2);
				ajax.onreadystatechange=function() {
					if (ajax.readyState==4) {
						divResultado.innerHTML = ajax.responseText
					}
				}
				ajax.send(null)
			} 			
		</script>
<?php }

	function ajaxTablaBusquedaADV() {?>
<?php $this->ajaxObjeto(); ?>
function mostrarTablaBusquedaADV(){ var divResultado =
document.getElementById('divRes'); var campo =
document.getElementById('tablaBusquedaADV').value; ajax=objetoAjax();
ajax.open("POST", "tabla_busqueda_adv.php?tabla="+campo);
ajax.onreadystatechange=function() { if (ajax.readyState==4) {
divResultado.innerHTML = ajax.responseText } } ajax.send(null) }
</script>
<?php }

	function mostrarTablasBusqueda ($actual) { ?>
<script type="text/JavaScript">
			<!--
			function menuSalto(targ,selObj,restore){ //v3.0
			  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
			  if (restore) selObj.selectedIndex=0;
			}
			//-->
		</script>
<select name="tablaBusqueda" onchange="menuSalto('parent',this,0)">
	<option value="../personaje/busqueda_simple.php"
	<?php if ($actual=="personaje") echo "selected='selected'";?>=>Personaje</option>
	<option value="../lugar/busqueda_simple.php"
	<?php if ($actual=="lugar") echo "selected='selected'";?>>Lugar</option>
	<option value="../cargo/busqueda_simple.php"
	<?php if ($actual=="cargo") echo "selected='selected'";?>>Cargo</option>
	<option value="../referencia/busqueda_simple.php"
	<?php if ($actual=="referencia") echo "selected='selected'";?>>Referencia</option>
	<option value="../familia/busqueda_simple.php"
	<?php if ($actual=="familia") echo "selected='selected'";?>>Familia</option>
	<option value="../actividad/busqueda_simple.php"
	<?php if ($actual=="actividad") echo "selected='selected'";?>>Oficio/Actividad</option>
	<option value="../disciplina/busqueda_simple.php"
	<?php if ($actual=="disciplina") echo "selected='selected'";?>>Disciplina</option>
	<option value="../fuente/busqueda_simple.php"
	<?php if ($actual=="fuente") echo "selected='selected'";?>>Fuente</option>
</select>
<?php }

function getConfiguracion($campos) {
		$conexionMK = new DB_mysql();
		$conexionMK->conectar();
		$resConf = $conexionMK->consulta("SELECT $campos FROM configuracion where seleccionada = 'si'");
		$conf = mysql_fetch_assoc($resConf);
		return $conf;
	}

	function listaTabla($tabla,$campoValor,$campoNombre,$noEsp=false,$onChange="",$idmarcada=0) {
		$miconexion = new DB_mysql();
		$miconexion->conectar();
		$res = $miconexion->consulta("SELECT * FROM $tabla");
		if($miconexion->numregistros()>0) {
		?>
<select id="<?php echo $campoValor; ?>"
	name="<?php echo $campoValor; ?>" style="width: 436px;" class="txtar"
	<?php if($onChange!="") { echo "onChange='$onChange'"; }?>>
	<?php if($noEsp==true) { ?>
	<option value="noespecificado" selected="selected">No especificado</option>
	<?php } ?>
	<?php while($row=mysql_fetch_assoc($res)) { ?>
	<option
		class="txtar <?php if($row[$campoValor] == $idmarcada) { ?> opt-marcada<?php } ?>"
		value="<?php echo $row[$campoValor]?>"
		<?php if($row[$campoValor] == $idmarcada) { ?> selected="selected"
		<?php } ?>>
		<?php echo $row[$campoNombre]; ?>
	</option>
	<?php }
		} else {
			echo "La tabla $tabla est&aacute; vac&iacute;a";
		}
		?>
</select>
<?php
	}

	function listaTablaRelaciones($id,$sentido) {
		$miconexion = new DB_mysql();
		$miconexion->conectar();
		if($sentido=="descendente") {
			$sql ="SELECT * FROM personaje WHERE idPersonaje<>'$id' AND ";
		} else {
			$sql ="SELECT idPersonaje,nombreA FROM personaje AS p,personaje_relacion AS pr WHERE (p.idPersonaje=pr.idPersonaje1) AND (p.idPersonaje<>'$id' AND pr.iPersonaje1<>'$id')";;
		}
		$res = $miconexion->consulta($sql);	?>
<select id="<?php echo "idPersonaje"; ?>"
	name="<?php echo "idPersonaje"; ?>" style="width: 436px;" class="txtar">
	<?php while($row=mysql_fetch_assoc($res)) { ?>
	<option
		class="txtar <?php if($row["idPersonaje"] == $idmarcada) { ?> opt-marcada<?php } ?>"
		value="<?php echo $row["idPersonaje"]?>"
		<?php if($row["idPersonaje"] == $idmarcada) { ?> selected="selected"
		<?php } ?>>
		<?php echo $row["nombreA"]; ?>
	</option>
	<?php } ?>

</select>
<?php
	}

	function accesoIconoPanel($linkUrl,$linkText,$linkTitle,$iconoFile,$iconoAlt,$nivelUsuario) {
		global $privilegios;
		if ($iconoFile=='') {
			$iconoFile = "pc_ico_default.png";
		}
		$iconoUrl = $_SESSION['Config_diractual']."img/comunes/".$iconoFile;
		if(tieneAcceso($nivelUsuario)) {
	?>
<div class="iconoPanel">
	<a href="<?php echo $linkUrl; ?>" target="_parent"
		title="<?php echo $linkTitle; ?>"><img src="<?php echo $iconoUrl; ?>"
		alt="<?php echo $iconoAlt; ?>" /> </a>
	<?php echo $linkText; ?>
</div>
<?php
		}
	}

	function tipoNisba($nombre_de_la_constante) {
		$temp = $nombre_de_la_constante;

		if($temp != NULL && strlen($temp) > 0) {
			$temp = constant($nombre_de_la_constante);
		}
		else {
			$temp = "";
		}

		return $temp;
	}

	function addJQuery() {
	?>
<script
	type="text/javascript"
	src="<?php echo $_SESSION['Config_diractual']?>javascripts/jquery/jquery-1.4.2.js"></script>
<?php
	}

	function addJQueryAutocompleter() {
	?>

<script
	type='text/javascript'
	src='<?php echo $_SESSION['Config_diractual']?>javascripts/jquery/jquery.autocomplete.js'></script>
<link
	rel="stylesheet" type="text/css"
	href="<?php echo $_SESSION['Config_diractual']?>javascripts/jquery/jquery.autocomplete.css" />
<?php
	}

	function addJQueryForms() {
	?>
<script
	type='text/javascript'
	src='<?php echo $_SESSION['Config_diractual']?>javascripts/jquery/jquery.form.js'></script>
<?php
	}

	function printDIV($clase, $titulo, $datos, $printIfEmpty = false) {
		$empty = true;
		$salida = "";
		if($datos != null && strlen(trim($datos)) > 0) {
			$empty = false;
		}
		if (!$empty || $printIfEmpty) {
			$salida  = $titulo."<br />";
			$salida .= "<div class=\"".$clase."\">".$datos."</div>";
		}
		return $salida;
	}

	function printAjaxCommonScripts($nActuales,$code,$codes,$upcode,$upcodes) {
		?>
<script language="javascript" type="text/javascript">
<!--
var n<?php echo $upcodes;?> = <?php echo $nActuales?>;
$(document).ready(function() { 
	var options<?php echo $codes;?> = { target: '#<?php echo $codes;?>Container', success: function() { $('#<?php echo $codes;?>Container').fadeIn('slow'); } }; 
	$('#form_<?php echo $code;?>').ajaxForm(options<?php echo $codes;?>); 
});
var n<?php echo $upcodes;?>_r = 1; var n<?php echo $upcodes;?>_fn = 'form_<?php echo $code;?>';
function updateStyle<?php echo $upcode;?>(formname,contador) {
var formulario = document.getElementById(formname);	
if (contador>1) formulario.setAttribute("class", "detalle unsaved"); 
else formulario.setAttribute("class", "detalle"); 
}; updateStyle<?php echo $upcode;?>(n<?php echo $upcodes;?>_fn,0);
function delReg<?php echo $upcode;?>(divid) {
  var d = document.getElementById('<?php echo $code;?>Registros');
  var olddiv = document.getElementById(divid);
  d.removeChild(olddiv);
  updateStyle<?php echo $upcode;?>(n<?php echo $upcodes;?>_fn,--n<?php echo $upcodes;?>_r);
}
function updateReg<?php echo $upcode;?>() {
	var ncamposinput = document.getElementById('<?php echo $code;?>NCampos');
	ncamposinput.value=n<?php echo $upcodes;?>;
}
	<?php
	}
	
	function printAjaxCommonScriptsADini($nActuales,$code,$codes,$upcode,$upcodes) {
	?>
function addReg<?php echo $upcode;?>() {
	var newdiv = document.createElement('div');
	var ni = document.getElementById('<?php echo $code;?>Registros');
	var divIdName = 'div<?php echo $upcode;?>'+n<?php echo $upcodes;?>;
	newdiv.setAttribute('id',divIdName);
	<?php
	}
	
	function printAjaxCommonScriptsADend($nActuales,$code,$codes,$upcode,$upcodes) {
	?>
	
	updateReg<?php echo $upcode;?>();
	n<?php echo $upcodes;?>++;
	updateStyle<?php echo $upcode;?>(n<?php echo $upcodes;?>_fn,++n<?php echo $upcodes;?>_r);
	makeNiceTitles();
	kb(); // Actualiza campos accesibles por KB
}
-->
</script>
<?php
	}
}
?>
