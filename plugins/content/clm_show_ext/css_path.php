<?php
// Pfade zum Style Sheet und Stylesheet laden !
// Es gab ein paar Probleme mit dem Joomla Dateiseperator die diese Massnahme noetig machen

	// Falls nicht bereits aktiv.
	//require_once (JPATH_SITE . DIRECTORY_SEPARATOR . "components" . DIRECTORY_SEPARATOR . "com_clm" . DIRECTORY_SEPARATOR . "clm" . DIRECTORY_SEPARATOR . "index.php");

	// Konfigurationsparameter umspeichern
									  
	$template			= $xml->template;
	$clm_lesehilfe		= $xml->clm_lesehilfe;
	$clm_zeile1			= $xml->clm_zeile1;
	$clm_zeile2			= $xml->clm_zeile2;
	$clm_re_col			= $xml->clm_re_col;
	$clm_tableth		= $xml->clm_tableth;
	$clm_subth			= $xml->clm_subth;
	$clm_tableth_s1		= $xml->clm_tableth_s1;
	$clm_tableth_s2		= $xml->clm_tableth_s2;
	$clm_cellin_top		= $xml->clm_cellin_top;
	$clm_cellin_left	= $xml->clm_cellin_left;
	$clm_cellin_right	= $xml->clm_cellin_right;
	$clm_cellin_bottom	= $xml->clm_cellin_bottom;
	$clm_border			= $xml->clm_border;
	$clm_wrong1 		= $xml->clm_wrong1;
	$clm_wrong2			= $xml->clm_wrong2;
	$clm_rang_auf		= $xml->clm_rang_auf;
	$clm_rang_auf_evtl	= $xml->clm_rang_auf_evtl;
	$clm_rang_ab		= $xml->clm_rang_ab;
	$clm_rang_ab_evtl	= $xml->clm_rang_ab_evtl;
	$clm_msch_nr		= $xml->clm_msch_nr;
	$clm_msch_dwz		= $xml->clm_msch_dwz;
	$clm_msch_rnd		= $xml->clm_msch_rnd;
	$clm_msch_punkte	= $xml->clm_msch_punkte;
	$clm_msch_spiele	= $xml->clm_msch_spiele;
	$clm_msch_prozent	= $xml->clm_msch_prozent;
	$fe_pgn_show		= $xml->fe_pgn_show;


	if ($template) {

	$document = JFactory::getDocument();
	$document->addStyleSheet('plugins/content/clm_show_ext/clm_plg_content.css', 'text/css');
?>
<style type="text/css">

#clm .clm .pgn a, #clm .clm  a.pgn { color:<?php echo $fe_pgn_show; ?> !important; }

<?php if ($clm_lesehilfe =="1") { ?>#clm .clm tr.zeile1:hover td, #clm .clm tr.zeile2:hover td, #clm .clm tr.zeile1_auf:hover td, #clm .clm tr.zeile2_auf:hover td {background-color: #FFFFBB !important;}  /*-- Lesehilfe --*/
<?php } ?>

#clm .clm table th, #clm .clm table td { padding-left: <?php echo $clm_cellin_left; ?>; padding-top: <?php echo $clm_cellin_top; ?>; padding-right: <?php echo $clm_cellin_right; ?>; padding-bottom: <?php echo $clm_cellin_bottom; ?>; border: <?php echo $clm_border; ?>; }
#spieler div.title, #spieler div.spielerverlauf  { border-bottom: none !important; border: <?php echo $clm_border; ?>;}
.right .selectteam  { border: <?php echo $clm_border; ?>;}

#clm .clm .zeile1, #clm .clm .zeile1_dg2 { background-color: <?php echo $clm_zeile1; ?>;} 
#clm .clm .zeile2, #clm .clm .zeile2_dg2{ background-color: <?php echo $clm_zeile2; ?>;} 

#mannschaft td.punkte, #mannschaft td.spiele, #mannschaft td.prozent, #turnier_tabelle td.pkt, #turnier_tabelle td.sobe, #turnier_tabelle td.bhlz, #turnier_tabelle td.busu, #rangliste td.mp, #rangliste td.bp, td.fw_col {background-color: <?php echo $clm_re_col; ?> !important;} 

#clm .clm table th, #clm .clm table th div{ background-color: <?php echo $clm_tableth; ?>; color: <?php echo $clm_tableth_s1; ?> !important;}
#clm .clm table .anfang, #clm .clm table .ende, #clm .clm .clmbox, #spieler table.spielerverlauf th, #spieler div.spielerverlauf, #spieler .title, #clm .clm .clm-navigator ul li, #clm .clm .clm-navigator ul li ul{ background-color: <?php echo $clm_subth; ?>; color: <?php echo $clm_tableth_s2; ?> !important;}

#vereinsliste th.col_1, #vereinsliste th.col_2,#vereinsliste th.col_3,#vereinsliste th.col_4,#vereinsliste th.col_5,#vereinsliste th.col_6,#vereinsliste th.col_7,#vereinsliste th.col_8, #vereinsliste th.col{background-color: <?php echo $clm_subth; ?>; color: <?php echo $clm_tableth_s2; ?> !important;}

#wrong, .wrong {background:<?php echo $clm_wrong1; ?>; border:<?php echo $clm_wrong2; ?>;}

#mannschaft .nr { width: <?php echo $clm_msch_nr; ?>; } 
#mannschaft .dwz { width: <?php echo $clm_msch_dwz; ?>; } 
#mannschaft .rnd { width: <?php echo $clm_msch_rnd; ?>; }
#mannschaft .punkte { width: <?php echo $clm_msch_punkte; ?>; }
#mannschaft .spiele { width: <?php echo $clm_msch_spiele; ?>; }
#mannschaft .prozent{ width: <?php echo $clm_msch_prozent; ?>; } 

/* Lesehilfe AUS bei Einzelturniermodus */
#tableoverflow tr.zeile1:hover td, #tableoverflow tr.zeile2:hover td, #tableoverflow tr.zeile1_auf:hover td, #tableoverflow tr.zeile2_auf:hover td {background: none !important;}
#tableoverflow tr.zeile1:hover td.sobe, #tableoverflow tr.zeile2:hover td.sobe, #tableoverflow tr.zeile1:hover td.fw_col, #tableoverflow tr.zeile2:hover td.fw_col, #tableoverflow tr.zeile1:hover td.pkt, #tableoverflow tr.zeile2:hover td.pkt,#tableoverflow tr.zeile1:hover td.busu, #tableoverflow tr.zeile2:hover td.busu{background-color: #FFFFCC !important;} 
#tableoverflow tr.zeile1:hover td.trenner, #tableoverflow tr.zeile2:hover td.trenner {background-color: #E8E8E8 !important;}
/** Auf- und Abstieg **/

#clm .clm .rang_auf {background-color: <?php echo $clm_rang_auf; ?> !important;} 
#clm .clm .rang_auf_evtl {background-color: <?php echo $clm_rang_auf_evtl; ?> !important;} 
#clm .clm .rang_ab {background-color: <?php echo $clm_rang_ab; ?> !important;} 
#clm .clm .rang_ab_evtl {background-color: <?php echo $clm_rang_ab_evtl; ?> !important;}

</style>

<?php } ?>
