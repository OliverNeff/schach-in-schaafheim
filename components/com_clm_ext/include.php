<?php
/**
 * @ CLM Extern Component
 * @Copyright (C) 2008-2022 CLM Team.  All rights reserved
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.chessleaguemanager.de
 * @author Thomas Schwietert
 * @email fishpoke@fishpoke.de
*/
// Check to ensure this file is included in Joomla!
// no direct access
defined('_JEXEC') or die('Restricted access');

// Einlesen von Get/Post-Parameter vom Typ String
function clm_ext_request_string($input, $standard = '') {
	if (isset($_GET[$input])) $value = $_GET[$input];
	elseif (isset($_POST[$input])) $value = $_POST[$input];
	elseif (isset($_REQUEST[$input])) $value = $_REQUEST[$input];
	else return $standard;
	if (is_string($value)) $result = $value; else $result = $standard;
	return $result;		
}

// Einlesen von Get/Post-Parameter vom Typ Integer
function clm_ext_request_int($input, $standard = 0) {
	if (!is_int($standard)) $standard = 0;
	if (isset($_GET[$input])) $value = $_GET[$input];
	elseif (isset($_POST[$input])) $value = $_POST[$input];
	elseif (isset($_REQUEST[$input])) $value = $_REQUEST[$input];
	elseif (!class_exists('JFactory')) return $standard; // kein Joomla
	else {
		$app =JFactory::getApplication(); // nur nötig wegen Menüeintragstypen
		$xy = $app->input->getInt($input);
		if (!is_null($xy)) $value = $xy;
		else return $standard; 
	}
	if (!is_numeric($value)) $result = $standard;
	else $result = (integer) $value;
	return $result;		
}
?>
