<?php
define('_JEXEC', 1);
define('JPATH_BASE', dirname(__DIR__));

// Load system defines
if (file_exists(JPATH_BASE . '/defines.php'))
{
    require_once JPATH_BASE . '/defines.php';
}

if (!defined('_JDEFINES'))
{
    require_once JPATH_BASE . '/includes/defines.php';
}

// Get the framework.
require_once JPATH_LIBRARIES . '/import.legacy.php';

// Bootstrap the CMS libraries.
require_once JPATH_LIBRARIES . '/cms.php';

// Configure error reporting to maximum for CLI output.
error_reporting(E_ALL);
ini_set('display_errors', 1);

const BASE_URL = 'http://www.schach-in-starkenburg.de/webservice/api/';
const MANNSCHAFT_1 = 'Starkenburgliga';
const MANNSCHAFT_2 = 'Kreisklasse B';
const TABLE_NAME_RECORDS = '#__chess_records';
const TABLE_NAME_MATCHES = '#__chess_matches';

class UpdateRecords {

    private $turniere = [];
    private $spielklassen = [];
    private $teamSpieltage = [
        MANNSCHAFT_1 => [],
        MANNSCHAFT_2 => []
    ];
    private $teamResults = [
        MANNSCHAFT_1 => [],
        MANNSCHAFT_2 => []
    ];

    function executeRest($service_url) {
        $curl = curl_init($service_url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($curl, CURLOPT_TIMEOUT, 15);
        $curl_response = curl_exec($curl);
        curl_close($curl);
        return json_decode($curl_response, true);
    }

    function getTurniere() {
        $turniere = $this->executeRest(BASE_URL . 'Turniere/GetTurniere/');
        $turnierMap = [];
        foreach ($turniere as $turnier) {
            $id = $turnier['id'];
            $name = $turnier['turniername'];
            $turnierMap[$name] = $id;
        }
        var_dump($turnierMap);
        return $turnierMap;
    }

    function getSpielklassen() {
        $spielklassen = $this->executeRest(BASE_URL . 'Vereine/GetSpielklassen/');
        $klasseMap = [];
        foreach ($spielklassen as $klasse) {
            $id = $klasse['id'];
            $name = $klasse['klassenname'];
            $klasseMap[$name] = $id;
        }
        var_dump($klasseMap);
        return $klasseMap;
    }

    function getSpielageByturnierSpielklasse($klasse) {
        $turnierId = max(array_values($this->turniere));
        $klasseId = $this->spielklassen[$klasse];

        $spieltage = $this->executeRest(BASE_URL . 'Spieltage/GetSpieltageByTurnierSpielklasse?turnierid=' . $turnierId . '&spielklassenid=' . $klasseId);
        $spieltageMap = [];
        foreach ($spieltage as $spieltag) {
            $id = $spieltag['id'];
            $tag = date('Y-m-d', strtotime($spieltag['spieltag']));
            $spieltageMap[$tag] = $id;
        }
        var_dump($spieltageMap);
        return $spieltageMap;
    }

    function getResultTable($klasse) {
        $turnierId = max(array_values($this->turniere));
        $klasseId = $this->spielklassen[$klasse];
        $spieltage = $this->teamSpieltage[$klasse];
        $rundeId = $spieltage[$this->getMostRecent(array_keys($spieltage))];
        $results = $this->executeRest(BASE_URL . '/MKTabellen/GetMKTabellen?turnierid=' . $turnierId . '&spielklassenid=' . $klasseId . '&runde=' . $rundeId);
        var_dump($results);
        return $results;
    }

    function getMostRecent($dates) {
        $mostRecent = 0;
        $now = strtotime(date("Y-m-d"));
        foreach ($dates as $date) {
            if ($date > $mostRecent && $date < $now) {
                $mostRecent = $date;
            }
        }
        return $mostRecent;
    }

// $row = {team, division, date, flag, bp, mp}
    function saveRecords($division, $row) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $now = date("Y-m-d");
        $columns = array('verein', 'division', 'last_update', 'flag', 'platz', 'summebp', 'summemp');
        $values = array($db->quote($row['verein']), $db->quote($division), $db->quote($now), $db->quote($row['flag']), $db->quote($row['platz']), $db->quote($row['summebp']), $db->quote($row['summemp']));
        $query
                ->insert($db->quoteName(TABLE_NAME_RECORDS))
                ->columns($db->quoteName($columns))
                ->values(implode(',', $values));
        $db->setQuery($query);
        $db->execute();
    }

    function deleteRecords($division) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $conditions = array(
            $db->quoteName('division') . ' = ' . "\"$division\""
        );
        $query->delete($db->quoteName(TABLE_NAME_RECORDS));
        $query->where($conditions);
        $db->setQuery($query);
        return $db->execute();
    }

// $row = {id, spieltag}
    function saveMatches($division, $date) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $columns = array('division', 'date');
        $values = array($db->quote($division), $db->quote($date));
        $query
                ->insert($db->quoteName(TABLE_NAME_MATCHES))
                ->columns($db->quoteName($columns))
                ->values(implode(',', $values));
        $db->setQuery($query);
        $db->execute();
    }

    function deleteMatches($division) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $conditions = array(
            $db->quoteName('division') . ' = ' . "\"$division\""
        );
        $query->delete($db->quoteName(TABLE_NAME_MATCHES));
        $query->where($conditions);
        $db->setQuery($query);
        return $db->execute();
    }

    function updateDatebaseIfNeeded() {
        //$date = date('d-m-Y', strtotime("-1 day", strtotime(getMostRecent(array_keys($spieltage)))));
        //$now = strtotime(date("Y-m-d"));

        foreach (array_keys($this->teamSpieltage) as $klasse) {
            //if ($date == $now) {
            $this->deleteRecords($klasse);
            $this->deleteMatches($klasse);
            foreach ($this->teamResults[$klasse] as $result) {
                $this->saveRecords($klasse, $result);
            }
            foreach (array_keys($this->teamSpieltage[$klasse]) as $date) {
                $this->saveMatches($klasse, $date);
            }
            //}  
        }
    }

    public function doExecute() {
        $this->turniere = $this->getTurniere();
        $this->spielklassen = $this->getSpielklassen();
        $this->teamSpieltage[MANNSCHAFT_1] = $this->getSpielageByturnierSpielklasse(MANNSCHAFT_1);
        $this->teamSpieltage[MANNSCHAFT_2] = $this->getSpielageByturnierSpielklasse(MANNSCHAFT_2);
        $this->teamResults[MANNSCHAFT_1] = $this->getResultTable(MANNSCHAFT_1);
        $this->teamResults[MANNSCHAFT_2] = $this->getResultTable(MANNSCHAFT_2);
        $this->updateDatebaseIfNeeded();
    }
}

$results = new UpdateRecords();
$results->doExecute();