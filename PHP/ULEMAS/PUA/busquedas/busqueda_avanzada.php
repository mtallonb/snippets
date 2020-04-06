<?php include_once('../comun.inc.php'); ?>
<?php controlAcceso($privilegios['guest']); ?>
<?php $vista = new Vista(); ?>

<?php $_SESSION['Config_diractual']='../'; ?>
<?php $vista->cabecera($_SESSION['Config_diractual'].'css/'.$Config_css,$_SESSION['Config_diractual'].$Config_dirIdiomas); ?>
<?php $subtitulo = _PN_BUSQUEDA_AVANZADA; ?>
<?php $vista->setTituloPagina( $subtitulo ); ?>
<?php $vista->setKB( true, 'txtbusqueda0' ); ?>

<?php $vista->iniciarPagina(); ?>
<?php $vista->iniciarContenido(); ?>
<?php include_once('./formulario_busqueda_adv.php.inc'); ?>
<?php $vista->terminarContenido();	?>
<?php $vista->terminarPagina();	?>