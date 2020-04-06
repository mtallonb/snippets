<?php 
/*
$est=new Stat(); 
echo ($est->getNumRegistros());
*/
?>

    <div id="divBusqueda">
	<form id="formbusqueda" class="formbusqueda" name="formbusqueda" action="./resultado_busqueda_simple.php" method="get" >
		<input id="tipoBusqueda" name="tipoBusqueda" type="hidden" value="simple" />
		<?php //$vista->mostrarTablasBusqueda("personaje");
		$campoget = @$_GET['campo'];
		$campoget = str_replace('\"', '"', $campoget); 
		$modoget = @$_GET['modo'];
		$modooget = intval(str_replace('\"', '"', $modoget)); 
		$numcompget = @$_GET['numcomp'];
		$numcompget = intval(str_replace('\"', '"', $numcompget)); 
		$ordenget = @$_GET['orden'];
		$ordenget = intval(str_replace('\"', '"', $ordenget)); 
		
		// Limpiamos los valores numericos antes de insertarlos en el cuadro de b�squeda
		switch($campoget) {
			case "edad":
			case "nacimiento":
			case "muerte":
			case "nacimientoec":
			case "muerteec":
			case "idp":
				$busqueda=intval($busqueda);
		}
		?>

	  <select name="numcomp" class="opnum" id="numcomp">
				<option value="0"<?php if($numcompget==0){?> selected="selected"<?php }?>><?php echo htmlentities('=')?></option>
                <option value="1"<?php if($numcompget==1){?> selected="selected"<?php }?>><?php echo htmlentities('>')?></option>
				<option value="2"<?php if($numcompget==2){?> selected="selected"<?php }?>>&ge;</option>
				<option value="3"<?php if($numcompget==3){?> selected="selected"<?php }?>><?php echo htmlentities('<')?></option>
                <option value="4"<?php if($numcompget==4){?> selected="selected"<?php }?>>&le;</option>
				<option value="5"<?php if($numcompget==5){?> selected="selected"<?php }?>>&ne;</option>
      </select> 
		<input name="txtbusqueda" class="iparcial medio txtbusqueda" type="text" id="txtbusqueda" value="<?php echo htmlspecialchars(stripslashes($busqueda))?>" style="direction:rtl;" />
		
		  <select name="campo" class="selcampos" id="campo" onchange="direccion('campo', 'txtbusqueda');">
				<option value="todos"<?php if($campoget=="todos"){?> selected="selected"<?php }?> style="color:#0000FF"><?php echo _CUALQUIER_CAMPO?></option>
				<option value="idp"<?php if($campoget=="idp"){?> selected="selected"<?php }?> style="color:#AA0011"><?php echo _IDPERSONAJE?></option>
                <option value="nombrea"<?php if($campoget=="nombrea"){?> selected="selected"<?php }?>><?php echo _NAMEA?></option>
				<option value="nombree"<?php if($campoget=="nombree"){?> selected="selected"<?php }?>><?php echo _NAMEE?></option>
                <option value="suhra"<?php if($campoget=="suhra"){?> selected="selected"<?php }?>><?php echo _SUHRA?></option>
                <option value="nisba"<?php if($campoget=="nisba"){?> selected="selected"<?php }?>><?php echo _NISBA?></option>
				<option value="comentarios"<?php if($campoget=="comentarios"){?> selected="selected"<?php }?>><?php echo _COMENTARIOS?></option>
				<?php if (tieneAcceso($privilegios['researcher'])) { ?>
                <option value="otros"<?php if($campoget=="otros"){?> selected="selected"<?php }?>><?php echo _OTROS?></option>
                <option value="notas"<?php if($campoget=="notas"){?> selected="selected"<?php }?>><?php echo _NOTAS?></option>
                <?php }?>
                <option value="resumenb"<?php if($campoget=="resumenb"){?> selected="selected"<?php }?>><?php echo _RESUMEN_BIO?></option>
                <option value="maestroso"<?php if($campoget=="maestroso"){?> selected="selected"<?php }?>><?php echo _MAESTROS_O?></option>
                <option value="fuentesc"<?php if($campoget=="fuentesc"){?> selected="selected"<?php }?>><?php echo _FUENTES_CITADAS?></option>
                <option value="nacimiento"<?php if($campoget=="nacimiento"){?> selected="selected"<?php }?> style="color:#00AA11"><?php echo _NACIMIENTO?></option>
				<option value="nacimientoec"<?php if($campoget=="nacimientoec"){?> selected="selected"<?php }?> style="color:#00AA11"><?php echo _NACIMIENTO_EC?></option>
                <option value="muerte"<?php if($campoget=="muerte"){?> selected="selected"<?php }?> style="color:#00AA11"><?php echo _MUERTE?></option>
				<option value="muerteec"<?php if($campoget=="muerteec"){?> selected="selected"<?php }?> style="color:#00AA11"><?php echo _MUERTE_EC?></option>
                <option value="edad"<?php if($campoget=="edad"){?> selected="selected"<?php }?> style="color:#AA0011"><?php echo _EDAD?></option>
                
				
      </select> <input id="envio" type="submit" value="<?php echo _SEARCH; ?>" name="envio" />
      <div class="mediosalto"></div>
	  <div id="modo_busqueda">
	  <input type="radio" name="modo" value="0" <?php if($modoget==0) { ?>checked="checked"<?php } ?>/><?php echo _ANY_WORD; ?>
	  <input type="radio" name="modo" value="1" <?php if($modoget==1) { ?>checked="checked"<?php } ?> /><?php echo _ALL_WORDS; ?>
	  </div>
	  <div id="orden_busqueda">
	  <input type="radio" name="orden" value="0" <?php if($ordenget==0) { ?>checked="checked"<?php } ?>/><?php echo _DESCENDENTE; ?>
	  <input type="radio" name="orden" value="1" <?php if($ordenget==1) { ?>checked="checked"<?php } ?>/><?php echo _ASCENDENTE; ?>
	  </div>
	</form>
<script language="javascript" type="text/javascript">
	function direccion (campo, elemento) {
		var c = document.getElementById(campo).value;
		if(c=="nombrea" || c=="suhra" || c=="nisba" || c=="fuentesc") {
			document.getElementById(elemento).style.direction = 'rtl';
			
		} else {
			document.getElementById(elemento).style.direction = 'ltr';
		}
		switch(c) {
			case "edad":
			case "muerte":
			case "nacimiento":
			case "muerteec":
			case "nacimientoec":
			case "idp":
				document.getElementById('numcomp').style.display = 'inline';
				document.getElementById('orden_busqueda').style.display = 'inline';
				document.getElementById('modo_busqueda').style.display = 'none';
				break;
			default:
				document.getElementById('numcomp').style.display = 'none';
				document.getElementById('orden_busqueda').style.display = 'none';
				document.getElementById('modo_busqueda').style.display = 'inline';
		}
	}
	direccion('campo', 'txtbusqueda');
</script>
	</div>
	<div class="limpieza mediosalto"></div>