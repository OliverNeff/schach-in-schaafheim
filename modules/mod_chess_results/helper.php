<?php

require_once dirname(__FILE__) . '/persistence/database_handler.php';

class ModChessResultsHelper {

    protected $persist = null;

    public function __construct() {
        $this->persist = new DatabaseHandler();
    }

    function getRecords($division) {
        $result['division'] = $division;
        $result['rows'] = $this->persist->loadRecords($division);

        return $result;
    }

    function getRound($division) {
        $result['rows'] = $this->persist->loadMatchDays($division);
        $round = 0;
        $mostRecent = 0;
        $now = strtotime(date("Y-m-d"));
            foreach ($result['rows'] as $row) {
                $date = $row['date'];
                if ($date > $mostRecent && $date < $now) {
                    $round++;
                    $mostRecent = $date;
                }  
        }
        return $round;
    }

    function getManagedTables($division) {
        $result = $this->getRecords($division);
        $result['round'] = $this->getRound($division);
        return $result;
    }

    public static function getResults($params) {
        $manager = new ModChessResultsHelper();
        return $manager->getManagedTables($params['division']);
    }

}
