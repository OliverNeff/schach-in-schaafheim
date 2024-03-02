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

// Component definitions / Libraries
if (! jimport('components.com_clm_turnier.bootstrap', JPATH_SITE)) {
    throw new Exception(JText::_('COM_CLM_TURNIER_ERROR'), '404');
}

// Bootstrap CSS / JS
JHtml::_('grandprix.stylesheet');

// set current locale for date and time formatting with strftime()
setlocale(LC_TIME, JFactory::getLanguage()->getLocale());

// Get an instance of the controller
$controller = JControllerLegacy::getInstance('CLM_Turnier');

// Perform the Request task
$input = JFactory::getApplication()->input;
$controller->execute($input->getCmd('task'));

// Redirect if set by the controller
$controller->redirect();
