<?php

define('_VALID_ACCESS', 1);
require_once('../config.php');
$_SESSION['Config_diractual']='../'; 
include_once($_SESSION['Config_diractual'].$Config_dirclases . 'db.php');
include_once($_SESSION['Config_diractual'].$Config_dirclases . 'vista.php');
include_once($_SESSION['Config_diractual'].$Config_dirclases . 'idioma.php');
include_once($_SESSION['Config_diractual'].$Config_dirclases . 'usuario.php');

//global $Config_lang, $Config_menudinamico, $Config_dirclases, $Config_dirIdiomas, $Config_sitename, $Config_css;

controlAcceso($privilegios['admin']); // Permite acceso al panel de control

$vista = new Vista();
$usr = new Usuario();

$vista->setMostrarOpciones(true);
global $privilegios;
?>
		<?php $vista->cabecera($_SESSION['Config_diractual'].'css/'.$Config_css,$_SESSION['Config_diractual'].$Config_dirIdiomas,"Ulemas"); ?>
		<?php $vista->setTituloPagina( _MAIN_PANEL_GRAPH ); ?>
		<?php $vista->setMostrarOpciones(true); ?>
		<?php $vista->setKB(false); ?>
		<?php $vista->iniciarPagina(); ?>
		<?php $vista->iniciarContenido(); ?>
		
		<!--<div id="imagen_index"></div>-->
		<!--<div class="txtlinea"><?php echo _TEXT_INDEX; ?></div>-->
		<?php if(tieneAcceso($privilegios['guest'])) { ?>
		<div class="cuatro_columnas">
			<h3><?php echo _MAPS; ?></h3>
			<?php $vista->accesoIconoPanel('mapa_alandalus.php',_ALANDALUS,_SEARCH_SIMPLE_EXPL,'generico.png',_SEARCH_SIMPLE,$privilegios['guest']); 
                  $vista->accesoIconoPanel('onomastica.php',_ONOMASTICA,_SEARCH_SIMPLE_EXPL,'generico.png',_SEARCH_SIMPLE,$privilegios['guest']); 
                  $vista->accesoIconoPanel('mortalidad.php',_MORTALIDAD,_SEARCH_SIMPLE_EXPL,'generico.png',_SEARCH_SIMPLE,$privilegios['guest']); 
                  
             ?>			
			<div class="limpieza"></div>
						
		</div>
		<?php }?>
		
	
		<div class="limpieza mediosalto"></div>
<?php $vista->terminarContenido();?>
<?php $vista->terminarPagina();	?>
