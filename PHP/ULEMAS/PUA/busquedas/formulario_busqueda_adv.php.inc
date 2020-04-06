<?php

function get_values($indice)
{
	return array($_GET['SQ'.$indice],$_GET['FO'.$indice],$_GET['NC'.$indice],$_GET['OP'.$indice]);
}

list ($sq0, $fo0, $nc0, $op0) = get_values(0);

?>	

<script  type="text/javascript">

function selectItemByValue(elmnt, value){

    for(var i=0; i < elmnt.options.length; i++)
    {
      if(elmnt.options[i].value == value)
        elmnt.selectedIndex = i;
    }
  }

function setbusquedaNCampos(ncampos)
{
   //formName is the name of your form
   document.formbusqueda.busquedaNCampos.value = ncampos;
}

var nCondiciones = 1;

function addRegCondicion( sqX, foX, ncX, opX) {
	
	var newdiv = document.createElement('div');
	var ni = document.getElementById('condicionesRegistros');
	var divIdName = 'row'+nCondiciones;
	newdiv.setAttribute('id',divIdName);
	newdiv.innerHTML  = '<div id="row'+nCondiciones+'">'+
						'<select name="OP'+nCondiciones+'" class="yono" id="yono'+nCondiciones+'" style="display:inline;">'+
						'<option value="0" <?php if($opX==0){?> selected="selected"<?php }?>><?php echo _BUSQUEDA_AVANZADA_Y?><\/option>'+
						'<option value="1" <?php if($opX==1){?> selected="selected"<?php }?>><?php echo _BUSQUEDA_AVANZADA_O?><\/option>'+
						'<option value="2" <?php if($opX==2){?> selected="selected"<?php }?>><?php echo _BUSQUEDA_AVANZADA_YNO?><\/option>'+
						'<option value="3" <?php if($opX==3){?> selected="selected"<?php }?>><?php echo _BUSQUEDA_AVANZADA_ONO?><\/option>'+
						'<\/select> '+
						'<select name="NC'+nCondiciones+'" class="opnum" id="numcomp'+nCondiciones+'" style="display:inline;">'+
						'<option value="0" <?php if($ncX==0){?> selected="selected"<?php }?>><?php echo htmlentities('=')?><\/option>'+
						'<option value="1" <?php if($ncX==1){?> selected="selected"<?php }?>><?php echo htmlentities('>')?><\/option>'+
						'<option value="2" <?php if($ncX==2){?> selected="selected"<?php }?>>&ge;<\/option>'+
						'<option value="3" <?php if($ncX==3){?> selected="selected"<?php }?>><?php echo htmlentities('<')?><\/option>'+
						'<option value="4" <?php if($ncX==4){?> selected="selected"<?php }?>>&le;<\/option>'+
						'<option value="5" <?php if($ncX==5){?> selected="selected"<?php }?>>&ne;<\/option>'+
						'<\/select> '+
						'<input name="SQ'+nCondiciones+'" value="'+sqX+'" class="iparcial medio txtbusqueda" maxlength="310" accesskey="v" tabindex="1" type="text" id="txtbusqueda'+nCondiciones+'" style="direction:ltr;" \/> '+
						'<select name="FO'+nCondiciones+'" id="campo'+nCondiciones+'" tabindex="2" class="selcampos" onchange="javascript:direccion(\'campo'+nCondiciones+'\',\'txtbusqueda'+nCondiciones+'\','+nCondiciones+');">' +
					
						'<option value="personaje.idPersonaje" <?php if($foX=="personaje.idPersonaje"){?> selected="selected"<?php }?>><?php if (defined("_".strtoupper("idPersonaje"))) { echo constant("_".strtoupper("idPersonaje")); } else { echo "_".strtoupper($nom); } ?><\/option>' +						
						'<option value="personaje.nombreA" <?php if($foX=="personaje.nombreA"){?> selected="selected"<?php }?>><?php if (defined("_".strtoupper("nombreA"))) { echo constant("_".strtoupper("nombreA")); } else { echo "_".strtoupper($nom); } ?><\/option>' +
						'<option value="personaje.nombreE" <?php if($foX=="personaje.nombreE"){?> selected="selected"<?php }?>><?php if (defined("_".strtoupper("nombreE"))) { echo constant("_".strtoupper("nombreE")); } else { echo "_".strtoupper($nom); } ?><\/option>' +
						
						'<option value="suhra" <?php if($foX=="suhra"){?> selected="selected"<?php }?>><?php if (defined("_".strtoupper("suhra"))) { echo constant("_".strtoupper("suhra")); } else { echo "_".strtoupper($nom); } ?><\/option>'+		
						'<option value="muerte" <?php if($foX=="muerte"){?> selected="selected"<?php }?>><?php if (defined("_".strtoupper("muerte"))) { echo constant("_".strtoupper("muerte")); } else { echo "_".strtoupper($nom); } ?><\/option>'+
						'<option value="muerteec" <?php if($foX=="muerteec"){?> selected="selected"<?php }?>><?php if (defined("_".strtoupper("muerteec"))) { echo constant("_".strtoupper("muerteec")); } else { echo "_".strtoupper($nom); } ?><\/option>'+
						'<option value="nacimiento" <?php if($foX=="nacimiento"){?> selected="selected"<?php }?>><?php if (defined("_".strtoupper("nacimiento"))) { echo constant("_".strtoupper("nacimiento")); } else { echo "_".strtoupper($nom); } ?><\/option>'+
						'<option value="nacimientoec" <?php if($foX=="nacimientoec"){?> selected="selected"<?php }?>><?php if (defined("_".strtoupper("nacimientoec"))) { echo constant("_".strtoupper("nacimientoec")); } else { echo "_".strtoupper($nom); } ?><\/option>'+
						'<option value="edad" <?php if($foX=="edad"){?> selected="selected"<?php }?>><?php if (defined("_".strtoupper("edad"))) { echo constant("_".strtoupper("edad")); } else { echo "_".strtoupper($nom); } ?><\/option>'+
									
						'<option value="nisba.nombre" <?php if($foX=="nisba.nombre"){?> selected="selected"<?php }?>>Nisba<\/option>'+
						'<option value="nisba.nombre_castellano" <?php if($foX=="nisba.nombre_castellano"){?> selected="selected"<?php }?>>Nisba (Castellano)<\/option>'+
						'<option value="familia.nombre" <?php if($foX=="familia.nombre"){?> selected="selected"<?php }?>>Familia<\/option>'+
						'<option value="familia.nombreE" <?php if($foX=="familia.nombreE"){?> selected="selected"<?php }?>>Familia (Castellano)<\/option>'+
						'<option value="lugar.nombre" <?php if($foX=="lugar.nombre"){?> selected="selected"<?php }?>>Lugar<\/option>'+
						'<option value="lugar.nombre_castellano" <?php if($foX=="lugar.nombre_castellano"){?> selected="selected"<?php }?>>Lugar (Castellano)<\/option>'+
						'<option value="cargo.nombre" <?php if($foX=="cargo.nombre"){?> selected="selected"<?php }?>>Cargo<\/option>'+
						'<option value="cargo.nombre_castellano" <?php if($foX=="cargo.nombre_castellano"){?> selected="selected"<?php }?>>Cargo (Castellano)<\/option>'+
						'<option value="actividad.nombre" <?php if($foX=="actividad.nombre"){?> selected="selected"<?php }?>>Actividad <\/option>'+
						'<option value="personaje_obra.tituloObra" <?php if($foX=="personaje_obra.tituloObra"){?> selected="selected"<?php }?>>Obra (Título) <\/option>'+
						'<option value="fuente.sigla" <?php if($foX=="fuente.sigla"){?> selected="selected"<?php }?>>Fuente (Sigla) <\/option>'+
						'<option value="fuente.titulo" <?php if($foX=="fuente.titulo"){?> selected="selected"<?php }?>>Fuente (Título) <\/option>'+
									
						<?php if(tieneAcceso($privilegios['researcher'])){ ?>
							'<option value="notas" <?php if($foX=="notas"){?> selected="selected"<?php }?>><?php if (defined("_".strtoupper("notas"))) { echo constant("_".strtoupper("notas")); } else { echo "_".strtoupper($nom); } ?><\/option>'+				
							'<option value="otros" <?php if($foX=="otros"){?> selected="selected"<?php }?>> <?php if (defined("_".strtoupper("otros"))) { echo constant("_".strtoupper("otros")); } else { echo "_".strtoupper($nom); } ?><\/option>'+				
							'<option value="maestrosOrientales" <?php if($foX=="maestrosOrientales"){?> selected="selected"<?php }?>><?php if (defined("_".strtoupper("maestrosOrientales"))) { echo constant("_".strtoupper("maestrosOrientales")); } else { echo "_".strtoupper($nom); } ?><\/option>'+				
							'<option value="fuentesCitadas" <?php if($foX=="fuentesCitadas"){?> selected="selected"<?php }?>><?php if (defined("_".strtoupper("fuentesCitadas"))) { echo constant("_".strtoupper("fuentesCitadas")); } else { echo "_".strtoupper($nom); } ?><\/option>'+				
							'<option value="creado" <?php if($foX=="creado"){?> selected="selected"<?php }?>><?php if (defined("_".strtoupper("creado"))) { echo constant("_".strtoupper("creado")); } else { echo "_".strtoupper($nom); } ?><\/option>'+				
							'<option value="modificado" <?php if($foX=="modificado"){?> selected="selected"<?php }?>><?php if (defined("_".strtoupper("modificado"))) { echo constant("_".strtoupper("modificado")); } else { echo "_".strtoupper($nom); } ?><\/option>'+				
							'<option value="publicado" <?php if($foX=="publicado"){?> selected="selected"<?php }?>><?php if (defined("_".strtoupper("publicado"))) { echo constant("_".strtoupper("publicado")); } else { echo "_".strtoupper($nom); } ?><\/option>'+
							
						<?php }?>	
						'<\/select> '+
						'<a href="javascript:delRegCondicion(\'row'+nCondiciones+'\')"><?php echo _BUSQUEDA_AVANZADA_DEL_CONDICION?><\/a>'+
						'<\/div>';
	
	
	ni.appendChild(newdiv);
	updateRegCondicion();

	var yono = document.getElementById('yono'+nCondiciones );
	selectItemByValue(yono, opX);

	var campo = document.getElementById('campo'+nCondiciones );
	selectItemByValue(campo, foX);

	var numcomp = document.getElementById('numcomp'+nCondiciones );
	selectItemByValue(numcomp, ncX);
	
	nCondiciones++;
	//kb();
}
function delRegCondicion(divid) {
  var d = document.getElementById('condicionesRegistros');
  var olddiv = document.getElementById(divid);
  d.removeChild(olddiv);
  nCondiciones--;
}
function updateRegCondicion() {
	var ncamposinput = document.getElementById('busquedaNCampos');
	ncamposinput.value=nCondiciones;
}

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

</script>

<div id="divBusqueda">
<form name="formbusqueda" class="formbusqueda"  id="formbusqueda" method="get" action="resultado_busqueda_adv.php">	
	<div id="row0">
		<input id="busquedaNCampos" type="hidden" name="busquedaNCampos" />
	  <select name="NC0" class="opnum" id="numcomp0" style="display:inline;">
				<option value="0" <?php if($nc0==0){?> selected="selected"<?php }?>><?php echo htmlentities('=')?></option>
                <option value="1" <?php if($nc0==1){?> selected="selected"<?php }?>><?php echo htmlentities('>')?></option>
				<option value="2" <?php if($nc0==2){?> selected="selected"<?php }?>>&ge;</option>
				<option value="3" <?php if($nc0==3){?> selected="selected"<?php }?>><?php echo htmlentities('<')?></option>
                <option value="4" <?php if($nc0==4){?> selected="selected"<?php }?>>&le;</option>			
				<option value="5" <?php if($nc0==5){?> selected="selected"<?php }?>>&ne;</option>
      </select> 
		<input name="SQ0" value="<?php echo $sq0?>" class="iparcial medio txtbusqueda" maxlength="310" accesskey="v" tabindex="1" type="text" id="txtbusqueda0" style="direction:ltr;"/>

		<select name="FO0" id="campo0" tabindex="2" class="selcampos" onchange="javascript:direccion('campo0','txtbusqueda0',0);">
						
			<option value="personaje.idPersonaje" <?php if($fo0=="personaje.idPersonaje"){?> selected="selected"<?php }?>><?php if (defined("_".strtoupper("idPersonaje"))) { echo constant("_".strtoupper("idPersonaje")); } else { echo "_".strtoupper($nom); } ?></option>						
			<option value="personaje.nombreA" <?php if($fo0=="personaje.nombreA"){?> selected="selected"<?php }?>><?php if (defined("_".strtoupper("nombreA"))) { echo constant("_".strtoupper("nombreA")); } else { echo "_".strtoupper($nom); } ?></option>
			<option value="personaje.nombreE" <?php if($fo0=="personaje.nombreE"){?> selected="selected"<?php }?>><?php if (defined("_".strtoupper("nombreE"))) { echo constant("_".strtoupper("nombreE")); } else { echo "_".strtoupper($nom); } ?></option>
			
			<option value="suhra" <?php if($fo0=="suhra"){?> selected="selected"<?php }?>><?php if (defined("_".strtoupper("suhra"))) { echo constant("_".strtoupper("suhra")); } else { echo "_".strtoupper($nom); } ?></option>		
			<option value="muerte" <?php if($fo0=="muerte"){?> selected="selected"<?php }?>><?php if (defined("_".strtoupper("muerte"))) { echo constant("_".strtoupper("muerte")); } else { echo "_".strtoupper($nom); } ?></option>
			<option value="muerteec" <?php if($fo0=="muerteec"){?> selected="selected"<?php }?>><?php if (defined("_".strtoupper("muerteec"))) { echo constant("_".strtoupper("muerteec")); } else { echo "_".strtoupper($nom); } ?></option>
			<option value="nacimiento" <?php if($fo0=="nacimiento"){?> selected="selected"<?php }?>><?php if (defined("_".strtoupper("nacimiento"))) { echo constant("_".strtoupper("nacimiento")); } else { echo "_".strtoupper($nom); } ?></option>
			<option value="nacimientoec" <?php if($fo0=="nacimientoec"){?> selected="selected"<?php }?>><?php if (defined("_".strtoupper("nacimientoec"))) { echo constant("_".strtoupper("nacimientoec")); } else { echo "_".strtoupper($nom); } ?></option>
			<option value="edad" <?php if($fo0=="edad"){?> selected="selected"<?php }?>><?php if (defined("_".strtoupper("edad"))) { echo constant("_".strtoupper("edad")); } else { echo "_".strtoupper($nom); } ?></option>
						
			<option value="nisba.nombre" <?php if($fo0=="nisba.nombre"){?> selected="selected"<?php }?>>Nisba</option>
			<option value="nisba.nombre_castellano" <?php if($fo0=="nisba.nombre_castellano"){?> selected="selected"<?php }?>>Nisba (Castellano)</option>
			<option value="familia.nombre" <?php if($fo0=="familia.nombre"){?> selected="selected"<?php }?>>Familia</option>
			<option value="familia.nombreE" <?php if($fo0=="familia.nombreE"){?> selected="selected"<?php }?>>Familia (Castellano)</option>
			<option value="lugar.nombre" <?php if($fo0=="lugar.nombre"){?> selected="selected"<?php }?>>Lugar</option>
			<option value="lugar.nombre_castellano"<?php if($fo0=="lugar.nombre_castellano"){?> selected="selected"<?php }?>>Lugar (Castellano)</option>
			<option value="cargo.nombre" <?php if($fo0=="cargo.nombre"){?> selected="selected"<?php }?>>Cargo</option>
			<option value="cargo.nombre_castellano" <?php if($fo0=="cargo.nombre_castellano"){?> selected="selected"<?php }?>>Cargo (Castellano)</option>
			<option value="actividad.nombre" <?php if($fo0=="actividad.nombre"){?> selected="selected"<?php }?>>Actividad </option> 
			<option value="personaje_obra.tituloObra" <?php if($fo0=="personaje_obra.tituloObra"){?> selected="selected"<?php }?>>Obra (Título) </option>
			<option value="fuente.sigla" <?php if($fo0=="fuente.sigla"){?> selected="selected"<?php }?>>Fuente (Sigla) </option>
			<option value="fuente.titulo" <?php if($fo0=="fuente.titulo"){?> selected="selected"<?php }?>>Fuente (Título) </option>
						
			<?php if(tieneAcceso($privilegios['researcher'])){ ?>
				<option value="notas" <?php if($fo0=="notas"){?> selected="selected"<?php }?>><?php if (defined("_".strtoupper("notas"))) { echo constant("_".strtoupper("notas")); } else { echo "_".strtoupper($nom); } ?></option>				
				<option value="otros" <?php if($fo0=="otros"){?> selected="selected"<?php }?>><?php if (defined("_".strtoupper("otros"))) { echo constant("_".strtoupper("otros")); } else { echo "_".strtoupper($nom); } ?></option>				
				<option value="maestrosOrientales" <?php if($fo0=="maestrosOrientales"){?> selected="selected"<?php }?>><?php if (defined("_".strtoupper("maestrosOrientales"))) { echo constant("_".strtoupper("maestrosOrientales")); } else { echo "_".strtoupper($nom); } ?></option>				
				<option value="fuentesCitadas" <?php if($fo0=="fuentesCitadas"){?> selected="selected"<?php }?>><?php if (defined("_".strtoupper("fuentesCitadas"))) { echo constant("_".strtoupper("fuentesCitadas")); } else { echo "_".strtoupper($nom); } ?></option>				
				<option value="creado" <?php if($fo0=="creado"){?> selected="selected"<?php }?>><?php if (defined("_".strtoupper("creado"))) { echo constant("_".strtoupper("creado")); } else { echo "_".strtoupper($nom); } ?></option>				
				<option value="modificado" <?php if($fo0=="modificado"){?> selected="selected"<?php }?>><?php if (defined("_".strtoupper("modificado"))) { echo constant("_".strtoupper("modificado")); } else { echo "_".strtoupper($nom); } ?></option>				
				<option value="publicado" <?php if($fo0=="publicado"){?> selected="selected"<?php }?>><?php if (defined("_".strtoupper("publicado"))) { echo constant("_".strtoupper("publicado")); } else { echo "_".strtoupper($nom); } ?></option>
				
			<?php }?>			
		</select>
		<select id="orden_busqueda_avanzado" class="" name="ord">
		  <option value="0" <?php if($opX==0) { ?>selected="selected"<?php } ?>><?php echo _DESCENDENTE; ?></option>
		  <option value="1" <?php if($opX==1) { ?>selected="selected"<?php } ?>><?php echo _ASCENDENTE; ?></option>
		</select>
	</div>
	
<?php	

if ($_GET['busquedaNCampos'] != ""){
	$indR=0;
	$busquedaNCampos=intval($_GET['busquedaNCampos']);
	while($indR<=$busquedaNCampos) {			
		//echo "valor de busquedaNCampos:", $busquedaNCampos, "<br/>";	
		if ($indR > 0){
			//Crear un nuevo registro de busqueda
			
			list ($sqX, $foX, $ncX, $opX) = get_values($indR);
			//echo "valor de ncX:", $ncX, "<br/>";
			
			?>
				<div class="nuevoregistro" id="condicionesRegistros"></div>
				<script type="text/javascript"> 
					addRegCondicion('<?php echo $sqX; ?>', '<?php echo $foX; ?>', '<?php echo $ncX; ?>', '<?php echo $opX; ?>'); 
				</script> 
				<?php 
				
		}
		
		$indR++;
	
	}
}
?>
									
<div class="nuevoregistro" id="condicionesRegistros"></div>
<a href="javascript:addRegCondicion('','0','0','0')"><?php echo _BUSQUEDA_AVANZADA_ADD_CONDICION;?></a>


	  
    <!-- add/remove search/clear -->    
	<input id="buscar" name="buscar" type="submit" onclick="setbusquedaNCampos(nCondiciones-1)" value="<?php echo _DO_SEARCH;?>" /> 
	</form>
</div>
	<div class="limpieza mediosalto"></div>
	