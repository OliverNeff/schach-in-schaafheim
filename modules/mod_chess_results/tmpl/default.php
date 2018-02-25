<?php
// No direct access
defined('_JEXEC') or die;
?>
<div class = "clm">
    <style type="text/css">
<?php
$document = JFactory::getDocument();

$cssDir = JURI::base() . 'modules/mod_chess_results';
//	$cssDir = JURI::base().'components'.DS.'com_clm'.DS.'includes';

$document->addStyleSheet($cssDir . '/table.css', 'text/css', null, array());
?>
    </style>
    <div id="clm"><div id="rangliste">
            <div class="componentheading" >
			<br>
                <?php echo 'Spieltag: ' . $results['round'] . ' ' . $results['division']; ?>
            </div>
            <table cellpadding="0" cellspacing="0" class = "rangliste">
                <tr>
                    <th class="rang">Rg</th>
                    <th class="team">Mannschaft</th>
                    <th class="bp">BP</th>
                    <th class="mp">MP</th>
                </tr>

                <?php
                foreach ($results['rows'] as $entry) {
                    echo '<tr>';
                    if ($entry['flag'] != ' ') {
                        echo '<td class=' . $entry['flag'] . '>' . $entry['platz'] . '</td>';
                    } else {
                        echo '<td>' . $entry['platz'] . '</td>';
                    }

                    echo '<td class= team>' . $entry['verein'] . '</td>';
                    echo '<td class= bp>' . $entry['summebp'] . '</td>';
                    echo '<td class= mp>' . $entry['summemp'] . '</td>';
                    echo '</tr>';
                }
                echo '</table>';

//var_export($results);
                ?>
        </div></div></div>