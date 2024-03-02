<?php
/**
 * @ Chess League Manager (CLM) Component 
 * @Copyright (C) 2008-2021 CLM Team.  All rights reserved
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.chessleaguemanager.de
*/
defined('_JEXEC') or die('Restricted access');

class CLMModeltermineimport extends JModelLegacy {

	var $_TermineFiles;

	function __construct(){
		parent::__construct();
	}
	
	function getTermineFiles() { 
		jimport( 'joomla.filesystem.folder' );
		
		$filesDir = JPATH_COMPONENT . DIRECTORY_SEPARATOR . "pgn" . DIRECTORY_SEPARATOR;
		if (!file_exists($filesDir)) mkdir($filesDir);
		$this->TermineFiles = JFolder::files( $filesDir, '.CSV$|.csv$', false, true );
		
		return $this->TermineFiles;
	}
	
	function upload() {
		jimport( 'joomla.filesystem.file' );
		
		//Datei wird hochgeladen
		$file = clm_core::$load->request_file('termine_datei', null);
		
		//Dateiname wird bereinigt
		$filename = JFile::makeSafe($file['name']);
		$_POST['filename'] = $filename;
		//Temporärer Name und Ziel werden festgesetzt
		$src = $file['tmp_name'];
		$dest = JPATH_COMPONENT . DIRECTORY_SEPARATOR . "pgn" . DIRECTORY_SEPARATOR . $filename;
		//Datei wird auf dem Server gespeichert (abfrage auf .csv Endung)
		if ( strtolower(JFile::getExt($filename) ) == 'csv') {
			if ( JFile::upload($src, $dest) ) {
				$msg = JText::_( 'SWT_UPLOAD_SUCCESS' ); 
			} else {
				$msg = JText::_( 'SWT_UPLOAD_ERROR' );
			}
		} else {
			$msg = JText::_( 'SWT_UPLOAD_ERROR_WRONG_EXT' );
		}
		return $msg;
	}
	
	function delete() {
		jimport( 'joomla.filesystem.file' );
		
		//Name der zu löschenden Datei wird geladen
		$filename = clm_core::$load->request_string('termine_file', '');
		
		//SWT-Verzeichnis
		$path = JPATH_COMPONENT . DIRECTORY_SEPARATOR . "pgn" . DIRECTORY_SEPARATOR;
		
		//Datei löschen
		if ( JFile::delete($path.$filename) ) {
			$msg = JText::_( 'SWT_DELETE_SUCCESS' ); 
		} else {
			$msg = JText::_( 'SWT_DELETE_ERROR' ); 
		}
		return $msg;
	}
	
	function import() {
		//Name der zu importierenden Datei wird geladen
		$filename = clm_core::$load->request_string('termine_file', '');
		//pgn-Verzeichnis
		$path = JPATH_COMPONENT . DS . "pgn" . DS;
		$result = clm_core::$api->db_term_import($path.$filename,false);		
		//pgn-Verzeichnis
		$path = JPATH_COMPONENT . DS . "pgn" . DS;

		if($filename!=""&&file_exists($path.$filename)) {
			return CLMSWT::readInt($path.$filename,606,1);
		} else {
			return -1;
		}
	}
	
}

?>