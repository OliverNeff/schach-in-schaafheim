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

// Access check: is this user allowed to access the backend of this component?
if (!JFactory::getUser()->authorise('core.manage', 'com_clm_turnier')) {
    throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
}

// Component definitions
if (! jimport('components.com_clm_turnier.defines', JPATH_SITE)) {
    throw new Exception(JText::_('COM_CLM_TURNIER_ERROR'), '404');
}

// Chess League Manager
if (! jimport('clm.index', JPATH_CLM_COMPONENT)) {
    throw new Exception(JText::_('COM_CLM_TURNIER_REQ_COM_CLM'), '404');
}

// Add include path for ...
JHtml::addIncludePath(JPATH_CLM_TURNIER_COMPONENT . '/helpers/html');

echo '<div id="clm"><div class="clm">';

// Get an instance of the controller
$controller = JControllerLegacy::getInstance('clm_turnier');

// Perform the Request task
$controller->execute(JFactory::getApplication()->input->get('task'));

// Redirect if set by the controller
$controller->redirect();

echo "</div></div>";
