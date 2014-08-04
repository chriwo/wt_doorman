<?php
/**
 * Created by PhpStorm.
 * User: Christian Wolfram
 * Date: 04.08.14
 * Time: 11:03
 */

$extensionClasses = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('wt_doorman') . '/';

$extLoader = array(
	'tx_wtdoorman_pivars_check' => $extensionClasses . 'class.tx_wtdoorman_pivars_check.php',
	'tx_wtdoorman_RemoveXSS' => $extensionClasses . 'class.tx_wtdoorman_removexss.php',
	'tx_wtdoorman_security' => $extensionClasses . 'class.tx_wtdoorman_security.php',
);

return $extLoader;