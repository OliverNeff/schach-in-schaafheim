<?php

/**
 * Description of cache_handler
 * 
 * @author Oliver Neff
 */
class DatabaseHandler {

    const TABLE_NAME_RECORDS = '#__chess_records';
    const TABLE_NAME_MATCHES = '#__chess_matches';

    function loadRecords($division) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select($db->quoteName(array('verein', 'division', 'last_update', 'flag', 'platz', 'summebp', 'summemp')));
        $query->from($db->quoteName(DatabaseHandler::TABLE_NAME_RECORDS));
        $query->where($db->quoteName('division') . ' = ' . $db->quote($division));
        $query->order('platz ASC');
        $db->setQuery($query);
        $tmp = $db->loadObjectList();
        $result = array();
        for ($i = 0; $i < count($tmp); $i++) {
            $result[$i] = (array) $tmp[$i];
        }
        return $result;
    }
    
    function loadMatchDays($division) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select($db->quoteName(array('date')));
        $query->from($db->quoteName(DatabaseHandler::TABLE_NAME_MATCHES));
        $query->where($db->quoteName('division') . ' = ' . $db->quote($division));
        $query->order('date ASC');
        $db->setQuery($query);
        $tmp = $db->loadObjectList();
        $result = array();
        for ($i = 0; $i < count($tmp); $i++) {
            $result[$i] = (array) $tmp[$i];
        }
        return $result;
    }

}
