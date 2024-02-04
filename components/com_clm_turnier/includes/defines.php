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

// Directory Separator (backward compatibility)
if (! defined("DS")) {
    define('DS', DIRECTORY_SEPARATOR);
}

// CLM Turnier Component Administrator Path
if (! defined('JPATH_ADMIN_CLM_TURNIER_COMPONENT')) {
    define('JPATH_ADMIN_CLM_TURNIER_COMPONENT', JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_clm_turnier');
}

// CLM Turnier Component Path
if (! defined('JPATH_CLM_TURNIER_COMPONENT')) {
    define('JPATH_CLM_TURNIER_COMPONENT', JPATH_SITE . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_clm_turnier');
}

// CLM Component Path
if (! defined('JPATH_CLM_COMPONENT')) {
    define('JPATH_CLM_COMPONENT', JPATH_SITE . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_clm');
}
