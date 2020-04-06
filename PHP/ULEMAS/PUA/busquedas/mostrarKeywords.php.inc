    <?php $conexion = new DB_mysql(); ?>
	<?php $conexion->conectar(); ?>
	<div id="divKeywords">
		<?php

	$sql = "select * from keywords ORDER BY valor ASC";
	$result = $conexion->consulta($sql);
	?>
		
	<?php 	
	if($result && ($conexion->numregistros()>0))  { 
		?><div class="faq" style="float:left;"><?php
		echo _KEYWORD_LIST;
		for($i=0; $row = $conexion->filasconsulta(); $i++) {
			?>
<a href="#" onClick="javascript:addToSearchBox('txtbusqueda','<?php echo $row['valor']?>');"><?php echo $row['valor']?></a>&nbsp;
			<?php
		}
		?></div><?php
	}
	?>
<script language="javascript" type="text/javascript">
	function addToSearchBox (elemento,valor) {
		var c = document.getElementById(elemento).value;
		document.getElementById(elemento).value = c + " " + valor;
	}
</script>
	</div>
	<div class="limpieza mediosalto"></div>