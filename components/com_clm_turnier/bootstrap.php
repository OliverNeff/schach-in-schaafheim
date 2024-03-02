<?php
/**
 * Chess League Manager Turnier Erweiterungen 
 *  
 * @copyright (C) 2019 Andreas Hrubesch; All rights reserved
 * @license GNU General Public License; see https://www.gnu.org/licenses/gpl.html
 * @author Andreas Hrubesch
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// Component definitions / Libraries
jimport('components.com_clm_turnier.defines', JPATH_SITE);
jimport('joomla.filesystem.folder');


// lädt alle Klassen - quasi autoload
// ---- Chess League Manager
if (! jimport('clm/index', JPATH_CLM_COMPONENT)) {
	throw new Exception(JText::_('COM_CLM_TURNIER_ERROR'), '404');
}

// FIXME: https://docs.joomla.org/Using_own_library_in_your_extensions
$classpath = JPATH_CLM_COMPONENT . DIRECTORY_SEPARATOR . 'classes';
foreach (JFolder::files($classpath) as $file) {
	JLoader::register(str_replace('.class.php', '', $file), $classpath . DIRECTORY_SEPARATOR . $file);
}

// ---- Turniererweiterung
JLoader::registerPrefix('CLMTurnier', JPATH_CLM_TURNIER_COMPONENT . DIRECTORY_SEPARATOR . 'helpers');

// Add include path for ...
JHtml::addIncludePath(JPATH_CLM_TURNIER_COMPONENT . '/helpers/html');
JTable::addIncludePath(JPATH_ADMIN_CLM_TURNIER_COMPONENT . '/tables');
