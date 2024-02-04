<?php
/**
 * Chess League Manager Turnier Erweiterungen 
 *  
 * @copyright (C) 2017 Andreas Hrubesch
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author Andreas Hrubesch
 */

// No direct access to this file
defined('JPATH_CLM_TURNIER_COMPONENT') or die('Restricted access');

// CLM Component Path
if (! defined('JPATH_CLM_COMPONENT'))
    define('JPATH_CLM_COMPONENT', JPATH_SITE . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_clm');

// CLM Stylesheet laden
require_once (JPATH_CLM_COMPONENT . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'css_path.php');

// CLM Turnier Stylsheets laden
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_clm_turnier/includes/content.css', 'text/css');

?>
