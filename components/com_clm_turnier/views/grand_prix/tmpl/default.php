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

echo "<div class='clm'>";

// CLM Config
$config = clm_core::$db->config();

// CLM-Container
echo "<div id='clm'><div id='turnier_rangliste'>";


// Page Header
if ($this->params->get('show_title')) {
    echo CLMTurnierContent::componentTitle($this->escape($this->params->get('page_title')));
}

// Icons
echo JLayoutHelper::render('icons', array('state' => $this->state, 'params' => $this->params, 'print' => $this->print), JPATH_CLM_TURNIER_COMPONENT);

// Einleitungstext
if (is_object($this->grand_prix) && $this->grand_prix->introduction != null) {
    echo '<p>';
    echo $this->grand_prix->introduction;
    echo '</p>';
}

// Submenü
echo JLayoutHelper::render('submenu', array('state' => $this->state, 'ranglisten' => $this->get('ranglisten'), 'print' => $this->print), JPATH_CLM_TURNIER_COMPONENT);

if (count($this->gesamtwertung) == 0) {
	$string = ($this->state->get('grand_prix.rids')) ? 'TOURNAMENT_SPECIALRANKING_NOPLAYERS' : 'COM_CLM_TURNIER_KATEGORIE_GESAMTWERTUNG_NO';
    echo CLMContent::clmWarning(JText::_($string));
} else {
    $min_tournaments = $this->get('minTournaments');
    $filter = $this->state->get('grand_prix.filter');
?>

<table <?php JHtml::_('thead.tableClass', ($config->fixth_ttab == "1")); ?> id="turnier_kategorie_gesamtwertung" cellpadding="0" cellspacing="0">

	<thead>
	<tr>
		<th class="rang"><?php echo JText::_('COM_CLM_TURNIER_COL_RANG') ?></th>
		<?php if ($this->params->get('show_player_title')) { ?>
			<th class="titel"><?php echo JText::_('COM_CLM_TURNIER_COL_PLAYER_TITLE') ?></th>
		<?php } ?>
		<th class="name"><?php echo JText::_('COM_CLM_TURNIER_COL_NAME') ?></th>
		<?php if ($this->params->get('show_verein')) { ?>
			<th class="verein"><?php echo JText::_('COM_CLM_TURNIER_COL_VEREIN') ?></th>
		<?php } ?>
		<?php if ($this->params->get('show_dwz') && $this->params->get('show_elo')) { ?>
			<th class="twz"><?php echo JText::_('COM_CLM_TURNIER_COL_TWZ') ?></th>
		<?php } ?>
		<?php if ($this->params->get('show_dwz')) { ?>
			<th class="twz"><?php echo JText::_('COM_CLM_TURNIER_COL_DWZ') ?></th>
		<?php } ?>
		<?php if ($this->params->get('show_elo')) { ?>
			<th class="twz"><?php echo JText::_('COM_CLM_TURNIER_COL_ELO') ?></th>
		<?php } ?>
		
		<?php
		$linkTurnier = (boolean)($this->params->get('link_turnier') && !$this->print);
		for ($ii = 1; $ii <= $this->anzahlTurniere; $ii ++) {
        ?>
		<th class="erg">
		<?php
			$ik = $this->getTurnierIndex($ii);
    		if (is_object($this->grand_prix) && $this->grand_prix->col_header) {
		        $colTitle = strftime("%b", mktime(0, 0, 0, $ik, 1));
            } else {
		        $colTitle = $ik;
		    }
		    // Turnier gewertet
		    if ($linkTurnier && isset($this->turniere[$ik])) {
		    	$link = CLMTurnierRoute::getTurnierRanglisteRoute($this->turniere[$ik]->id, $this->params->get('link_turnier'));
				$attribs = 'class="active_link"' .
		  		        ' title="' . $this->turniere[$ik]->name . '"';
		  		        
		        $colTitle = JHtml::_('link', JRoute::_($link), $colTitle, $attribs);
            }

		    // Spaltenüberschrift ausgeben
		    echo $colTitle;
        ?>
		</th>
		<?php } ?>
		<th class="gesamt"><?php echo JText::_('COM_CLM_TURNIER_COL_GESATM') ?></th>
	</tr>
	</thead>

	<tbody>
<?php
    // alle Spieler durchgehen
    $p = 0;
    $gb = -1;
    foreach ($this->gesamtwertung as $row) {
        $style = '';
        if (count($row->ergebnis) < $min_tournaments) {
            if ($filter['tlnr']) {
                continue;
            }
            $style = 'style="color: red;"';
        }
        
        $p ++; // row count (entspricht Platzierung)
        
        $zeilenr = (($p % 2) != 0) ? '"zeile1"' : '"zeile2"';
        ?>
		
	<tr class=<?php echo $zeilenr . ' ' . $style; ?>>
		<td class="rang">  <?php echo ($row->gesamt <> $gb) ? $p . '. ' : ''; ?>			</td>
		<?php if ($this->params->get('show_player_title')) { ?>
			<td class="titel"> <?php echo $row->titel; ?> 	</td>
		<?php } ?>
		<td class="name">  <?php echo $row->name; ?> 	</td>
		<?php if ($this->params->get('show_verein')) { ?>
			<td class="verein"><?php echo $row->verein; ?></td>
		<?php } ?>
		<?php if ($this->params->get('show_dwz') && $this->params->get('show_elo')) { ?>
			<td class="twz"><?php echo ($row->twz == 0 ? '-' : $row->twz); ?></td>
		<?php } ?>
		<?php if ($this->params->get('show_dwz')) { ?>
			<td class="twz"><?php echo ($row->dwz == 0 ? '-' : $row->dwz); ?></td>
		<?php } ?>
		<?php if ($this->params->get('show_elo')) { ?>
			<td class="twz"><?php echo ($row->elo == 0 ? '-': $row->elo); ?></td>
		<?php } ?>
		<?php
        for ($ii = 1; $ii <= $this->anzahlTurniere; $ii ++) {
            $style = '';
            $ergebnis = '';
            $ik = $this->getTurnierIndex($ii);
            if (isset($row->ergebnis[$ik])) {
                $ergebnis = $row->ergebnis[$ik];
                if ($ergebnis < 0 || strcmp(strval($ergebnis), '-0') == 0) {
                    $ergebnis *= - 1;
                    $style = ' style="background-color: yellow !important;"';
                }
            }
            ?>
		<td class="erg" <?php echo $style; ?>> <?php echo $ergebnis; ?></td>
		<?php
        
        }
        $gb = $row->gesamt;
        ?>
		<td class="gesamt"> <?php echo $row->gesamt; ?>	</td>
	</tr>

<?php 
    }
?>

    </tbody>
</table>


<?php 
} 

// CLM-Container
echo '</div></div>';
echo '</div>';

?>
