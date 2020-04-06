<?php
include_once('../comun.inc.php');

$tabla = $_POST['tabla'];

$conexion = new DB_mysql();
$conexion->conectar();
$resCampos = $conexion->consulta("SELECT * FROM '$tabla'");

?><select name="FO"><?php 
for($i=0; $i<mysql_num_fields($resCampos); $i++) { 
	$nombre = mysql_field_name($resCampos,$i); ?>
    <option value="<?php echo $nombre; ?>"><?php echo ucfirst($nombre); ?></option>
<?php } ?>
</select>


