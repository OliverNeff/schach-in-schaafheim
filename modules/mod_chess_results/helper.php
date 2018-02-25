<?php

require_once dirname(__FILE__) . '/service/rest_handler.php';
require_once dirname(__FILE__) . '/persistence/cache_handler.php';

class ModChessResultsHelper {

    // Transfer to database. 
    private static $TOURNAMENT_ID = array(
        'Mannschaft 2016/2017' => 11,
        'Mannschaft 2017/2018' => 12
    );
    private static $DIVISION_ID = array(
        'Kreisklasse A' => 7,
        'Kreisklasse B' => 8,
        'Kreisklasse C' => 9
    );
    
    private static $ROUND;
    
    protected $rest = null;
    protected $persist = null;

    public function __construct() {
        $this->rest = new RestHandler();
        $this->persist = new CacheHandler();
    }

    /**
     * Get a resulted game table. 
     * @param string $tournament Name of the Tournament.
     * @param string $division Name of the Division.
     * @return array A result Table as Array. 
     */
    function getRestTable($tournament, $division, $round) {
        $result['division'] = $division;
        $result['round'] = $round;
        $result['rows'] = $this->rest->getMKTabellen(RestHandler::URL, 'GetMKTabellen', array(
            'turnierid' => ModChessResultsHelper::$TOURNAMENT_ID[$tournament],
            'spielklassenid' => ModChessResultsHelper::$DIVISION_ID[$division],
            'runde' => $round
                )
        );
        
        return $result;
    }

    function getRestRounds($tournament, $division) {
        return $this->rest->getSpieltage(RestHandler::URL, 'GetSpieltageByTurnierSpielklasse', array(
            'turnierid' => ModChessResultsHelper::$TOURNAMENT_ID[$tournament],
            'spielklassenid' => ModChessResultsHelper::$DIVISION_ID[$division])
        );
    }

    function getDataTable($division) {
        $result['division'] = $division;
        $result['rows'] = $this->persist->load(CacheHandler::TABLE_NAME, $division);
        
        return $result;
    }

    function getManagedTables($tournament, $division) {
        $result = $this->getDataTable($division);
        if (!isset(ModChessResultsHelper::$ROUND)) {
              $result['round'] = ModChessResultsHelper::$ROUND = $this->calculateNextRoundId($tournament, $division);    
        } else {
            $result['round'] = ModChessResultsHelper::$ROUND;  
        }
        
        // Update tables if not up to date.
        if (!isset($result) || count($result['rows']) <= 1 || strtotime($result['rows'][0]['last_update']) < strtotime(date("Y-m-d"))) {
            $restResult = $this->getRestTable($tournament, $division, $result['round']);
 
            if (isset($restResult) && count($restResult['rows']) > 0) {
                $result = $restResult;
                $this->persist->delete(CacheHandler::TABLE_NAME, $division);
                foreach ($result['rows'] as $row) {
                    $this->persist->save(CacheHandler::TABLE_NAME, $division, date("Y-m-d"), $row);
                }
            } else {
                // TODO Add can't update message.
                $this->persist->update(CacheHandler::TABLE_NAME, $division, date("Y-m-d"));
            }
  
            $result['round'] = ModChessResultsHelper::$ROUND = $this->calculateNextRoundId($tournament, $division);
        }
        
        return $result;
    }

    /**
     * Calculate round Id from now. 
     * @return type Id of the round as Integer. 
     */
    function calculateNextRoundId($tournament, $division) {
        $rounds = $this->getRestRounds($tournament, $division);
        
        $resultId = 0;
        foreach (array_reverse($rounds) as $round) {
            if (strtotime($round['spieltag']) <= strtotime("-1 day")) {
                $resultId = $round['id'];
            } else {
                return $resultId;
            }
        }
        
        return $resultId;
    }

    public static function getResults($params) {
        $manager = new ModChessResultsHelper();
        //return $manager->getRestTable($params['tournament'], $params['division']);
        
        return $manager->getManagedTables($params['tournament'], $params['division']);
    }

}
