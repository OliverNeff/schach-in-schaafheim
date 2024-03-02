<?php
/**
 * @ Chess League Manager (CLM) Modul MiniTab 
 * @Copyright (C) 2008-2021 CLM Team.  All rights reserved
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.chessleaguemanager.de
*/
// no direct access
defined('_JEXEC') or die('Restricted access');
if(!defined("DS")){define('DS', DIRECTORY_SEPARATOR);} // fix for Joomla 3.2

// Konfiguration wird benötigt
require_once (JPATH_SITE . DIRECTORY_SEPARATOR . "components" . DIRECTORY_SEPARATOR . "com_clm" . DIRECTORY_SEPARATOR . "clm" . DIRECTORY_SEPARATOR . "index.php");

// angemeldet
require_once (dirname(__FILE__).DS.'helper.php');

$altItemid	= $params->def('altItemid');

$lid		= $params->def('liga');
$zps		= $params->def('zps');
$show_zps	= $params->def('show_zps', 1);
$show_aufab	= $params->def('show_aufab', 1);
$bold		= $params->def('bold');

$show_th	= $params->def('show_th', 1);
$show_mp	= $params->def('show_mp', 1);
$show_bp	= $params->def('show_bp', 1);

$punkte 	= modCLM_MinitabHelper::getCLMPunkte($params);
$liga 		= modCLM_MinitabHelper::getCLMLiga($params);

require(JModuleHelper::getLayoutPath('mod_clm_minitab'));


