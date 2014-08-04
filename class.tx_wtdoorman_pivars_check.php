<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2009 Alex Kellner <alexander.kellner@einpraegsam.net>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 * Hint: use extdeveval to insert/update function index above.
 */

#require_once(PATH_tslib.'class.tslib_pibase.php');
#if (!class_exists('tslib_cObj')) require_once(PATH_tslib.'class.tslib_content.php');


/**
 * Plugin 'car market' for the 'wt_carmarket' extension.
 *
 * @author	Alex Kellner <alexander.kellner@einpraegsam.net>
 * @package	TYPO3
 * @subpackage	tx_wtcarmarket
 */
class tx_wtdoorman_pivars_check {
	
	// Function clearVars() is a clears all piVars from TYPO3
	function checkDataSubmission($feObj) {
		// config
		global $TSFE;
    	$this->cObj = $TSFE->cObj; // cObject
		$this->confArr = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['wt_doorman']); // Get backandconfig
		$this->removeXSS = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('tx_wtdoorman_RemoveXSS'); // Create new instance for removeXSS class
		$varsDefinition = $this->string2array($this->confArr['varsDefinition']); // get config for doorman
		if ($this->confArr['pidInRootline'] > -1) $pid = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', $this->confArr['pidInRootline'].','.tslib_cObj::getTreeList($this->confArr['pidInRootline'], 100), 1); // array with all allowed pids
		
		// Let's go
		if ($this->confArr['pidInRootline'] > -1 && (in_array($GLOBALS['TSFE']->id, $pid) || $this->confArr['pidInRootline'] == 0)) { // if current page is allowed
			$this->sec = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('tx_wtdoorman_security'); // Create new instance for security class
			$this->sec->secParams = $this->string2array($this->confArr['varsDefinition']); // get config for backend definition
			$this->sec->delNotSetVars = $this->confArr['clearNotDefinedVars']; // now allowed params should be deleted or not?
			
			$_GET = $this->sec->sec($_GET); // overwrite GET params with vars from doorman class
			$_POST = $this->sec->sec($_POST); // overwrite POST params with vars from doorman class
		}
	}
	
	
	// Function string2array() changes string to an array (L=int,tx_indexedsearch|sword=alphanum)
	function string2array($string) {
		if (!empty($string)) {
			$temp_arr = $newarray = array();
			
			$temp_arr1 = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', $string, 1); // L=int,Lang=int => L=int AND Lang=int
			for ($i=0; $i < count($temp_arr1); $i++) { // one loop for the different vars
				$temp_arr[$i] = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode('=', $temp_arr1[$i], 1); // L=int => L AND int
				if (strpos($temp_arr[$i][0], '|') === false) { // no pipe symbol in key
					
					$newarray[$temp_arr[$i][0]] = $temp_arr[$i][1]; // set array
				
				} else { // pipe symbol in key => second level array
					
					$temp = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode('|', $temp_arr[$i][0], 1); // tx_ext_pi1|key => tx_ext_pi1 AND key
					if (count($temp) == 2) $newarray[$temp[0]][$temp[1]] = $temp_arr[$i][1]; // set array
					if (count($temp) == 3) $newarray[$temp[0]][$temp[1]][$temp[2]] = $temp_arr[$i][1]; // set array
					if (count($temp) == 4) $newarray[$temp[0]][$temp[1]][$temp[2]][$temp[3]] = $temp_arr[$i][1]; // set array
					if (count($temp) == 5) $newarray[$temp[0]][$temp[1]][$temp[2]][$temp[3]][$temp[4]] = $temp_arr[$i][1]; // set array
					
				}
			}
			return $newarray; // return array
		}
	}
	

}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/wt_doorman/class.tx_wtdoorman_pivars_check.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/wt_doorman/class.tx_wtdoorman_pivars_check.php']);
}

?>