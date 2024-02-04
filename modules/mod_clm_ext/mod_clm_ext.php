<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

// angemeldet
if(!defined("DS")){define('DS', DIRECTORY_SEPARATOR);} // fix for Joomla 3.2

require(JModuleHelper::getLayoutPath('mod_clm_ext'));
