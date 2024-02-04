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

/**
 * Grand Prix Form Controller
 */
class CLM_TurnierControllerGrand_Prix_Form extends JControllerForm {

	/**
	 * Constructor.
	 *
	 * @param array $config
	 *        	An optional associative array of configuration settings.
	 *        	
	 * @see JControllerLegacy
	 */
	function __construct($config = array()) {
		$this->view_list = 'grand_prix';
		
		parent::__construct($config);
	}
}

?>
