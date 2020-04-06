<?php include_once('../comun.inc.php'); ?>
<?php controlAcceso($privilegios['guest']); ?>
<?php $vista = new Vista(); ?>
<?php $_SESSION['Config_diractual']='../'; ?>
<?php $vista->cabecera($_SESSION['Config_diractual'].'css/'.$Config_css,$_SESSION['Config_diractual'].$Config_dirIdiomas,$subtitulo); ?>
<?php $subtitulo = _PN_BUSQUEDA_AVANZADA; ?>
<?php $vista->setTituloPagina( $subtitulo ); ?>
<?php $vista->setKB( true, 'txtbusqueda0' ); ?>

<?php $vista->iniciarPagina(); ?>
<?php $vista->iniciarContenido(); ?>

<?php //$vista->ajaxTablaBusquedaADV(); ?>
<?php
$conexion = new DB_mysql();
$conexion->conectar();
$tabla = "personaje";
$resCampos = $conexion->consulta("SELECT * FROM $tabla LIMIT 1");
$_ncampos=0;
?>
<div id="divBusqueda">
<form name="formbusqueda" class="formbusqueda"  id="formbusqueda" method="get" action="resultado_busqueda_adv.php">
	<input id="busquedaNCampos" type="hidden" value="<?php echo $_ncampos;?>" name="busquedaNCampos" /> 
	<div id="row0">
	  <select name="NC<?php echo $_ncampos;?>" class="opnum" id="numcomp<?php echo $_ncampos;?>" style="display:inline;">
				<option value="0"<?php if($numcompget==0){?> selected="selected"<?php }?>><?php echo htmlentities('=')?></option>
                <option value="1"<?php if($numcompget==1){?> selected="selected"<?php }?>><?php echo htmlentities('>')?></option>
				<option value="2"<?php if($numcompget==2){?> selected="selected"<?php }?>>&ge;</option>
				<option value="3"<?php if($numcompget==3){?> selected="selected"<?php }?>><?php echo htmlentities('<')?></option>
                <option value="4"<?php if($numcompget==4){?> selected="selected"<?php }?>>&le;</option>
			
			<option value="5"<?php if($numcompget==5){?> selected="selected"<?php }?>>&ne;</option>
      </select> 
		<input name="SQ<?php echo $_ncampos;?>" value="" class="iparcial medio txtbusqueda" maxlength="310" accesskey="v" tabindex="1" type="text" id="txtbusqueda<?php echo $_ncampos;?>" style="direction:ltr;"/>

		<select name="FO<?php echo $_ncampos;?>" id="campo<?php echo $_ncampos;?>" tabindex="2" class="selcampos" onchange="javascript:direccion('campo<?php echo $_ncampos;?>','txtbusqueda<?php echo $_ncampos;?>',<?php echo $_ncampos;?>);">
						
			<option value="personaje.idPersonaje"><?php if (defined("_".strtoupper("idPersonaje"))) { echo constant("_".strtoupper("idPersonaje")); } else { echo "_".strtoupper($nom); } ?></option>						
			<option value="personaje.nombreA"><?php if (defined("_".strtoupper("nombreA"))) { echo constant("_".strtoupper("nombreA")); } else { echo "_".strtoupper($nom); } ?></option>
			<option value="personaje.nombreE"><?php if (defined("_".strtoupper("nombreE"))) { echo constant("_".strtoupper("nombreE")); } else { echo "_".strtoupper($nom); } ?></option>
			
			<option value="suhra"><?php if (defined("_".strtoupper("suhra"))) { echo constant("_".strtoupper("suhra")); } else { echo "_".strtoupper($nom); } ?></option>		
			<option value="muerte"><?php if (defined("_".strtoupper("muerte"))) { echo constant("_".strtoupper("muerte")); } else { echo "_".strtoupper($nom); } ?></option>
			<option value="muerteec"><?php if (defined("_".strtoupper("muerteec"))) { echo constant("_".strtoupper("muerteec")); } else { echo "_".strtoupper($nom); } ?></option>
			<option value="nacimiento"><?php if (defined("_".strtoupper("nacimiento"))) { echo constant("_".strtoupper("nacimiento")); } else { echo "_".strtoupper($nom); } ?></option>
			<option value="nacimientoec"><?php if (defined("_".strtoupper("nacimientoec"))) { echo constant("_".strtoupper("nacimientoec")); } else { echo "_".strtoupper($nom); } ?></option>
			<option value="edad"><?php if (defined("_".strtoupper("edad"))) { echo constant("_".strtoupper("edad")); } else { echo "_".strtoupper($nom); } ?></option>
						
			<option value="nisba.nombre">Nisba</option>
			<option value="nisba.nombre_castellano">Nisba (Castellano)</option>
			<option value="familia.nombre">Familia</option>
			<option value="familia.nombreE">Familia (Castellano)</option>
			<option value="lugar.nombre">Lugar</option>
			<option value="lugar.nombre_castellano">Lugar (Castellano)</option>
			<option value="cargo.nombre">Cargo</option>
			<option value="cargo.nombre_castellano">Cargo (Castellano)</option>
			<option value="actividad.nombre">Actividad </option> 
			<option value="personaje_obra.tituloObra">Obra (Título) </option>
			<option value="fuente.sigla">Fuente (Sigla) </option>
			<option value="fuente.titulo">Fuente (Título) </option>
						
			<?php if(tieneAcceso($privilegios['researcher'])){ ?>
				<option value="notas"><?php if (defined("_".strtoupper("notas"))) { echo constant("_".strtoupper("notas")); } else { echo "_".strtoupper($nom); } ?></option>				
				<option value="otros"><?php if (defined("_".strtoupper("otros"))) { echo constant("_".strtoupper("otros")); } else { echo "_".strtoupper($nom); } ?></option>				
				<option value="maestrosOrientales"><?php if (defined("_".strtoupper("maestrosOrientales"))) { echo constant("_".strtoupper("maestrosOrientales")); } else { echo "_".strtoupper($nom); } ?></option>				
				<option value="fuentesCitadas"><?php if (defined("_".strtoupper("fuentesCitadas"))) { echo constant("_".strtoupper("fuentesCitadas")); } else { echo "_".strtoupper($nom); } ?></option>				
				<option value="creado"><?php if (defined("_".strtoupper("creado"))) { echo constant("_".strtoupper("creado")); } else { echo "_".strtoupper($nom); } ?></option>				
				<option value="modificado"><?php if (defined("_".strtoupper("modificado"))) { echo constant("_".strtoupper("modificado")); } else { echo "_".strtoupper($nom); } ?></option>				
				<option value="publicado"><?php if (defined("_".strtoupper("publicado"))) { echo constant("_".strtoupper("publicado")); } else { echo "_".strtoupper($nom); } ?></option>
				
			<?php }?>			
		</select>
		<select id="orden_busqueda_avanzado" class="" name="ord">
		  <option value="0" <?php if($ordenget==0) { ?>selected="selected"<?php } ?>><?php echo _DESCENDENTE; ?></option>
		  <option value="1" <?php if($ordenget==1) { ?>selected="selected"<?php } ?>><?php echo _ASCENDENTE; ?></option>
		</select>
	</div>


<script language="javascript" type="text/javascript">
var nCondiciones = <?php echo $_ncampos+1;?>;
function addRegCondicion() {
	var newdiv = document.createElement('div');
	var ni = document.getElementById('condicionesRegistros');
	var divIdName = 'row'+nCondiciones;
	newdiv.setAttribute('id',divIdName);
	newdiv.innerHTML  = '<div id="row'+nCondiciones+'">'+
						'<select name="OP'+nCondiciones+'" class="yono" id="yono'+nCondiciones+'" style="display:inline;">'+
						'<option value="0" selected="selected"><?php echo _BUSQUEDA_AVANZADA_Y?><\/option>'+
						'<option value="1"><?php echo _BUSQUEDA_AVANZADA_O?><\/option>'+
						'<option value="2"><?php echo _BUSQUEDA_AVANZADA_YNO?><\/option>'+
						'<option value="3"><?php echo _BUSQUEDA_AVANZADA_ONO?><\/option>'+
						'<\/select> '+
						'<select name="NC'+nCondiciones+'" class="opnum" id="numcomp'+nCondiciones+'" style="display:inline;">'+
						'<option value="0" selected="selected"><?php echo htmlentities('=')?><\/option>'+
						'<option value="1"><?php echo htmlentities('>')?><\/option>'+
						'<option value="2">&ge;<\/option>'+
						'<option value="3"><?php echo htmlentities('<')?><\/option>'+
						'<option value="4">&le;<\/option>'+
						'<option value="5">&ne;<\/option>'+
						'<\/select> '+
						'<input name="SQ'+nCondiciones+'" value="" class="iparcial medio txtbusqueda" maxlength="310" accesskey="v" tabindex="1" type="text" id="txtbusqueda'+nCondiciones+'" style="direction:ltr;" \/> '+
						'<select name="FO'+nCondiciones+'" id="campo'+nCondiciones+'" tabindex="2" class="selcampos" onchange="javascript:direccion(\'campo'+nCondiciones+'\',\'txtbusqueda'+nCondiciones+'\','+nCondiciones+');">' +
					
						'<option value="personaje.idPersonaje"><?php if (defined("_".strtoupper("idPersonaje"))) { echo constant("_".strtoupper("idPersonaje")); } else { echo "_".strtoupper($nom); } ?><\/option>' +						
						'<option value="personaje.nombreA"><?php if (defined("_".strtoupper("nombreA"))) { echo constant("_".strtoupper("nombreA")); } else { echo "_".strtoupper($nom); } ?><\/option>' +
						'<option value="personaje.nombreE"><?php if (defined("_".strtoupper("nombreE"))) { echo constant("_".strtoupper("nombreE")); } else { echo "_".strtoupper($nom); } ?><\/option>' +
						
						'<option value="suhra"><?php if (defined("_".strtoupper("suhra"))) { echo constant("_".strtoupper("suhra")); } else { echo "_".strtoupper($nom); } ?><\/option>'+		
						'<option value="muerte"><?php if (defined("_".strtoupper("muerte"))) { echo constant("_".strtoupper("muerte")); } else { echo "_".strtoupper($nom); } ?><\/option>'+
						'<option value="muerteec"><?php if (defined("_".strtoupper("muerteec"))) { echo constant("_".strtoupper("muerteec")); } else { echo "_".strtoupper($nom); } ?><\/option>'+
						'<option value="nacimiento"><?php if (defined("_".strtoupper("nacimiento"))) { echo constant("_".strtoupper("nacimiento")); } else { echo "_".strtoupper($nom); } ?><\/option>'+
						'<option value="nacimientoec"><?php if (defined("_".strtoupper("nacimientoec"))) { echo constant("_".strtoupper("nacimientoec")); } else { echo "_".strtoupper($nom); } ?><\/option>'+
						'<option value="edad"><?php if (defined("_".strtoupper("edad"))) { echo constant("_".strtoupper("edad")); } else { echo "_".strtoupper($nom); } ?><\/option>'+
									
						'<option value="nisba.nombre">Nisba<\/option>'+
						'<option value="nisba.nombre_castellano">Nisba (Castellano)<\/option>'+
						'<option value="familia.nombre">Familia<\/option>'+
						'<option value="familia.nombreE">Familia (Castellano)<\/option>'+
						'<option value="lugar.nombre">Lugar<\/option>'+
						'<option value="lugar.nombre_castellano">Lugar (Castellano)<\/option>'+
						'<option value="cargo.nombre">Cargo<\/option>'+
						'<option value="cargo.nombre_castellano">Cargo (Castellano)<\/option>'+
						'<option value="actividad.nombre">Actividad <\/option>'+
						'<option value="personaje_obra.tituloObra">Obra (Título) <\/option>'+
						'<option value="fuente.sigla">Fuente (Sigla) <\/option>'+
						'<option value="fuente.titulo">Fuente (Título) <\/option>'+
									
						<?php if(tieneAcceso($privilegios['researcher'])){ ?>
							'<option value="notas"><?php if (defined("_".strtoupper("notas"))) { echo constant("_".strtoupper("notas")); } else { echo "_".strtoupper($nom); } ?><\/option>'+				
							'<option value="otros"><?php if (defined("_".strtoupper("otros"))) { echo constant("_".strtoupper("otros")); } else { echo "_".strtoupper($nom); } ?><\/option>'+				
							'<option value="maestrosOrientales"><?php if (defined("_".strtoupper("maestrosOrientales"))) { echo constant("_".strtoupper("maestrosOrientales")); } else { echo "_".strtoupper($nom); } ?><\/option>'+				
							'<option value="fuentesCitadas"><?php if (defined("_".strtoupper("fuentesCitadas"))) { echo constant("_".strtoupper("fuentesCitadas")); } else { echo "_".strtoupper($nom); } ?><\/option>'+				
							'<option value="creado"><?php if (defined("_".strtoupper("creado"))) { echo constant("_".strtoupper("creado")); } else { echo "_".strtoupper($nom); } ?><\/option>'+				
							'<option value="modificado"><?php if (defined("_".strtoupper("modificado"))) { echo constant("_".strtoupper("modificado")); } else { echo "_".strtoupper($nom); } ?><\/option>'+				
							'<option value="publicado"><?php if (defined("_".strtoupper("publicado"))) { echo constant("_".strtoupper("publicado")); } else { echo "_".strtoupper($nom); } ?><\/option>'+
							
						<?php }?>	
						'<\/select> '+
						'<a href="javascript:delRegCondicion(\'row'+nCondiciones+'\')"><?php echo _BUSQUEDA_AVANZADA_DEL_CONDICION?><\/a>'+
						'<\/div>';
	ni.appendChild(newdiv);
	updateRegCondicion();
	nCondiciones++;
	kb();
}
function delRegCondicion(divid) {
  var d = document.getElementById('condicionesRegistros');
  var olddiv = document.getElementById(divid);
  d.removeChild(olddiv);
}
function updateRegCondicion() {
	var ncamposinput = document.getElementById('busquedaNCampos');
	ncamposinput.value=nCondiciones;
}
</script>
	
<div class="nuevoregistro" id="condicionesRegistros"></div>
<a href="javascript:addRegCondicion()"><?php echo _BUSQUEDA_AVANZADA_ADD_CONDICION;?></a>
	
<script language="javascript" type="text/javascript">
	function direccion (campo, elemento, num) {
		var c = document.getElementById(campo).value;
		if(c=="nombreA" || c=="suhra" || c=="nisba.nombre" || c=="familia.nombre" || 
				c=="lugar.nombre" || c=="cargo.nombre" || c=="personaje_obra.tituloObra" ||
				c=="fuentesCitadas" ||	c=="actividad.nombre") {
			document.getElementById(elemento).style.direction = 'rtl';
			
		} else {
			document.getElementById(elemento).style.direction = 'ltr';
		}
		//A los siguientes les pone los operadores lógicos
		switch(c) {
			case "edad":
			case "muerte":
			case "nacimiento":
			case "muerteec":
			case "nacimientoec":
			case "idPersonaje":
			case "idFamilia":
			case "publicado	":
			case "creado":
			case "modificado":
				document.getElementById('numcomp'+num).style.display = 'inline';
				break;
			default:
				document.getElementById('numcomp'+num).style.display = 'none';
		}
	}
	direccion('campo', 'txtbusqueda');
</script>

	  <div id="row1" style="display: none;">
		SELECT_op_num 
		INPUT_busqueda
		SELECT_campo
		ANCHOR_Elimina_this
      </div>
	  
    <!-- add/remove search/clear -->
	<input id="buscar" name="buscar" type="submit" value="<?php echo _DO_SEARCH;?>" />
	</form>
</div>
	<div class="limpieza mediosalto"></div>
<?php $vista->terminarContenido();	?>
<?php $vista->terminarPagina();	?>
