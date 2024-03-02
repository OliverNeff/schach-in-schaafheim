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
 * View Klasse zur Darstellung der CLM Turnier Sonderranglisten
 */
class CLM_TurnierViewSonderranglisten extends JViewLegacy {
	
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
		
		// Only set the toolbar if not modal
		if ($this->getLayout() !== 'modal') {
			$this->addToolBar();
		}

		return parent::display($tpl);
	}
	
	/**
	 * Add the page title and toolbar.
	 *
	 * @return void
	 */
	protected function addToolbar() {
		$user = JFactory::getUser();
		
		JToolBarHelper::title(JText::_('COM_CLM_TURNIER_SONDERRANGLISTEN'));
		
		if (clm_core::$access->access('BE_tournament_create')) {
			JToolBarHelper::publishList('sonderranglisten.publish');
			JToolBarHelper::unpublishList('sonderranglisten.unpublish');
			JToolBarHelper::deleteList('JGLOBAL_CONFIRM_DELETE', 'grand_prix.delete');
			JToolBarHelper::editList('sonderranglisten_form.edit');
			JToolBarHelper::addNew('sonderranglisten_form.add');
			
			if ($user->authorise('core.admin', 'com_clm_turnier')
					|| $user->authorise('core.options', 'com_clm_turnier')) {
						JToolbarHelper::preferences('com_clm_turnier');
					}
		} else {
			JToolBarHelper::editList('sonderranglisten_form.edit');
		}
	}
	
	/**
	 * Returns an array of fields the table can be sorted by
	 *
	 * @return array Array containing the field name to sort by as the key and display text as value
	 */
	protected function getSortFields() {
		return array ('r.name' => JText::_('COM_CLM_TURNIER_SONDERRANGLISTEN_NAME_LABEL'),
				't.name' => JText::_('COM_CLM_TURNIER_SONDERRANGLISTEN_TURNIER_NAME_LABEL'),
				'r.id' => JText::_('JGRID_HEADING_ID') );
	}
}
