<?php
/**
 * Chess League Manager Turnier Erweiterungen 
 *  
 * @copyright (C) 2017 Andreas Hrubesch; All rights reserved
 * @license GNU General Public License; see https://www.gnu.org/licenses/gpl.html
 * @author Andreas Hrubesch
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// Component definitions
if (! jimport('components.com_clm_turnier.includes.defines', JPATH_SITE)) {
    throw new Exception(JText::_('COM_CLM_TURNIER_ERROR'), '404');
}

// TODO: intelligenten CLMLoader ...
jimport('joomla.filesystem.folder');

// lädt alle CLM-Klassen - quasi autoload
$classpath = JPATH_CLM_COMPONENT . DIRECTORY_SEPARATOR . 'classes';
foreach (JFolder::files($classpath) as $file) {
    JLoader::register(str_replace('.class.php', '', $file), $classpath . DIRECTORY_SEPARATOR . $file);
}

$classpath = JPATH_CLM_TURNIER_COMPONENT . DIRECTORY_SEPARATOR . 'classes';
foreach (JFolder::files($classpath) as $file) {
    JLoader::register(str_replace('.class.php', '', $file), $classpath . DIRECTORY_SEPARATOR . $file);
}

JLoader::register('Grand_PrixHelperRoute', JPATH_CLM_TURNIER_COMPONENT . '/helpers/route.php');

// Add include path for ...
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JTable::addIncludePath(JPATH_ADMIN_CLM_TURNIER_COMPONENT . DIRECTORY_SEPARATOR . 'tables');

// set current locale for date and time formatting with strftime()
setlocale(LC_TIME, JFactory::getLanguage()->getLocale());

// Get an instance of the controller
$controller = JControllerLegacy::getInstance('CLM_Turnier');

// Perform the Request task
$input = JFactory::getApplication()->input;
$controller->execute($input->getCmd('task'));

// Redirect if set by the controller
$controller->redirect();
