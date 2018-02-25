<?php

/**
 * Description of cache_handler
 * 
 * @author Oliver Neff
 */
class CacheHandler {

    const TABLE_NAME = '#__chess_records';

    // $row = {team, division, date, flag, bp, mp}
    function save($tableName, $division, $date, $row) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $columns = array('verein', 'division', 'last_update', 'flag', 'platz', 'summebp', 'summemp');
        $values = array($db->quote($row['verein']), $db->quote($division), $db->quote($date), $db->quote($row['flag']), $db->quote($row['platz']), $db->quote($row['summebp']), $db->quote($row['summemp']));
        
        $query
                ->insert($db->quoteName($tableName))
                ->columns($db->quoteName($columns))
                ->values(implode(',', $values));

        $db->setQuery($query);
        $db->execute();
    }

    function delete($tableName, $division) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        // Delete all entries of a division. 
        $conditions = array(
            $db->quoteName('division') . ' = ' . "\"$division\""
        );

        $query->delete($db->quoteName($tableName));
        $query->where($conditions);

        $db->setQuery($query);

        return $db->execute();
    }

    function update($tableName, $division, $date) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        // Fields to update.
        $fields = array(
            $db->quoteName('last_update') . ' = ' . "\"$date\""
        );

        // Conditions for which records should be updated.
        $conditions = array(
            $db->quoteName('division') . ' = ' . "\"$division\""
        );

        $query->update($db->quoteName($tableName))->set($fields)->where($conditions);

        $db->setQuery($query);

        return $db->execute();
    }

    function load($tableName, $division) {
        $db = JFactory::getDbo();

        $query = $db->getQuery(true);
        $query->select($db->quoteName(array('verein', 'division', 'last_update', 'flag', 'platz', 'summebp', 'summemp')));
        $query->from($db->quoteName($tableName));

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

}
