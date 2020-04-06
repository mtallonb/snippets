<?php include_once('../comun.inc.php'); ?>
<?php controlAcceso($privilegios['guest']); ?>
<?php $vista = new Vista(); ?>

<?php $_SESSION['Config_diractual']='../'; ?>
<?php $vista->cabecera($_SESSION['Config_diractual'].'css/'.$Config_css,$_SESSION['Config_diractual'].$Config_dirIdiomas); ?>
<?php $vista->setTituloPagina( _SIMPLE_SEARCH_BOT ); ?>
<?php $vista->iniciarPagina(); ?>
<?php $vista->iniciarContenido(); ?>
<?php include_once('./formulario_busqueda_simple.php.inc'); ?>
<?php include_once('./mostrarKeywords.php.inc'); ?>
<?php $vista->terminarContenido();	?>
<?php $vista->terminarPagina();	?>