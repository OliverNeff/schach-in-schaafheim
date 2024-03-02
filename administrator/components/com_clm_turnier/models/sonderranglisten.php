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

/**
 * CML Turnier Sonderranglisten List Model
 *
 * @since 3.0
 */
class CLM_TurnierModelSonderranglisten extends JModelList {

	/**
	 * Constructor.
	 *
	 * @param array $config
	 *        	An optional associative array of configuration settings.
	 *        	
	 * @see JControllerLegacy
	 */
	function __construct($config = array()) {
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array ('id', 'r.id', 'name', 'r.name',
					'turnier', 't.name', 'ordering', 'r.ordering' );
		}

		parent::__construct($config);
	}

	/**
	 * Build the core SQL Query to load the list data.
	 *
	 * Die Daten erfüllen dabei folgende Kriterien:
	 * <ul>
	 * <li>Saison: veröffentlicht, nicht archiviert</li>
	 * <li>Turnier: veröffentlicht</li>
	 * <li>Sonderrangliste: veröffentlicht</li>
	 * </ul>
	 *
	 * @return JDatabaseQuery
	 */
	protected function _getQuery() {
		$query = $this->getDbo()->getQuery(true);
		$query->select($this->_db->quoteName(explode(',', 'r.id,r.name,r.ordering,s.name,t.name'), explode(',', 'id,name,ordering,saison,turnier')))
			->select('CONCAT(r.name, \' / \', t.name) AS dname');		
		$query->from($this->_db->quoteName('#__clm_saison', 's'));
		$query->join('', $this->_db->quoteName('#__clm_turniere', 't') . ' ON ' .
				$this->_db->quoteName('t.sid') . ' = ' .
				$this->_db->quoteName('s.id'));
		$query->join('', $this->_db->quoteName('#__clm_turniere_sonderranglisten', 'r') .
				' ON ' . $this->_db->quoteName('r.turnier') . ' = ' .
				$this->_db->quoteName('t.id'));
		$query->where($this->_db->quoteName('s.archiv') . ' = ' . '0');
		$query->where($this->_db->quoteName('s.published') . ' = ' . '1');
		$query->where($this->_db->quoteName('t.published') . ' = ' . '1');
		$query->where($this->_db->quoteName('r.published') . ' = ' . '1');

		return $query;
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return JDatabaseQuery
	 */
	protected function getListQuery() {
		// Create a new query object.
		$query = $this->_getQuery();

		// Filter by search in name
		$search = $this->getState('filter.search');
		if (! empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('r.id = ' . (int) substr($search, 3));
			} else {
				$search = $this->_db->quote('%' .
						str_replace(' ', '%', $this->_db->escape(trim($search), true) .
						'%'));
				$query->where($this->_db->quoteName('r.name') . ' LIKE ' .
						$search);
				$query->orWhere($this->_db->quoteName('t.name') . ' LIKE ' .
						$search);
			}
		}

		// Add the list ordering clause.
		$orderCol = $this->state->get('list.ordering', 'r.id');
		$orderDirn = $this->state->get('list.direction', 'asc');

		$query->order($this->_db->escape($orderCol) . ' ' .
				$this->_db->escape($orderDirn));

		return $query;
	}

	/**
	 * Method to get an array of all data items.
	 *
	 * @param array $rids
	 *        	optional weiter Ranglisten ID's
	 *        	
	 * @return mixed An array of data items on success, false on failure.
	 */
	public function getTotalItems($rids = array()) {
		$query = $this->_getQuery();

		// weitere Rabglisten ID's
		if (! empty($rids)) {
			$values = array_map("intval", $rids);
			if (! empty($values)) {
				$query->orWhere($this->_db->quoteName('r.id') . ' IN(' .
						implode(', ', $values) . ')');
			}
		}

		return $this->_getList($query);
	}
}

?>