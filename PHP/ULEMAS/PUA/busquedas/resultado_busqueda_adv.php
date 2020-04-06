<?php include_once('../comun.inc.php'); ?>
<?php controlAcceso($privilegios['guest']); ?>
<?php $vista = new Vista(); ?>

<?php $_SESSION['Config_diractual']='../'; ?>
<?php $vista->cabecera($_SESSION['Config_diractual'].'css/'.$Config_css,$_SESSION['Config_diractual'].$Config_dirIdiomas,$subtitulo); ?>
<?php $subtitulo = _BUSQUEDA_AVANZADA_RESULTADO; ?>
<?php $vista->setTituloPagina( $subtitulo ); ?>
<?php $vista->iniciarPagina(); ?>
<?php $vista->iniciarContenido(); ?>

<?php


$error=false;

$tab = 'personaje';
$sq = $_GET['SQ0']; //SQ0 valor del campo
$fo = $_GET['FO0']; //FO0 indica el campo FIELD
$nc = $_GET['NC0']; //Operadores lógicos
$ord= intval($_GET['ord']);

$foALL=$fo;

while($i<intval($_GET['busquedaNCampos']+1)) {
	if($_GET['SQ'.$i] != "") {
		$foALL = $foALL.",".$_GET['FO'.$i];
	}
	$i++;
}

//echo "valor de foALL:", $foALL, "<br/>";

function campoTabla($patron,$cadenaCampos){
	return preg_match($patron,$cadenaCampos);
}

/*function campoTablaLugar($fo){
	if (strpos($fo,"lugar")===false)
		return false;
	return true;
}*/

$FLAGnisba=campoTabla("/nisba/",$foALL);
$FLAGfamilia=campoTabla("/familia/",$foALL);
$FLAGlugar=campoTabla("/lugar/",$foALL);
$FLAGcargo=campoTabla("/cargo/",$foALL);
$FLAGactividad=campoTabla("/actividad/",$foALL);
$FLAGobra=campoTabla("/obra/",$foALL);
$FLAGfuente=campoTabla("/fuente/",$foALL);

if($fo!="" and $sq != "") { 
	$i = 0;
	$sql = "SELECT personaje.* FROM $tab";
	
	if ($FLAGnisba) {//Hacer JOIN
		$sql = $sql." LEFT JOIN personaje_nisba ON personaje.idPersonaje=personaje_nisba.idPersonaje LEFT JOIN nisba ON personaje_nisba.idNisba=nisba.idNisba";		
	} 
	
	if ($FLAGfamilia) {
		$sql = $sql." LEFT JOIN familia ON personaje.idFamilia=familia.idFamilia";
	}
	
	if ($FLAGcargo) {
		$sql = $sql." LEFT JOIN personaje_cargo ON personaje.idPersonaje=personaje_cargo.idPersonaje LEFT JOIN cargo ON personaje_cargo.idCargo=cargo.idCargo";
	}
	
	if ($FLAGactividad) {
		$sql = $sql." LEFT JOIN personaje_actividad ON personaje.idPersonaje=personaje_actividad.idPersonaje LEFT JOIN actividad ON personaje_actividad.idActividad=actividad.idActividad";
	}
	
	if ($FLAGobra) {
		$sql = $sql." LEFT JOIN personaje_obra ON personaje.idPersonaje=personaje_obra.idPersonaje";
	}
	
	if ($FLAGfuente) {
		$sql = $sql." LEFT JOIN personaje_fuente ON personaje.idPersonaje=personaje_fuente.idPersonaje LEFT JOIN fuente ON personaje_fuente.idFuente=fuente.idFuente";
	}
	
	
	if ($FLAGlugar) {
		$sql = $sql." LEFT JOIN personaje_lugar ON personaje.idPersonaje=personaje_lugar.idPersonaje LEFT JOIN lugar ON personaje_lugar.idLugar=lugar.idLugar";
		$sql= $sql. " WHERE (idTipoRelacionLugar=24 OR idTipoRelacionLugar=50) AND ";
	}	else{
		$sql=$sql." WHERE";
	}
	
	
	
	while($i<intval($_GET['busquedaNCampos'])+1) {
		if($_GET['SQ'.$i] != "") {
			$opX = $_GET['OP'.$i];
			$foX = $_GET['FO'.$i];
			$sqX = $_GET['SQ'.$i];
			$ncX = $_GET['NC'.$i];					
			
			switch($foX) {
				case 'idPersonaje':
				case 'nacimiento':
				case 'nacimientoec':
				case 'muerte':
				case 'muerteec':
				case 'edad':
				case 'publicado':
					$sqX = intval($sqX);
					$numadd = null;
					switch($ncX) {
						case 1: $numadd = '>';  break;
						case 2: $numadd = '>='; break;
						case 3: $numadd = '<';  break;
						case 4:	$numadd = '<='; break;
						case 5:	$numadd = '!='; break;
						case 0: default:$numadd = '=';
					}
					$sentencia = "$numadd ".$sqX;
					break;
				default: //Campos de texto
					$sentencia = "LIKE '%$sqX%'";
			}
			
			if ($i != 0) {
				$opadd = null;
				switch ($opX) {
					case 1: $opadd = 'OR'; break;
					case 2: $opadd = 'AND NOT'; break;
					case 3: $opadd = 'OR NOT'; break;
					case 0: default: $opadd = 'AND'; 
				}
			}
			$sql .= " $opadd $foX $sentencia";
		}
		$i++;
		
	}
	if ($fo != "") {
		$sql .= " ORDER BY $fo ";
		if ($ord == 0) {
			$sql .= "DESC";
		}
		else {
			$sql .= "ASC";
		}
	}
	
	if(tieneAcceso($privilegios['admin'])) {
		$limites = '';
	}
	else {
		$limites = "LIMIT "._MAX_RESULTS_GUEST;
		$limitado = true;
	}
		
	$sql .= " " . $limites;
}
else {
	$error=true;
}

if ($error) {
	echo _ERR_EMPTY_DATA;
	?>
<div class="limpieza mediosalto"></div>
<form action="busqueda_avanzada.php" method="post" class="fleft">
<button type="submit"><?php echo _REINTENTAR; ?></button>
</form>
<div class="limpieza mediosalto"></div>
	<?php
}
else {
	include_once('./formulario_busqueda_adv.php.inc');
	//echo 'QUERY: ',$sql, "<br/>";
	$miconexion= new DB_mysql();
	$miconexion->conectar();
	$registrosPorPagina = 20;
	$result=$miconexion->consultaPaginada($sql,$registrosPorPagina);
	

	if($_GET['page'] && $_GET['page']!="") {
		$paginaActual = $_GET['page'];
	}
	else {
		$paginaActual = 1;
	}
	
	if($result==0) {
		echo $miconexion->Error;
		
	} else {
	
	//AQUI MOSTRAMOS EL FORMULARIO PARA GUARDAR LA BUQUEDA COMO FAVORITA
	/*
	if($_SESSION['tipoUsuario']<6) {
		if($miconexion->numregistros()>0) { 
			$vista->mostrarFormGuardarBusqueda($_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'],$sql); 	
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
	<?php echo _COINCIDENCIAS . " " . $de . " - ". $hasta . _DE . " " . $miconexion->numregistros() . " " .$extra; ?>
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
		<br />
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
			case "fuentesc":
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
		
		$vista->paginacion($sql,$registrosPorPagina,3);
	} else {
	?>
		<div><?php echo _MSG_SIN_COINCIDENCIAS;?></div>
		<br class="limpieza" />
	<?php
	}
	}
}
	?>
 </div> 

	
<?php $vista->terminarContenido();	?>
<?php $vista->terminarPagina();	?>


