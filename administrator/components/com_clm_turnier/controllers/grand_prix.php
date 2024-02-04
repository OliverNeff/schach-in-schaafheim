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
 * Grand Prix List Controller
 */
class CLM_TurnierControllerGrand_Prix extends JControllerAdmin {

    /**
     * Constructor.
     *
     * @param array $config
     *            An optional associative array of configuration settings.
     *            
     * @see JControllerLegacy
     */
    function __construct($config = array()) {
        parent::__construct($config);
    }

    /**
     * Proxy for getModel.
     *
     * @param string $name
     *            The name of the model.
     * @param string $prefix
     *            The prefix for the PHP class name.
     * @param array $config
     *            Array of configuration parameters.
     *            
     * @return JModelLegacy
     */
    public function getModel($name = 'Grand_Prix_Form', $prefix = 'CLM_TurnierModel', $config = array('ignore_request' => true)) {
        return parent::getModel($name, $prefix, $config);
    }
}

?>
