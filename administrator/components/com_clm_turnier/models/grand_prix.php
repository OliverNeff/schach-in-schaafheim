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
 * Grand Prix List Model
 */
class CLM_TurnierModelGrand_Prix extends JModelList {

    /**
     * Constructor.
     *
     * @param array $config
     *            An optional associative array of configuration settings.
     *            
     * @see JControllerLegacy
     */
    function __construct($config = array()) {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'id',
                'a.id',
                'name',
                'a.name',
                'typ',
                'a.typ',
                'published',
                'a.published'
            );
        }
        
        parent::__construct($config);
    }

    /**
     * Build an SQL query to load the list data.
     *
     * @return JDatabaseQuery
     */
    protected function getListQuery() {
        // Create a new query object.
        $db = $this->getDbo();
        $query = $db->getQuery(true);
        
        $query->select('a.*');
        $query->from($this->_db->quoteName('#__clm_turniere_grand_prix', 'a'));
        
        // Join over the users for the checked out user.
        $query->select($db->quoteName('uc.name', 'editor'))
            ->join('LEFT', $db->quoteName('#__users', 'uc') . ' ON ' . $db->quoteName('uc.id') . ' = ' . $db->quoteName('a.checked_out'));
        
        // Filter by published state
        $published = $this->getState('filter.published');
        if (is_numeric($published)) {
            $query->where($db->quoteName('a.published') . ' = ' . (int) $published);
        }
        
        // Filter by type state
        $typ = $this->getState('filter.typ');
        if (is_numeric($typ)) {
            $query->where($db->quoteName('a.typ') . ' = ' . (int) $typ);
        }
        
        // Filter by search in name
        $search = $this->getState('filter.search');
        if (! empty($search)) {
            if (stripos($search, 'id:') === 0) {
                $query->where('a.id = ' . (int) substr($search, 3));
            } else {
                $search = $db->quote('%' . str_replace(' ', '%', $db->escape(trim($search), true) . '%'));
                $query->where($db->quoteName('a.name') . ' LIKE ' . $search);
            }
        }
        
        // Add the list ordering clause.
        $orderCol = $this->state->get('list.ordering', 'a.name');
        $orderDirn = $this->state->get('list.direction', 'asc');
        
        $query->order($db->escape($orderCol) . ' ' . $db->escape($orderDirn));
        
        return $query;
    }
}

?>