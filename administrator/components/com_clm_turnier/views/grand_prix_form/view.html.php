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
 * enthält Methoden zur Darstellung der Grand Prix Form
 */
class CLM_TurnierViewGrand_Prix_Form extends JViewLegacy {

    /**
     * The JForm object
     *
     * @var JForm
     */
    protected $form;

    /**
     * The active item
     *
     * @var object
     */
    protected $item;

    /**
     * The model state
     *
     * @var object
     */
    protected $state;

    /**
     * Display the view.
     *
     * @param string $tpl
     *            The name of the template file to parse; automatically searches through the template paths.
     *            
     * @return mixed A string if successful, otherwise an Error object.
     */
    public function display($tpl = null) {
        // Initialise variables.
        $this->form = $this->get('Form');
        $this->item = $this->get('Item');
        $this->state = $this->get('State');
        
        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode("\n", $errors), 500);
        }
        
        $this->addToolbar();
        
        return parent::display($tpl);
    }

    /**
     * Add the page title and toolbar.
     *
     * @return void
     */
    protected function addToolbar() {
        JFactory::getApplication()->input->set('hidemainmenu', true);
        
        $user = JFactory::getUser();
        $userId = $user->id;
        $isNew = ($this->item->id == 0);
        $checkedOut = ! ($this->item->checked_out == 0 || $this->item->checked_out == $userId);
        
        JToolbarHelper::title($isNew ? JText::_('COM_CLM_TURNIER_GRAND_PRIX_NEW') : JText::_('COM_CLM_TURNIER_GRAND_PRIX_EDIT'));
        
        // Build the actions for new and existing records.
        if (clm_core::$access->access('BE_tournament_create')) {
            JToolBarHelper::apply('grand_prix_form.apply');
            JToolBarHelper::save('grand_prix_form.save');
            JToolBarHelper::save2copy('grand_prix_form.save2copy');
        }
        JToolbarHelper::cancel('grand_prix_form.cancel');
    }
}

?>
