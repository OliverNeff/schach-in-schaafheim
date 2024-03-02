<?php
/**
 * Chess League Manager Turnier Erweiterungen 
 *  
 * @copyright (C) 2019 Andreas Hrubesch; All rights reserved
 * @license GNU General Public License; see https://www.gnu.org/licenses/gpl.html
 * @author Andreas Hrubesch
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

jimport('components.com_clm_turnier.defines', JPATH_SITE);

use Joomla\CMS\Form\FormField;
use Joomla\CMS\Layout\FileLayout;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use \Joomla\CMS\Version;

/**
 * Field to select Turnier Sonderlangliste(n) from a modal list.
 *
 * @since 3.0
 */
class JFormFieldSonderranglisten extends FormField {
	/**
	 * The form field type.
	 *
	 * @var string
	 */
	public $type = 'Sonderranglisten';

	/**
	 * Method to instantiate the form field object.
	 *
	 * @param Form $form
	 *        	The form to attach to the form field object.
	 */
	public function __construct($form = null) {
		parent::__construct($form);

		// Layout to render
		$this->layout = 'layouts.form.fields.sonderranglisten';
	}

	/**
	 * Get the renderer
	 *
	 * @param string $layoutId
	 *        	Id to load
	 *        	
	 * @return FileLayout
	 */
	protected function getRenderer($layoutId = 'default') {
		$renderer = new FileLayout($layoutId);

		$renderer->setDebug($this->isDebugEnabled());

		$layoutPaths = $this->getLayoutPaths();

		if ($layoutPaths) {
// TODO: Joomla Bug ??			
// 			$renderer->setIncludePaths($layoutPaths);			
			$renderer->addIncludePaths($layoutPaths);
		}

		return $renderer;
	}

	/**
	 * Method to get the data to be passed to the layout for rendering.
	 *
	 * @return array
	 */	
	 protected function getLayoutData() {
		// Get the basic field data
		$layoutData = parent::getLayoutData();

		BaseDatabaseModel::addIncludePath(JPATH_ADMIN_CLM_TURNIER_COMPONENT . '/models');
		$model = BaseDatabaseModel::getInstance('Sonderranglisten', 'CLM_TurnierModel');
		$totalItems = $model->getTotalItems($this->value);
		
		$saison = '';
		if (count($totalItems) > 0 && $totalItems[0]) {
			$saison = $totalItems[0]->saison;
		}
		
		$extraData = array ('options' => $totalItems, 'saison' => $saison);
		if (! isset($layoutData['dataAttribute'])) {
			$extraData['dataAttribute'] = '';
		}
		
		return array_merge($layoutData, $extraData);
	}

	/**
	 * Allow to override renderer include paths in child fields
	 *
	 * @return array
	 */
	protected function getLayoutPaths() {
		return array (JPATH_ADMIN_CLM_TURNIER_COMPONENT);
	}
}
