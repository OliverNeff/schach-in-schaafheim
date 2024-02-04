<?php 
/**
  * @ CLM Extern Component
 * @Copyright (C) 2008-2018 CLM Team.  All rights reserved
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.chessleaguemanager.de
 * @author Thomas Schwietert
 * @email fishpoke@fishpoke.de
*/

defined('_JEXEC') or die('Restricted access');
	jimport( 'joomla.filesystem.folder' );
	// Prüfen ob die Extern Komponente existiert
	$path	= JPATH_ROOT.DS.'administrator'.DS.'components'.DS;
	$backup	= $path.'com_clm_ext';
	jimport('joomla.filesystem.file');
 	if (!JFolder::exists($backup)){
 		JError::raiseNotice( 6000,  JText::_( 'CLM Extern Modul : Die CLM Extern Komponente ist nicht installiert ! Keine Anzeige möglich !' ));
 	} else {

	// Basis-Konfigurationsparameter auslesen
	$source_id = $module->id;
	$saison	= $params->get('sid',2);
	$auto	= $params->get('auto',0);

	if($auto =="0") {
		$lid	= $params->get('lid',22);

		$lids = explode(";", $lid);
		for ($x=0;$x < count($lids); $x++) {
			$liga[]		= $lids[$x];
			$lid_name[]	= $params->get('lid_'.(1+$x),'Liga 1*');
			$lid_runde[]	= $params->get('lid_r'.(1+$x),'1');
			$lid_dg[]	= $params->get('lid_d'.(1+$x),'1');
		}
	}
$url		= preg_replace ( '/\'/', '', JRequest::getVar('url'));

$ext_view	= JRequest::getVar( 'ext_view' );
$view		= JRequest::getVar( 'view' );
$lid		= JRequest::getVar( 'liga');
$runde		= JRequest::getVar( 'runde');
$dg			= JRequest::getVar( 'dg' );
$itemid		= JRequest::getVar( 'Itemid' );
$sid		= JRequest::getVar( 'saison');
 
?>
<ul class="menu">
<?php
for( $x=0; $x < count($lids); $x++) {
// Haupttlinks des Menüs
?>
	<li id="current" class="first_link">
	<a href="index.php?option=com_clm_ext&amp;view=clm_ext&amp;ext_view=rangliste&amp;source=<?php echo $source_id; ?>&amp;saison=<?php echo $saison;?>&amp;liga=<?php echo $liga[$x]; ?>&amp;Itemid=<?php echo $itemid;?>"	<?php if ($liga[$x] == $lid AND $ext_view == 'rangliste') {echo ' class="active_link"';} ?>>
	<span><?php echo $lid_name[$x]; ?></span>
	</a>
<?php 
// Unterlinks falls Link angeklickt
if ($lid == $liga[$x] AND $saison == $sid AND $ext_view == 'rangliste' AND $view=='clm_ext' AND $source_id == $module->id) { ?>
	<ul>
		<li class="first_link liga<?php echo $liga; ?>">
		<a href="index.php?option=com_clm_ext&amp;view=clm_ext&amp;ext_view=paarungsliste&amp;source=<?php echo $source_id; ?>&amp;saison=<?php echo $saison; ?>&amp;liga=<?php echo $liga[$x]; ?>&amp;Itemid=<?php echo $itemid;?>">
		<span>Paarungsliste</span></a>
		</li>
	<?php for ($y=0; $y < $lid_runde[$x]; $y++) { ?>
		<li>
		<a href="index.php?option=com_clm_ext&amp;view=clm_ext&amp;ext_view=runde&amp;source=<?php echo $source_id; ?>&amp;saison=<?php echo $saison; ?>&amp;liga=<?php echo $liga[$x]; ?>&amp;runde=<?php echo $y+1; ?>&amp;Itemid=<?php echo $itemid;?>">
		<span>Runde <?php echo $y+1; if ($lid_dg[$x] >1) {echo " (Hinrunde)";}?></span></a>
		</li>
	<?php } $cnt = $y;
	if ($lid_dg[$x] > 1) {
	for ($y=0; $y < $lid_runde[$x]; $y++) { ?>
		<li <?php if ($view == 'runde') { ?> id="current" class="active" <?php } ?>>
		<a href="index.php?option=com_clm_ext&amp;view=clm_ext&amp;ext_view=runde&amp;source=<?php echo $source_id; ?>&amp;saison=<?php echo $saison; ?>&amp;liga=<?php echo $liga[$x]; ?>&amp;runde=<?php echo $y+1; ?>&amp;Itemid=<?php echo $itemid;?>" 
		<?php if ($view == 'runde' AND $runde == ($y+1)) { ?> class="active_link" <?php } ?>>
		<span>Runde <?php echo $y+1; ?> (Rückrunde)</span></a>
		</li>
	<?php }} ?>
		<li <?php if ($view == 'dwz_liga') { ?> id="current" class="active" <?php } ?>>
		<a href="index.php?option=com_clm_ext&amp;view=clm_ext&amp;ext_view=dwz_liga&amp;source=<?php echo $source_id; ?>&amp;saison=<?php echo $saison; ?>&amp;liga=<?php echo $liga[$x]; ?>&amp;Itemid=<?php echo $itemid;?>" <?php if ($view == 'dwz_liga') { ?> class="active_link" <?php } ?>>
		<span>DWZ Mannschaften</span></a>
		</li>

		<li <?php if ($view == 'statistik') { ?> id="current" class="active" <?php } ?>>
		<a href="index.php?option=com_clm_ext&amp;view=clm_ext&amp;ext_view=statistik&amp;source=<?php echo $source_id; ?>&amp;saison=<?php echo $saison; ?>&amp;liga=<?php echo $liga[$x];?>&amp;Itemid=<?php echo $itemid;?>" <?php if ($view == 'statistik') { ?> class="active_link" <?php } ?>>
		<span>Ligastatistiken</span></a>
		</li>
	</ul>
	<?php } ?>
	</li>
<!-- Unterlink angeklickt -->
	<?php if ($liga[$x] == $lid AND $saison == $sid AND $ext_view != 'rangliste' AND $view=='clm_ext' AND $source_id == $module->id) { ?>
	<li class="parent active">
	<ul>
		<li <?php if ($ext_view == 'paarungsliste') { ?> id="current" class="active" <?php } ?>>
		<a href="index.php?option=com_clm_ext&amp;view=clm_ext&amp;ext_view=paarungsliste&amp;source=<?php echo $source_id; ?>&amp;saison=<?php echo $saison; ?>&amp;liga=<?php echo $liga[$x]; ?>&amp;Itemid=<?php echo $itemid;?>" <?php if ($ext_view == 'paarungsliste') { ?> class="active_link" <?php } ?>>
		<span>Paarungsliste</span></a>
		</li>
	<?php for ($y=0; $y < $lid_runde[$x]; $y++) { ?>
		<li <?php if ($ext_view == 'runde' AND $dg == 1) { ?> id="current" class="active" <?php } ?>>
		<a href="index.php?option=com_clm_ext&amp;view=clm_ext&amp;ext_view=runde&amp;source=<?php echo $source_id; ?>&amp;saison=<?php echo $saison; ?>&amp;liga=<?php echo $liga[$x]; ?>&amp;runde=<?php echo $y+1; ?>&amp;dg=1&amp;Itemid=<?php echo $itemid;?>" 
		<?php if ($ext_view == 'runde' AND $runde == ($y+1)) { ?> class="active_link" <?php } ?>>
		<span>Runde <?php echo $y+1; if ($lid_dg[$x] >1) {echo " (Hinrunde)";}?></span></a>
		</li>
	<?php } $cnt = $y;
	if ($lid_dg[$x] > 1) {
	for ($y=0; $y < $lid_runde[$x]; $y++) { ?>
		<li <?php if ($ext_view == 'runde' AND $dg == 2) { ?> id="current" class="active" <?php } ?>>
		<a href="index.php?option=com_clm_ext&amp;view=clm_ext&amp;ext_view=runde&amp;source=<?php echo $source_id; ?>&amp;saison=<?php echo $saison; ?>&amp;liga=<?php echo $liga[$x]; ?>&amp;runde=<?php echo $y+1; ?>&amp;dg=2&amp;Itemid=<?php echo $itemid;?>" 
		<?php if ($ext_view == 'runde' AND $runde == ($y+1)) { ?> class="active_link" <?php } ?>>
		<span>Runde <?php echo $y+1; ?> (Rückrunde)</span></a>
		</li>
	<?php }} ?>
		<li <?php if ($ext_view == 'dwz_liga') { ?> id="current" class="active" <?php } ?>>
		<a href="index.php?option=com_clm_ext&amp;view=clm_ext&amp;ext_view=dwz_liga&amp;source=<?php echo $source_id; ?>&amp;saison=<?php echo $saison; ?>&amp;liga=<?php echo $liga[$x]; ?>&amp;Itemid=<?php echo $itemid;?>" <?php if ($ext_view == 'dwz_liga') { ?> class="active_link" <?php } ?>>
		<span>DWZ Mannschaften</span></a>
		</li>

		<li <?php if ($ext_view == 'statistik') { ?> id="current" class="active" <?php } ?>>
		<a href="index.php?option=com_clm_ext&amp;view=clm_ext&amp;ext_view=statistik&amp;source=<?php echo $source_id; ?>&amp;saison=<?php echo $saison; ?>&amp;liga=<?php echo $liga[$x]; ?>&amp;Itemid=<?php echo $itemid;?>" <?php if ($ext_view == 'statistik') { ?> class="active_link" <?php } ?>>
		<span>Ligastatistiken</span></a>
		</li>
	</ul>
	</li>
	<?php 	}
		} ?>
</ul>
	<?php } ?>
 