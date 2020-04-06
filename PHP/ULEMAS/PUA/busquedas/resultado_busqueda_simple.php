<?php include_once('../comun.inc.php'); ?>
<?php controlAcceso($privilegios['guest']); ?>
<?php $vista = new Vista(); ?>

<?php $_SESSION['Config_diractual']='../'; ?>
<?php $vista->cabecera($_SESSION['Config_diractual'].'css/'.$Config_css,$_SESSION['Config_diractual'].$Config_dirIdiomas,$subtitulo); ?>
<?php $subtitulo = _BUSQUEDA_SIMPLE_RESULTADO; ?>
<?php $vista->setTituloPagina( $subtitulo ); ?>
<?php $vista->iniciarPagina(); ?>
<?php $vista->iniciarContenido(); ?>

	<?php $miconexion = new DB_mysql(); ?>
	<?php $miconexion->conectar(); ?>
	<?php
	$busqueda = trim(@$_GET['txtbusqueda']);
	$campo   = @$_GET['campo'];
	$modo   = @$_GET['modo'];
	$numcomp = @$_GET['numcomp'];
	$ordnum = @$_GET['orden'];

	//echo $busqueda;
	$busqueda = str_replace('\"', '"', $busqueda); 
	$campo = str_replace('\"', '"', $campo); 
	$modo = intval(str_replace('\"', '"', $modo)); // Adem�s deber�amos filtrar seg�n el campo (queda pendiente de HACER)
	$numcomp = intval(str_replace('\"', '"', $numcomp)); 
	$ordnum = intval(str_replace('\"', '"', $ordnum)); 
	$ordadd = null;
	
	$busqueda_numerica = false;
	$campo_original = $campo;
		// traducimos las abreviaturas de los campos a su valor de BBDD
		switch($campo) {
			case "edad":
			case "nacimiento":
			case "muerte":
			case "nacimientoec":
			case "muerteec":
				$busqueda_numerica = true;
				break;
			case "idp":
				$campo="idPersonaje";
				$busqueda_numerica = true;
				break;
			case "resumenb":
				$campo="resumenBiografico";
				break;
			case "maestroso":
				$campo="maestrosOrientales";
				break;
			case "fuentesc":
				$campo="fuentesCitadas";
				break;
			case "comentarios":
				$campo="nacimiento_comentario,muerte_comentario,edad_comentario";
				break;
			case "todos":
				//$campo="nombree,nombrea,suhra,nisba,nacimiento_comentario,muerte_comentario,edad_comentario,otros,notas,resumenBiografico,maestrosOrientales,fuentesCitadas,nacimiento,muerte,edad,idPersonaje";
				$campo="nombree,nombrea,suhra,nacimiento_comentario,muerte_comentario,edad_comentario,otros,notas,resumenBiografico,maestrosOrientales,fuentesCitadas,nacimiento,muerte,edad,idPersonaje";
				break;
		}
	
	include_once('./formulario_busqueda_simple.php.inc');
	
	if($_GET['page'] && $_GET['page']!="") {
		$paginaActual = $_GET['page'];
	}
	else {
		$paginaActual = 1;
	}
	
	if (isset($_GET['txtbusqueda'])) {
		if(trim($_GET['txtbusqueda']) != ''){
		
			if (!$busqueda_numerica) {
				$obj = new ArQuery();
				//$obj->setStrFields('nombre,nacimiento,otros,notas,resumenBiografico');
				//$busqueda = str_replace('%', '[��������������������������������������������������]', $busqueda);
				//$campo = 'nombreA,nombreE,nacimiento,otros,notas,resumenBiografico';
				/*$search = $busqueda;
				$search = str_replace('a', '[a������]', $search);
				$search = str_replace('e', '[e����]', $search);
				$search = str_replace('i', '[i����]', $search);
				$search = str_replace('o', '[o�����]', $search);
				$search = str_replace('u', '[u����]', $search);
				$search = str_replace('A', '[A������]', $search);
				$search = str_replace('E', '[E����]', $search);
				$search = str_replace('I', '[I����]', $search);
				$search = str_replace('O', '[O�����]', $search);
				$search = str_replace('U', '[U����]', $search);
				$busqueda = $search;*/
				
				$busqueda=preg_replace(array('@&([a-zA-Z]){1,2}(acute|grave|circ|tilde|uml|ring|elig|zlig|slash|cedil|strok|lig){1};@', '@&[euro]{1};@'), array('${1}', 'E'), $busqueda);

				$obj->setStrFields($campo); // Hacemos busquedas sobre un �nico campo
				$obj->setMode($modo); //cualquier palabra
		
				$strCondition = $obj->getWhereCondition($busqueda);
			}
			else {
				$numadd = null;
				switch($numcomp) {
					case 1: $numadd = '>';  break;
					case 2: $numadd = '>='; break;
					case 3: $numadd = '<';  break;
					case 4:	$numadd = '<='; break;
					case 5:	$numadd = '!='; break;
					case 0: default:$numadd = '=';
				}

				switch($ordnum) {
					case 1: $ordadd = 'ASC';  break;
					case 0: default:$ordadd = 'DESC';
				}
				$strCondition = $campo . " " . $numadd . " " . intval($busqueda);
			}
		}else{
			//$strCondition = '1' permite mostrar todos los resultados de la tabla de personajes (Full DB) en consultas vec�as
			global $privilegios;
			// Permitiremos este tipo de b�squedas para administradores y administradores
			if(tieneAcceso($privilegios['admin'])) { 
				$strCondition = '1';
			}
			else {
				// A este usuario no se le permiten b�squedas FULL-Table de personajes con consultas vac�as
				$strCondition = '';
			}
				
			
			?>

			<!--<b>No se han introducido los par&aacute;metros necesarios para completar la b&uacute;queda. 
			Se mostrar&aacute;n todos los campos disponibles:</b>-->
			<?php
		}
		$StrSQL = "SELECT COUNT(*) FROM personaje";
		$result = $miconexion->consulta($StrSQL);
		$total_reg = mysql_fetch_row($result);
		
		$StrSQL = "SELECT * FROM personaje WHERE $strCondition";
		if ($ordadd != null) {
			$orden_sql = "ORDER BY $campo $ordadd";
		}
		else {
			$orden_sql = "ORDER BY nombreA ASC";
		}
		
		if(tieneAcceso($privilegios['admin'])) {
			$limites = '';
		}
		else {
			$limites = "LIMIT "._MAX_RESULTS_GUEST;
			$limitado = true;
		}
		
		$StrSQL .= " " . $orden_sql. " " . $limites;
			
		$registrosPorPagina = 20;
		if ($strCondition != '') {
			$result=$miconexion->consultaPaginada($StrSQL,$registrosPorPagina);
		}
		else {
			// No se ha introducido ning�n par�metro de b�squeda
			echo _ERR_EMPTY_DATA;
		}
		
		if($strCondition == '' || $result==0) {
			echo $miconexion->Error;
			?>
				<div class="limpieza mediosalto"></div>
			<?php
		} else {
		
		//AQUI MOSTRAMOS EL FORMULARIO PARA GUARDAR LA BUQUEDA COMO FAVORITA
		/*
		if($_SESSION['tipoUsuario']<6) {
			if($miconexion->numregistros()>0) { 
				$vista->mostrarFormGuardarBusqueda($_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'],$StrSQL); 	
			}
			//$vista->mostrarFormExportacion($StrSQL);
		
		}
		*/
		//echo "<div id='resultadosPara'>Resultados para: <b>".$busqueda."</b> en <b>".$campo."</b></div>";
		//<div class="limpieza"></div>
		?>
		<br class="limpieza" />
		
		<?php $de = ($paginaActual-1)*$registrosPorPagina+1; ?>
		<?php $hasta = ($paginaActual)*$registrosPorPagina; ?>
		<?php $hasta2 = $miconexion->numregistros(); ?>
		<?php if($hasta>$hasta2) $hasta=$hasta2; ?>
		<?php if ($de > $hasta) $de = 0; 
			if ( $miconexion->numregistros() == _MAX_RESULTS_GUEST && $limitado) {
				$extra = _MAX_RESULTS_GUEST_MSG;
			}
		?>
		<?php echo _COINCIDENCIAS . " " . $de . " - ". $hasta . _DE . " " . $miconexion->numregistros() . " (" .  $total_reg[0] . " " .  _REGISTROS . ") " . $extra; ?>
		<?php //echo _RESULTADOS . " " . $de . " - ". $hasta . _DE . $miconexion->numregistros() . _COINCIDENCIAS . "10.342" . _REGISTROS; ?>
		<hr class="limpieza" />
		<div id='tablaResultado'>

		<?php
		if($miconexion->numregistros()!=0) {
			// mostramos los nombres de los campos
			/*echo "<td><b>Opciones</b></td>\n";
			for ($i = 2; $i+1 < $this->numcampos(); $i++){
				echo "<td><b>".$this->nombrecampo($i)."</b></td>\n";
			}
			echo "</tr>\n";*/
			// mostrarmos los registros

			for ($j=1; ($j<=$registrosPorPagina || $registrosPorPagina=="0") && $row = mysql_fetch_assoc($result); $j++) {
				?>

<div class="contenedor">
	<div class="datos">
		<div id='r<?php echo $j;?>' class="fila_busqueda <?php if($j%2-1) echo "sep";?>">

		<?php echo ($paginaActual-1)*$registrosPorPagina+$j; ?>
		<?php $vista->tag(_NAMEA_TAG, _NAMEA, $row['nombreA'], '../personaje/consulta_personaje.php?id='.$row['idPersonaje'], 'principal arabe'); ?>
		<br class="limpieza" />
		<?php $vista->tag(_NAMEE_TAG, _NAMEE, $row['nombreE'], ''); ?>
		<?php //$vista->tag(_PLACE_TAG, _PLACE, $row['idLugar'], ''); ?>
		<?php //$vista->tag(_NACIMIENTO_TAG, _NACIMIENTO, $row['nacimiento'], ''); ?>
		<?php //$vista->tag(_OTHERS_TAG, _OTHERS, $row['otros'], ''); ?>
		<br />
		<?php //$vista->tag(_NOTES_TAG, _NOTES, $row['notas'], ''); ?>
		<?php //$vista->tag(_BIOGRAPHY_TAG, _BIOGRAPHY, $row['resumenBiografico'], ''); ?>
		<?php
		switch($campo_original) {
			case "resumenb":
				$vista->tag(_BIOGRAPHY_TAG, _BIOGRAPHY, $row['resumenBiografico'], '');
				break;
			
			case "notas":				
					$vista->tag(_NOTES_TAG, _NOTES, $row['notas'], '');
					break;				
			case "otros":
				$vista->tag(_OTHERS_TAG, _OTHERS, $row['otros'], '');
				break;
			case "suhra":
				$vista->tag(_SUHRA_TAG, _OTHERS, $row['suhra'], '');
				break;
			case "nisba":
				$vista->tag(_NISBA_TAG, _OTHERS, $row['nisba'], '');
				break;
			case "maestroso":
				$vista->tag(_MAESTROS_ORIENTALES_TAG, _OTHERS, $row['maestrosOrientales'], '');
				break;
			case "fuenetsc":
				$vista->tag(_FUENTES_CITADAS_TAG, _OTHERS, $row['fuentesCitadas'], '');
				break;
			case "nacimiento":
				$vista->tag(_NACIMIENTO_TAG, _OTHERS, $row['nacimiento'], '');
				break;
			case "muerte":
				$vista->tag(_MUERTE_TAG, _OTHERS, $row['muerte'], '');
				break;
			case "edad":
				$vista->tag(_EDAD_TAG, _OTHERS, $row['edad'], '');
				break;
			case "idp":
				$vista->tag(_ID_PERSONAJE_TAG, _OTHERS, $row['idPersonaje'], '');
				break;
			case "comentarios":
				$vista->tag(_COMENTARIO_NACIMIENTO_TAG, _OTHERS, $row['nacimiento_comentario'], '');
				$vista->tag(_COMENTARIO_MUERTE_TAG, _OTHERS, $row['muerte_comentario'], '');
				$vista->tag(_COMENTARIO_EDAD_TAG, _OTHERS, $row['edad_comentario'], '');
				break;
			case "todos":
				$vista->tag(_COMENTARIO_NACIMIENTO_TAG, _OTHERS, $row['nacimiento_comentario'], '');
				
				$vista->tag(_SUHRA_TAG, _OTHERS, $row['suhra'], '');
				$vista->tag(_NISBA_TAG, _OTHERS, $row['nisba'], '');

				$vista->tag(_BIOGRAPHY_TAG, _BIOGRAPHY, $row['resumenBiografico'], '');
				$vista->tag(_MAESTROS_ORIENTALES_TAG, _OTHERS, $row['maestrosOrientales'], '');
				$vista->tag(_FUENTES_CITADAS_TAG, _OTHERS, $row['fuentesCitadas'], '');
				
				$vista->tag(_NOTES_TAG, _NOTES, $row['notas'], '');
				$vista->tag(_OTHERS_TAG, _OTHERS, $row['otros'], '');

				$vista->tag(_NACIMIENTO_TAG, _OTHERS, $row['nacimiento'], '');
				$vista->tag(_NACIMIENTO_EC_TAG, _OTHERS, $row['nacimientoec'], '');
				$vista->tag(_COMENTARIO_EDAD_TAG, _OTHERS, $row['edad_comentario'], '');
				$vista->tag(_MUERTE_TAG, _OTHERS, $row['muerte'], '');
				$vista->tag(_MUERTE_EC_TAG, _OTHERS, $row['muerteec'], '');
				$vista->tag(_COMENTARIO_MUERTE_TAG, _OTHERS, $row['muerte_comentario'], '');
				$vista->tag(_EDAD_TAG, _OTHERS, $row['edad'], '');

				$vista->tag(_ID_PERSONAJE_TAG, _OTHERS, $row['idPersonaje'], '');

				break;
		}
		?>
		</div>
	</div>			
</div>

			<?php
			}
			
			$vista->paginacion($StrSQL,$registrosPorPagina,2);
		} else {
		?>
			<div><?php echo _MSG_SIN_COINCIDENCIAS;?></div>
			<br class="limpieza" />
		<?php
		}
		?>
		</div> 
		<?php
		}
	}
	?>
	
<?php $vista->terminarContenido();	?>
<?php $vista->terminarPagina();	?>