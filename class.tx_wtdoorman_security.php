<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2008 Alex Kellner <alexander.kellner@einpraegsam.net>
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

require_once(t3lib_extMgm::extPath('wt_doorman').'class.tx_wtdoorman_removexss.php'); // load removeXSS class


/**
 * Plugin 'doorman' for the 'wt_doorman' extension.
 *
 * @author	Alex Kellner <alexander.kellner@einpraegsam.net>
 * @package	TYPO3
 * @subpackage wt_doorman
 */
class tx_wtdoorman_security {
	var $secParams = array(); // Allowed piVars (int, text, alphanum, "value")
	var $debugMessage = 'wt_doorman debug view - don\'t forget to disable the debug)'; // message for debugoutput
	var $delNotSetVars = 1;  // Delete all piVars which are not allowed
	var $allow_removeXSS = 0;  // Use removeXSS Class to filter
	
	// Function sec() is a security function against sql injection and XSS
	function sec($piVars) {
		// config
		$this->confArr = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['wt_doorman']); // Get backandconfig
		$this->removeXSS = t3lib_div::makeInstance('tx_wtdoorman_RemoveXSS'); // Create new instance for removeXSS class
		
		// let's go
		if ($this->confArr['debug'] == 1) t3lib_div::debug($piVars, 'wt_doorman input values - '.$this->debugMessage);
		if (count($piVars) > 0) { // if piVars are set
			foreach ($piVars as $key => $value) { // one loop for every var
				if (array_key_exists($key, $this->secParams) || array_key_exists('*', $this->secParams)) { // if current key is an allowed parameter OR there is a wildcard in first level
					if (!is_array($piVars[$key])) { // first level
						if (!array_key_exists('*', $this->secParams)) { // if wildcard should NOT be used
							
							$piVars[$key] = $this->filter($piVars[$key], $this->secParams[$key]); // clean current string with his method
						
						} else { // use wildcard (all keys will be handled in the same way
						
							$piVars[$key] = $this->filter($piVars[$key], $this->secParams['*']); // clean all this strings with method from *
						
						}
					}
				
				
					else { // second level
						if (count($piVars[$key]) > 0) { // only if exists
							foreach ($piVars[$key] as $key2 => $value2) { // one row for every key in second level
								if (!is_array($value2)) { // only on second level
									if (!array_key_exists('*', $this->secParams[$key])) { // if wildcard should NOT be used
							
										$piVars[$key][$key2] = $this->filter($piVars[$key][$key2], $this->secParams[$key][$key2]); // clean current string with his method
									
									} else { // use wildcard (all keys will be handled in the same way
									
										$piVars[$key][$key2] = $this->filter($piVars[$key][$key2], $this->secParams[$key]['*']); // clean all this strings with method from *
									
									}
								} 
								else unset($piVars[$key][$key2]); // delete
							}
						}
						else unset($piVars[$key]); // delete
					}
				}
				else { // curent key exists not in allowed array
					if ($this->delNotSetVars == 1) unset($piVars[$key]); // delete
				}
			}
			
			if ($this->allow_removeXSS) $piVars = array_map(array($this->removeXSS, 'RemoveXSS'), $piVars); // removeXSS recursive for all piVars
			if ($this->confArr['debug'] == 1) t3lib_div::debug($piVars, 'wt_doorman output values - '.$this->debugMessage);
			return $piVars; // return cleaned piVars
		} 
	}
	
	
	/**
	 * Function filter() cleans string with any value
	 *
	 * @param	string		$string: given string
	 * @return	string		$string: filtered string
	 */
	function filter($string, $method = '') {
	
		switch($method) {
			
			case 'addslashes': // addslashes
				$string = addslashes($string); // disable quotes
				break;
			
			case 'int': // should be integer
				$string = intval($string); // change to integer
				break;
				
			case 'alphanum': // only numbers and letters allowed
				$string = preg_replace('/[^\sa-zA-Z0-9]/', '', $string); // replace not allowed letters with nothing (allowed: numbers, letters and space)
				break;
			
			case (strpos(str_replace(' ', '', $method), 'alphanum++') !== false): // extended alphanum found
				$signs = t3lib_div::trimExplode('++', $method, 1); // split to get signs for extension
				$string = preg_replace('/[^\sa-zA-Z0-9'.$signs[1].']/', '', $string); // replace not allowed letters with nothing (allowed: numbers, letters and space)
				break;
				
			case 'text': // should be text

				// 1. disable XSS
				if (method_exists('t3lib_div', 'removeXSS')) { // if removeXSS is available
					$string = t3lib_div::removeXSS($string); // add removeXSS
				} else { // removeXSS not available (on a very old T3 version maybe)
					$string = $this->removeXSS->RemoveXSS($string); // use own removeXSS
				}
				
				// 2. disable slashes
				$string = addslashes($string); // use addslashes
				
				break;
				
			case 'htmlentities': // change string with htmlentities
				$string = htmlentities(trim($string)); // change signs to ascii code
				break;
				
			case 'removeXSS': // change string with htmlentities
				if (method_exists('t3lib_div', 'removeXSS')) { // if removeXSS is available
					$string = t3lib_div::removeXSS($string); // add removeXSS
				} else { // removeXSS not available (on a very old T3 version maybe)
					$string = $this->removeXSS->RemoveXSS($string); // use own removeXSS
				}
				break;
			
			case (strpos($method, '"') !== false): // " found (e.g. "value1","value2")
				$set = 0; // not found at the beginning
				$tmp_method = t3lib_div::trimExplode(',', $method, 1); // split at ,
				for ($i=0; $i < count($tmp_method); $i++) { // one loop for every method (e.g. "value1")
					if ($string == str_replace('"', '', $tmp_method[$i])) { // if piVar == current value (without ")
						$string = str_replace('"', '', $tmp_method[$i]); // take string from current config
						$set = 1; // string was found
					}
				}
				if (!$set) unset($string); // delete string
				break;
			
			default: // default
				unset($string); // delete string
				
		}
		
		return $string;
	}
	
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/wt_doorman/class.tx_wtdoorman_security.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/wt_doorman/class.tx_wtdoorman_security.php']);
}

?>