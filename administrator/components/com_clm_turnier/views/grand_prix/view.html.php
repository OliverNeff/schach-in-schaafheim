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
 * View Klasse zur Darstellung der Grand Prix Liste.
 */
class CLM_TurnierViewGrand_prix extends JViewLegacy {

	/**
	 * An array of items
	 *
	 * @var array
	 */
	protected $items;

	/**
	 * The pagination object
	 *
	 * @var JPagination
	 */
	protected $pagination;

	/**
	 * The model state
	 *
	 * @var object
	 */
	protected $state;

	/**
	 * Form object for search filters
	 *
	 * @var JForm
	 */
	public $filterForm;

	/**
	 * The active search filters
	 *
	 * @var array
	 */
	public $activeFilters;

	/**
	 * Display the view.
	 *
	 * @param string $tpl
	 *        	The name of the template file to parse; automatically searches through the template paths.
	 *        	
	 * @return mixed A string if successful, otherwise an Error object.
	 */
	public function display($tpl = null) {
		$this->items = $this->get('Items');
		$this->state = $this->get('State');
		$this->pagination = $this->get('Pagination');
		$this->filterForm = $this->get('FilterForm');
		$this->activeFilters = $this->get('ActiveFilters');

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
		$user = JFactory::getUser();

		JToolBarHelper::title(JText::_('COM_CLM_TURNIER_GRAND_PRIX'));

		if (clm_core::$access->access('BE_tournament_create')) {
			JToolBarHelper::publishList('grand_prix.publish');
			JToolBarHelper::unpublishList('grand_prix.unpublish');
			JToolBarHelper::deleteList('JGLOBAL_CONFIRM_DELETE', 'grand_prix.delete');
			JToolBarHelper::editList('grand_prix_form.edit');
			JToolBarHelper::addNew('grand_prix_form.add');

			if ($user->authorise('core.admin', 'com_clm_turnier') 
					|| $user->authorise('core.options', 'com_clm_turnier')) {
				JToolbarHelper::preferences('com_clm_turnier');
			}
		} else {
			JToolBarHelper::editList('grand_prix_form.edit');
		}
	}

	/**
	 * Returns an array of fields the table can be sorted by
	 *
	 * @return array Array containing the field name to sort by as the key and display text as value
	 */
	protected function getSortFields() {
		return array ('a.name' => JText::_('COM_CLM_TURNIER_FIELD_NAME_LABEL'),
				'a.typ' => JText::_('COM_CLM_TURNIER_FIELD_TYP_LABEL'),
				'a.published' => JText::_('JSTATUS'),
				'a.id' => JText::_('JGRID_HEADING_ID') );
	}
}
