<?php
$tabla = $_GET['tabla'];
$tipoBusqueda = $_GET['tipoB'];

if($tipoBusqueda=='simple') {
	switch($tabla) {
		case 'personaje': include('../personaje/busqueda_simple.php'); break;
		case 'lugar': include('../lugar/busqueda_simple.php'); break;
		case 'cargo': include('../cargo/busqueda_simple.php'); break;
		case 'referencia': include('../referencia/busqueda_simple.php'); break;
		case 'familia': include('../familia/busqueda_simple.php'); break;
	}
} else {
	switch($tabla) {
		case 'personaje': include('./busqueda_simple.php'); break;
		case 'lugar': include('../lugar/busqueda_simple.php'); break;
		case 'cargo': include('../cargo/busqueda_simple.php'); break;
		case 'referencia': include('../referencia/busqueda_simple.php'); break;
	}
}
?>