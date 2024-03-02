<?php
/**
 * @ CLM Extern Component
 * @Copyright (C) 2008-2021 CLM Team.  All rights reserved
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.chessleaguemanager.de
 * @author Thomas Schwietert
 * @email fishpoke@fishpoke.de
*/
// kein direkter Zugriff
defined('_JEXEC') or die('Restricted access');
if(!defined("DS")){define('DS', DIRECTORY_SEPARATOR);} // fix for Joomla 3.2

// laden des Joomla! Basis Controllers
require_once (JPATH_COMPONENT.DS.'controller.php');
require_once (JPATH_SITE . DIRECTORY_SEPARATOR . "components" . DIRECTORY_SEPARATOR . "com_clm_ext" . DIRECTORY_SEPARATOR . "include.php");

$controller 	= clm_ext_request_string( 'controller');

// laden von weiteren Controllern
if($controller = clm_ext_request_string('controller')) {
	$path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';
	if (file_exists($path)) {
		require_once $path;
	} else {
		$controller = '';
	}
}
// Erzeugen eines Objekts der Klasse controller
$classname	= 'CLM_EXTController'.ucfirst($controller);
$controller = new $classname( );

// den request task ausleben
$controller->execute(clm_ext_request_string('task'));

// Redirect aus dem controller
$controller->redirect();

?>
 