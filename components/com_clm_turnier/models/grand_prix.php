<?php
/**
 * Chess League Manager Turnier Erweiterungen
 *
 * @copyright (C) 2017 Andreas Hrubesch; All rights reserved
 * @license GNU General Public License; see https://www.gnu.org/licenses/gpl.html
 * @author Andreas Hrubesch
 */

// No direct access to this file
defined('JPATH_CLM_TURNIER_COMPONENT') or die('Restricted access');

/**
 * Grand Prix Model Class
 */
class CLM_TurnierModelGrand_Prix extends JModelLegacy {

	// Grand Prix Wertung
	protected $grandPrix = null;

	// Grand Prix Turniere für Gesamtwertung
	protected $turniere = array ();

	// Grand Prix Gesamtwertung
	protected $gesamtergebnis = array ();

	// Anzahl der gewerteten Turniere
	protected $anzahlTurniere = 0;

	// Turnier Index (bei monatlichen Turnieren)
	protected $turnierOffset = 0;

	// Sonderrangliste
	protected $rangliste = null;

	// DWZ des ersten/letzten Turniers
	private $useDwzFrom = 0;

	/**
	 * ermittelt für den Modus 'absolut' die Verteilung der Punkte für die
	 * jeweilige Platzierung innerhalb eines Turnieres.
	 * Bei gleicher Platzierung werden die Punkte auf die Spieler gleichmäßig
	 * verteilt.
	 *
	 * @param int $pk
	 *        	des Turnieres
	 *        	
	 * @return array Verteilung der Punkte
	 */
	protected function _getPunkteVerteilung($pk, $wertung) {
		$query = $this->_db->getQuery(true);

		$query->select('t1.sum_punkte, count(*) AS anzahl');
		$query->from($this->_db->quoteName('#__clm_turniere_tlnr', 't1'));
		$query->where($this->_db->quoteName('t1.turnier') . ' = ' . $pk);
		$query->group($this->_db->quoteName('t1.sum_punkte') . ' DESC');

		$this->_db->setQuery($query);
		$list = $this->_db->loadObjectList();

		$ii = 0;
		$verteilung = array ();
		foreach ($list as $row) {
			$sum = 0;
			for ($ik = 0; $ik < $row->anzahl; $ik++) {
				if (isset($wertung[$ii]))
					$sum += (int) $wertung[$ii++];
			}

			if ($sum == 0)
				break;
			$sum = round($sum / $row->anzahl, 1);

			for ($ik = 0; $ik < $row->anzahl; $ik++) {
				array_push($verteilung, $sum);
			}
		}

		return $verteilung;
	}

	/**
	 * ermittelt die Rangliste eines Turnieres.
	 *
	 * @param int $pk
	 *        	Id des Turnieres
	 *        	
	 * @return array Rangliste
	 */
	protected function _loadTurnierErgebnis($pk) {
		$query = $this->_db->getQuery(true);
		$query->select($this->_db->quoteName(explode(',', 't2.name,t2.birthYear,t2.geschlecht,t2.zps,t2.verein,t2.titel,t2.twz,t2.start_dwz,t2.FIDEelo,t2.sum_punkte,t2.anz_spiele,t2.rankingPos,t2.sumTiebr1,t2.sumTiebr2,t2.sumTiebr3,t1.dateStart')));
		$query->from($this->_db->quoteName('#__clm_turniere', 't1'));
		$query->join('INNER', $this->_db->quoteName('#__clm_turniere_tlnr', 't2') .
				' ON ' . $this->_db->quoteName('t2.turnier') . ' = ' .
				$this->_db->quoteName('t1.id'));
		$query->where($this->_db->quoteName('t1.id') . ' = ' . $pk);
		$query->order($this->_db->quoteName('t2.rankingPos') . ' ASC');
		$query->order($this->_db->quoteName('t2.sumTiebr1') . ' DESC');
		$query->order($this->_db->quoteName('t2.sumTiebr2') . ' DESC');
		$query->order($this->_db->quoteName('t2.sumTiebr3') . ' DESC');

		$this->_db->setQuery($query);
		$list = $this->_db->loadObjectList();
		return $list;
	}

	/**
	 * speichert das Turnierergebnis eines Spielers.
	 *
	 * @param integer $ii
	 *        	Index im Gesamtergebnis
	 * @param object $row
	 * @param float $punkte
	 */
	protected function _setErgebnis($ii, $row, $punkte) {
		if (isset($this->gesamtergebnis[$row->name])) {
			$spieler = $this->gesamtergebnis[$row->name];
		} else {
			$spieler = new stdClass();
			$spieler->name = $row->name;
			$spieler->birthYear = $row->birthYear;
			$spieler->geschlecht = $row->geschlecht;
			$spieler->titel = $row->titel;
			$spieler->zps = $row->zps;
			$spieler->verein = $row->verein;
			$spieler->twz = $row->twz;
			$spieler->dwz = $row->start_dwz;
			$spieler->elo = $row->FIDEelo;
			$spieler->gesamt = 0.0;
			$spieler->ergebnis = array ();
		}

		if ($this->useDwzFrom) {
			$spieler->twz = $row->twz;
			$spieler->dwz = $row->start_dwz;
			$spieler->elo = $row->FIDEelo;
		}
		$spieler->ergebnis[$ii] = floatval($punkte);
		$this->gesamtergebnis[$row->name] = $spieler;
	}

	/**
	 * berechnet das Turnierergebnis für den Modus 'Summe'.
	 *
	 * <p>
	 * Die Grand Prix Gesamtwertung berechnet sich aus den Punkten der einzelnen
	 * Turniere.
	 *
	 * Punkte := erzielten Punkte
	 * </p>
	 *
	 * @param integer $pk
	 *        	Id des Turnieres
	 * @param integer $ii
	 *        	Index im Gesamtergebnis
	 */
	protected function _getTurnierErgebnisSumme($pk, $ii) {
		$list = $this->_loadTurnierErgebnis($pk);
		foreach ($list as $row) {
			$this->_setErgebnis($ii, $row, $row->sum_punkte);
		}
	}

	/**
	 * berechnet das Turnierergebnis für den Modus 'prozentual'.
	 *
	 * <p>
	 * Die Grand Prix Gesamtwertung berechnet sich aus dem Prozentwert der
	 * Punkte der einzelnen Turniere.
	 *
	 * Punkte := erzielte Punkte / Anzahl Runden * 100
	 * </p>
	 *
	 * @param integer $pk
	 *        	Id des Turnieres
	 * @param integer $ii
	 *        	Index im Gesamtergebnis
	 */
	protected function _getTurnierErgebnisProzentual($pk, $ii) {
		$list = $this->_loadTurnierErgebnis($pk);
		foreach ($list as $row) {
			$punkte = ($row->anz_spiele == 0) ? 0 : round(($row->sum_punkte /
					$row->anz_spiele * 100), $this->grandPrix->precision);
			$this->_setErgebnis($ii, $row, $punkte);
		}
	}

	/**
	 * berechnet das Turnierergebnis für den Modus 'absolut'.
	 *
	 * <p>
	 * Die Grand Prix Gesamtwertung berechnet sich nach folgendem Schema:
	 *
	 * <ol>
	 * <li>Anhand einer vorgegebenen Punkteverteilung (Feld typ_calculation).</li>
	 *
	 * <li>Anhand der Teilnehmeranzahl des Turnieres. Die Punkteverteilung
	 * berechnet sich dabei nach folgender Formel:
	 *
	 * Punkte = Anzahl Teilnehmer – Platzierung + 1
	 * </li>
	 * </ol>
	 *
	 * Bleibt die Feinwertung unberücksichtigt, so erfolgt eine Punkteteilung
	 * bei Teilnehmern mit gleichem Turnierergebnis.
	 * </p>
	 *
	 * @param integer $pk
	 *        	Id des Turnieres
	 * @param integer $ii
	 *        	Index im Gesamtergebnis
	 */
	protected function _getTurnierErgebnisAbsolut($pk, $ii) {
		$list = $this->_loadTurnierErgebnis($pk);

		if ($this->grandPrix->typ_calculation == null ||
				trim($this->grandPrix->typ_calculation == '')) {
			$wertung = array ();
			for ($ik = count($list); $ik > 0; $ik--) {
				array_push($wertung, $ik);
			}
		} else {
			$wertung = explode(' ', $this->grandPrix->typ_calculation);
		}

		$punkte = ($this->grandPrix->use_tiebreak) ? $wertung : $this->_getPunkteVerteilung($pk, $wertung);

		foreach ($list as $row) {
			if ($row->rankingPos > 0 && $row->rankingPos <= count($punkte)) {
				$this->_setErgebnis($ii, $row, $punkte[$row->rankingPos - 1]);
			}
		}
	}

	/**
	 * ermittelt die veröffentlichten Turniere einer Kategorie (Veranstaltung).
	 *
	 * @param integer $orderBy
	 *        	Turnierereihenfolge
	 *        	<ul>
	 *        	<li>1: Turnierdatum</li>
	 *        	<li>2: Turnierreihenfolge</li>
	 *        	<li>3: Turnier ID</li>
	 *        	</ul>
	 * @return array veröffentlichte Turniere
	 */
	protected function _loadTurnierListe($orderBy) {
		$where = array ();

		// Turnierkategorie
		$catid = (int) $this->getState('grand_prix.catidEdition');
		if ($catid > 0) {
			array_push($where, $this->_db->quoteName('t1.catidAlltime') . ' = ' .
					$catid);
			array_push($where, $this->_db->quoteName('t1.catidEdition') . ' = ' .
					$catid);
		}

		// Array der Turnier ID's
		$tids = $this->getState('grand_prix.tids');
		if ($tids) {
			$list = array_diff(array_map("intval", $tids), [ 0 ]);
			if (! empty($list)) {
				array_push($where, $this->_db->quoteName('t1.id') . ' IN(' .
						implode(', ', $list) . ')');
			}
		}

		// keine Parameter
		if (count($where) == 0) {
			return array ();
		}

		// Create a new query object
		$query = $this->_db->getQuery(true);
		$query->select($this->_db->quoteName(explode(',', 't1.id,t1.name,t1.dateStart,t1.ordering')));
		$query->from($this->_db->quoteName('#__clm_turniere', 't1'));
		$query->where($this->_db->quoteName('t1.published') . ' = 1');
		$query->andWhere($where);

		// default Order == 1
		switch ($orderBy) {
			case 2 :
				$query->order($this->_db->quoteName('t1.ordering') . ' ASC');
				break;
			case 3 :
				$query->order($this->_db->quoteName('t1.id') . ' ASC');
				break;
			default :
				$query->order($this->_db->quoteName('t1.dateStart') . ' ASC');
				break;
		}

		$this->_db->setQuery($query);
		$list = $this->_db->loadObjectList();

		$this->anzahlTurniere = count($list);
		if ($this->grandPrix->col_header) {
			if ($this->anzahlTurniere > 0) {
				$date = getdate(strtotime($list[0]->dateStart));
				$this->turnierOffset = $date["mon"] - 1;
			}
			$this->anzahlTurniere = 12;
		}

		return $list;
	}

	/**
	 * berechnet die Grand Prix Gesamtwertung der veröffentlichten Turniere
	 * einer Kategorie (Veranstaltung).
	 *
	 * <p>
	 * Die Gesamtwertung wird als Array von Objekten gespeichert. Dabei wird
	 * folgendes Format verwendet:
	 * </p>
	 * <p>Array Key:
	 * <ul>
	 * <li>Spielername</li>
	 * </ul>
	 * </p>
	 * <p>Object Values:
	 * <ul>
	 * <li>name: Spielername</li>
	 * <li>titel: FIDE Titel</li>
	 * <li>gesamt: Gesamtpunktzahl aus allen Turnieren</li>
	 * <li>ergebnis: Array mit den Einzelergebnissen</li>
	 * <ul>
	 * </p>
	 * <p>
	 * Ein negatives Einzelergbnis bedeutet, dass diese in der Gesamtwertung
	 * nicht berücksichtigt ist.
	 * </p>
	 *
	 * @param integer $orderBy
	 *        	Turnierereihenfolge
	 */
	protected function _getGesamtwertung($orderBy) {
		$this->turniere = array ();
		$this->gesamtergebnis = array ();

		// veröffentlichte Turniere ermitteln
		$list = $this->_loadTurnierListe($orderBy);

		// Tunrierergebnisse berechnen
		$ii = 0;
		foreach ($list as $row) {
			$ii++;
			if ($this->grandPrix->col_header) {
				$date = getdate(strtotime($row->dateStart));
				$ii = $date["mon"];
			}

			$this->turniere[$ii] = $row;
			switch ($this->grandPrix->typ) {
				case 3 :
					$this->_getTurnierErgebnisSumme($row->id, $ii);
					break;
				case 2 :
					$this->_getTurnierErgebnisProzentual($row->id, $ii);
					break;
				default :
					$this->_getTurnierErgebnisAbsolut($row->id, $ii);
					break;
			}
		}

		// Einzelergebnisse aufaddieren
		foreach ($this->gesamtergebnis as $spieler) {
			$ergebnis = $spieler->ergebnis;
			rsort($ergebnis, SORT_NUMERIC);
			for ($ii = 0; $ii < count($ergebnis); $ii++) {
				if ($this->grandPrix->best_of == 0 ||
						$ii < $this->grandPrix->best_of) {
					$spieler->gesamt += $ergebnis[$ii];
				} else {
					// Streichresultate
					$key = array_search($ergebnis[$ii], $spieler->ergebnis);
					$spieler->ergebnis[$key] *= - 1;
				}
			}
		}

		// Zusatzpunkte
		if ($this->grandPrix->num_tournaments > 0 &&
				$this->grandPrix->extra_points > 0) {
			foreach ($this->gesamtergebnis as $spieler) {
				$nt = count($spieler->ergebnis) -
						$this->grandPrix->num_tournaments + 1;
				if ($nt > 0) {
					$spieler->gesamt += ($nt * $this->grandPrix->extra_points);
				}
			}
		}

		// Sonderrangliste(n) berücksichtigen
		if (isset($this->rangliste)) {
			$this->gesamtergebnis = array_filter($this->gesamtergebnis, array (
					$this, '_filterRangliste' ));
		}

		// Gesamtwertung sortieren
		usort($this->gesamtergebnis, function ($a, $b) {
			return $a->gesamt < $b->gesamt;
		});
	}

	/**
	 * Ranglisten Filter
	 *
	 * @param Object $value
	 * @return boolean true => aktuelle Wert in der Ergebnisliste enthalten
	 *        
	 * @see clm/com_clm/site/models/turnier_rangliste.php _getSpecialRankingWhere()
	 */
	protected function _filterRangliste($value) {
		$rv = true;
		if ($this->rangliste->use_rating_filter == 1) {
			switch ($this->rangliste->rating_type) {
				case 0 : // NWZ & ELO
					$rv = $rv &&
							($value->dwz >= $this->rangliste->rating_higher_than &&
							$value->dwz <= $this->rangliste->rating_lower_than &&
							$value->elo >= $this->rangliste->rating_higher_than &&
							$value->elo <= $this->rangliste->rating_lower_than);
					break;
				case 1 : // NWZ
					$rv = $rv &&
							($value->dwz >= $this->rangliste->rating_higher_than &&
							$value->dwz <= $this->rangliste->rating_lower_than);
					break;
				case 2 : // ELO
					$rv = $rv &&
							($value->elo >= $this->rangliste->rating_higher_than &&
							$value->elo <= $this->rangliste->rating_lower_than);
					break;
				case 3 : // NWZ vor ELO
					$wz = ($value->dwz > 0) ? $value->dwz : $value->elo;
					$rv = $rv &&
							($wz >= $this->rangliste->rating_higher_than &&
							$wz <= $this->rangliste->rating_lower_than);
					break;
				case 4 : // ELO vor NWZ
					$wz = ($value->elo > 0) ? $value->elo : $value->dwz;
					$rv = $rv &&
							($wz >= $this->rangliste->rating_higher_than &&
							$wz <= $this->rangliste->rating_lower_than);
					break;
				case 5 : // höhere Wertzahl
					$wz = ($value->elo > $value->dwz) ? $value->elo : $value->dwz;
					$rv = $rv &&
							($wz >= $this->rangliste->rating_higher_than &&
							$wz <= $this->rangliste->rating_lower_than);
					break;
			}
		}

		if ($this->rangliste->use_birthYear_filter == 1) {
			$rv = $rv &
					$value->birthYear >= $this->rangliste->birthYear_younger_than &&
					$value->birthYear <= $this->rangliste->birthYear_older_than;
		}

		if ($this->rangliste->use_sex_filter == 1 && $this->rangliste->sex != '') {
			$rv = $rv && ($value->geschlecht == $this->rangliste->sex);
		}

		if ($this->rangliste->use_sex_year_filter == 1) {
			$rv = $rv &&
					((($value->geschlecht == 'M' || $value->geschlecht == '') &&
					$value->birthYear >= $this->rangliste->maleYear_younger_than &&
					$value->birthYear <= $this->rangliste->maleYear_older_than) ||
					($value->geschlecht == 'W' &&
					$value->birthYear >=
					$this->rangliste->femaleYear_younger_than &&
					$value->birthYear <=
					$this->rangliste->femaleYear_younger_than));
		}

		if ($this->rangliste->use_zps_filter == 1) {
			$rv = $rv && $value->zps >= $this->rangliste->zps_higher_than &&
					$value->zps <= $this->rangliste->zps_lower_than;
		}

		return $rv;
	}

	/**
	 *
	 * @param JApplication $app
	 * @param string $name
	 */
	protected function setParameterFromInput($app, $name) {
		$value = $app->input->get($name);
		if (isset($value)) {
			$app->getParams()->set($name, $value);
		}
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return void
	 */
	protected function populateState() {
		$app = JFactory::getApplication('site');

		// Load the parameters.
		$params = $app->getParams();
		$this->setState('params', $params);

		// *** Layout Parameter ***
		$this->setParameterFromInput($app, 'show_dwz');
		$this->setParameterFromInput($app, 'show_elo');
		$this->setParameterFromInput($app, 'show_verein');
		$this->setParameterFromInput($app, 'show_player_title');

		$this->useDwzFrom = $app->input->getInt('use_dwz_from');
		if ($this->useDwzFrom == 0) {
			$this->useDwzFrom = $params->get('use_dwz_from');
		}

		// Load state from the request.
		$pk = $app->input->getInt('grand_prix');
		$this->setState('grand_prix.id', $pk);

		// Turnierkategorie
		$catidEdition = $app->input->getInt('kategorie');
		$this->setState('grand_prix.catidEdition', $catidEdition);

		// Turnier ID's
		$tids = $app->input->get('turniere');
		$this->setState('grand_prix.tids', $tids);

		// Sortierung
		$orderBy = $app->input->getInt('order_by');
		if ($orderBy == 0) {
			$orderBy = $params->get('order_by');
		}
		$this->setState('grand_prix.order_by', $orderBy);

		// Filter, inkl. Default Werte
		$filter = (array) $app->input->get('filter', null, 'RAW');
		$filter['tlnr'] = (isset($filter['tlnr'])) ? $filter['tlnr'] : 0;
		$this->setState('grand_prix.filter', $filter);

		// Sonderranglisten ID's
		$ids = $app->input->get('ranglisten');
		$this->setState('grand_prix.rids', $ids);

		$id = $app->input->get('rid');
		$this->setState('grand_prix.rid', $id);
	}

	/**
	 * berechnet die Grand Prix Geamtwertung.
	 *
	 * @param integer $pk
	 *        	Id der Grand Prix Wertung
	 * @param integer $orderBy
	 *        	Turnierereihenfolge
	 *        	
	 * @return mixed Grand Prix Object, false im Fehlerfall
	 */
	public function getItem($pk = null, $orderBy = null) {
		$pk = (! empty($pk)) ? $pk : (int) $this->getState('grand_prix.id');
		$orderBy = (! empty($orderBy)) ? $orderBy : (int) $this->getState('grand_prix.order_by');

		if ($this->grandPrix === null || $this->grandPrix->id != $pk) {

			// Grand Prix Wertung ermitteln
			try {
				$result = JTable::getInstance('turnier_grand_prix', 'TableCLM');
				if (! $result->load($pk)) {
					return false;
				}
				$this->grandPrix = $result;
			} catch (Exception $e) {
				$this->setError($e->getMessage());
				return false;
			}

			// Sonderrangliste(n) berücksichtigen
			$rid = $this->getState('grand_prix.rid');
			if ($rid) {
				try {
					JTable::addIncludePath(JPATH_ADMIN_CLM_COMPONENT .
							DIRECTORY_SEPARATOR . 'tables');
					$result = JTable::getInstance('sonderranglistenform', 'TableCLM');
					if ($result) {
						$result->load($rid);
						$this->rangliste = $result;
					}
				} catch (Exception $e) {
					$this->setError($e->getMessage());
				}
			}

			// Grand Prix Gesamtwertung berechnen
			try {
				$this->_getGesamtwertung($orderBy);
			} catch (Exception $e) {
				$this->setError($e->getMessage());
				return false;
			}
		}

		return $this->grandPrix;
	}

	/**
	 * ermittelt die Grand Prix Gesamtwertung.
	 *
	 * @param integer $pk
	 *        	Id der Grand Prix Wertung
	 * @param integer $orderBy
	 *        	Turnierereihenfolge
	 *        	
	 * @return <multitype:, stdClass>
	 */
	public function getGesamtWertung($pk = null, $orderBy = 0) {
		$pk = (! empty($pk)) ? $pk : (int) $this->getState('grand_prix.id');
		$orderBy = (! empty($orderBy)) ? $orderBy : (int) $this->getState('grand_prix.order_by');

		if ($this->grandPrix === null || $this->grandPrix->id != $pk) {
			$this->getItem($pk, $orderBy);
		}

		return $this->gesamtergebnis;
	}

	/**
	 * ermittelt die Anzahl der gewerteten Turniere.
	 *
	 * @param integer $pk
	 *        	Id der Grand Prix Wertung
	 *        	
	 * @return integer Anzahl der Turniere
	 */
	public function getAnzahlTurniere($pk = null) {
		$pk = (! empty($pk)) ? $pk : (int) $this->getState('grand_prix.id');
		if ($this->grandPrix === null || $this->grandPrix->id != $pk) {
			$this->getItem($pk);
		}

		return $this->anzahlTurniere;
	}

	/**
	 * ermittelt bei monatlichen Turnieren den Index des ersten Turnieres.
	 *
	 * @param integer $pk
	 *        	Id der Grand Prix Wertung
	 * @return integer Turniere Offset
	 */
	public function getTurnierOffset($pk = null) {
		$pk = (! empty($pk)) ? $pk : (int) $this->getState('grand_prix.id');
		if ($this->grandPrix === null || $this->grandPrix->id != $pk) {
			$this->getItem($pk);
		}

		return $this->turnierOffset;
	}

	/**
	 * ermittelt die Liste der gewerteten Turniere.
	 *
	 * @param integer $pk
	 *        	Id der Grand Prix Wertung
	 * @param integer $orderBy
	 *        	Turnierereihenfolge
	 * @return array
	 */
	public function getTurnierListe($pk = null, $orderBy = 0) {
		$pk = (! empty($pk)) ? $pk : (int) $this->getState('grand_prix.id');
		$orderBy = (! empty($orderBy)) ? $orderBy : (int) $this->getState('grand_prix.order_by');

		if ($this->grandPrix === null || $this->grandPrix->id != $pk) {
			$this->getItem($pk, $orderBy);
		}

		return $this->turniere;
	}

	/**
	 * ermittelt die Anzahl der Turniere zu einer Grand Prix Wertung.
	 *
	 * @param integer $pk
	 *        	Id der Grand Prix Wertung
	 * @return number
	 */
	public function getMinTournaments($pk = null) {
		$pk = (! empty($pk)) ? $pk : (int) $this->getState('grand_prix.id');
		if ($this->grandPrix === null || $this->grandPrix->id != $pk) {
			$this->getItem($pk);
		}

		if ($this->grandPrix->min_tournaments > 0 &&
				count($this->turniere) >= $this->grandPrix->min_tournaments) {
			return $this->grandPrix->min_tournaments;
		}

		return 0;
	}

	/**
	 * ermittelt die Liste der Sonderranglisten.
	 *
	 * @return array Liste der Sonderranglisten oder leeres Array
	 */
	public function getRanglisten() {
		$where = array ();

		// Array der Sonderranglisten ID's
		$ids = $this->getState('grand_prix.rids');
		if ($ids) {
			$list = array_diff(array_map("intval", $ids), [ 0 ]);
			if (! empty($list)) {
				array_push($where, $this->_db->quoteName('t1.id') . ' IN(' .
						implode(', ', $list) . ')');
			}
		}

		// keine Parameter
		if (count($where) == 0) {
			return array ();
		}

		// Create a new query object
		$query = $this->_db->getQuery(true);
		$query->select($this->_db->quoteName(explode(',', 't1.id,t1.name')));
		$query->from($this->_db->quoteName('#__clm_turniere_sonderranglisten', 't1'));
		$query->where($this->_db->quoteName('t1.published') . ' = 1');
		$query->andWhere($where);
		$query->order($this->_db->quoteName('t1.ordering') . ' ASC');

		$this->_db->setQuery($query);
		$list = $this->_db->loadObjectList();

		return $list;
	}
}

?>