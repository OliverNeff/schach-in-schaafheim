<?php
/**
 * @ CLM Extern Component
 * @Copyright (C) 2008-2021 CLM Team.  All rights reserved
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.chessleaguemanager.de
 * @author Thomas Schwietert
 * @email fishpoke@fishpoke.de
*/
// no direct access
defined('_JEXEC') or die('Restricted access');
require_once (JPATH_SITE . DIRECTORY_SEPARATOR . "components" . DIRECTORY_SEPARATOR . "com_clm_ext" . DIRECTORY_SEPARATOR . "include.php");

// angemeldet
if(!defined("DS")){define('DS', DIRECTORY_SEPARATOR);} // fix for Joomla 3.2

require(JModuleHelper::getLayoutPath('mod_clm_ext'));
