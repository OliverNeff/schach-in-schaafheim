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
 * CLM Turnier Controller
 */
class CLM_TurnierController extends JControllerLegacy {

    // The default view for the display method.
    protected $default_view = 'grand_prix';

    /**
     *
     * @param array $config            
     */
    function __construct($config = array()) {
        parent::__construct($config = array());
    }

    /**
     * Method to display a view.
     *
     * @param boolean $cachable
     *            If true, the view output will be cached
     * @param array $urlparams
     *            An array of safe URL parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
     *            
     * @return ContactController This object to support chaining.
     */
    function display($cachable = false, $urlparams = array()) {
        $view = $this->input->get('view', 'grand_prix');
        $layout = $this->input->get('layout', 'default');
        $id = $this->input->getInt('id');
        
        // Check for edit form.
        if ($view == 'grand_prix_form' && $layout == 'edit' && ! $this->checkEditId('com_clm_turnier.edit.grand_prix_form', $id)) {
            // Somehow the person just went to the form - we don't allow that.
            $this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
            $this->setMessage($this->getError(), 'error');
            $this->setRedirect(JRoute::_('index.php?option=com_clm_turnier&view=grand_prix', false));
            
            return false;
        }
        
        parent::display();
    }
}
