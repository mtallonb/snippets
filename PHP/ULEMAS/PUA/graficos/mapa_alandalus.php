<?php

define('_VALID_ACCESS', 1);
require_once('../config.php');
$_SESSION['Config_diractual']='../'; 
include_once($_SESSION['Config_diractual'].$Config_dirclases . 'db.php');
include_once($_SESSION['Config_diractual'].$Config_dirclases . 'vista.php');
include_once($_SESSION['Config_diractual'].$Config_dirclases . 'idioma.php');
include_once($_SESSION['Config_diractual'].$Config_dirclases . 'usuario.php');
controlAcceso($privilegios['guest']); // Permite acceso al panel de control

$vista = new Vista();
$usr = new Usuario();

$vista->setMostrarOpciones(true);
global $privilegios;

controlAcceso($privilegios['guest']);

//$sql="SELECT nombre_castellano, COUNT(*) FROM lugar GROUP BY nombre_castellano"; 
$sql = "SELECT nombre_castellano, COUNT(*) FROM personaje_lugar JOIN lugar on personaje_lugar.idLugar=lugar.idLugar
WHERE (idTipoRelacionLugar=24 OR idTipoRelacionLugar=50)
AND nombre_castellano IS NOT NULL AND lugar.idLugar != 3
GROUP BY nombre_castellano";

$conexion = new DB_mysql();
$conexion->conectar();
$datos=$conexion->consulta($sql);
if($conexion->numregistros()>0) { //establecer condicion si se busca un PID inexistente
$row=mysql_fetch_assoc($datos); //MUCHO OJO NULL y VACIOS CONTROLARLO
$row=mysql_fetch_assoc($datos);

?>	

<script type='text/javascript' src='https://www.google.com/jsapi'></script>
<script type='text/javascript'>
	google.load('visualization', '1', {'packages': ['geochart']}); 
	google.setOnLoadCallback(drawMarkersMap);
	
	function drawMarkersMap() {
		var data = new google.visualization.DataTable();	
		data.addColumn('string','Ciudad');	
		data.addColumn('number','Contador');
		data.addRows([	
				<?php $primera=true; 
				while ($row=mysql_fetch_assoc($datos)){
				if (!$primera) {echo ",";}?>['<?php echo $row["nombre_castellano"];?>',<?php echo $row["COUNT(*)"];?>]
				<?php $primera=false;}//Fin del while
				?>]);

         var options = {
           region: '039', //South Europe '039', Europe '150'
           displayMode: 'markers',
           magnifyingGlass: {enable:true, zoomFactor: 7.5},
           colorAxis: {colors: ['green', 'blue']}
         };

      var chart = new google.visualization.GeoChart(document.getElementById('chart_div'));
      chart.draw(data, options);
	};
    </script>

<?php 	
}//Fin del if
 $vista->cabecera($_SESSION['Config_diractual'].'css/'.$Config_css,$_SESSION['Config_diractual'].$Config_dirIdiomas,"Ulemas");
$vista->setTituloPagina( _ALANDALUS ); 
$vista->setMostrarOpciones(true);
$vista->setKB(false);
$vista->iniciarPagina();
$vista->iniciarContenido();
if(tieneAcceso($privilegios['guest'])) { ?>
		<div id="chart_div" style="width: 900px; height: 500px;"></div>
		<div class="limpieza"></div>
		<?php
    }?>
		<div class="limpieza mediosalto"></div>
<?php 
$vista->terminarContenido();
$vista->terminarPagina();
?>


