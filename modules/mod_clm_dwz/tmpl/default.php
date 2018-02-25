<?php // no direct access
defined('_JEXEC') or die('Restricted access'); 

$vereine = modCLM_DWZHelper::getVereine($params);

?>

    <style type="text/css">
<?php
$document = JFactory::getDocument();
$cssDir = JURI::base() . 'modules/mod_clm_dwz';
//	$cssDir = JURI::base().'components'.DS.'com_clm'.DS.'includes';
$document->addStyleSheet($cssDir . '/dwz.css', 'text/css', null, array());
?>
    </style>


<ul class="menu">
<?php 
  foreach ($vereine as $verein) {
  	if(!is_array($verein)) {
  		echo '<li>'.$verein.'</li>';
  	}
	elseif ($verein[1] === '56023') {
		echo '<li id=\'home\'><a href="'.JRoute::_('index.php?option=com_clm&amp;view='.$verein[3].'&amp;saison='.$verein[0].'&amp;zps='.$verein[1]).'">'.$verein[2].'</a></li>';
	}
	else {
  	  	echo '<li><a href="'.JRoute::_('index.php?option=com_clm&amp;view='.$verein[3].'&amp;saison='.$verein[0].'&amp;zps='.$verein[1]).'">'.$verein[2].'</a></li>';
  	}	
  }
?></ul>