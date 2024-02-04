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

use Joomla\String\StringHelper;

/**
 * Grand Prix Form Model
 */
class CLM_TurnierModelGrand_Prix_Form extends JModelAdmin {

    /**
     * Method to get a table object, load it if necessary.
     *
     * @param string $name
     *            table name. Optional.
     * @param string $prefix
     *            class prefix. Optional.
     * @param array $options
     *            array for model. Optional.
     */
    function getTable($name = 'turnier_grand_prix', $prefix = 'TableCLM', $options = array()) {
        return JTable::getInstance($name, $prefix, $options);
    }

    /**
     * Method to get the row form.
     *
     * @param array $data
     *            Data for the form.
     * @param boolean $loadData
     *            True if the form is to load its own data (default case), false if not.
     *            
     * @return JForm|boolean A JForm object on success, false on failure
     */
    public function getForm($data = array(), $loadData = true) {
        $extension = $this->getState();
        $jinput = JFactory::getApplication()->input;
        
        $form = $this->loadForm('com_clm_turnier.grand_prix_form', 'grand_prix_form', array(
            'control' => 'jform',
            'load_data' => $loadData
        ));
        if (empty($form)) {
            return false;
        }
        
        $this->canEditState((object) $data);
        
        return $form;
    }

    /**
     * Method to get the data that should be injected in the form.
     *
     * @return array The default data is an empty array.
     *        
     * @since 1.6
     */
    protected function loadFormData() {
        $app = JFactory::getApplication();
        
        // Check the session for previously entered form data.
        $data = $app->getUserState('com_clm_turnier.edit.grand_prix_form.data', array());
        
        if (empty($data)) {
            $data = $this->getItem();
        }
        
        $this->preprocessData('com_clm_turnier.grand_prix_form', $data);
        
        return $data;
    }

    /**
     * Method to save the form data.
     *
     * @param array $data
     *            The form data.
     *            
     * @return boolean True on success.
     */
    public function save($data) {
        // Alter the title for save as copy
        $input = JFactory::getApplication()->input;
        if ($input->get('task') == 'save2copy') {
            $name = $data['name'];
            $table = $this->getTable();
            while ($table->load(array(
                'name' => $name
            ))) {
                $name = StringHelper::increment($name);
            }
            $data['name'] = $name;
        }
        
        return parent::save($data);
    }
}

?>
