<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "wt_doorman".
 *
 * Auto generated 27-05-2013 18:28
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array(
	'title' => 'wt_doorman',
	'description' => 'Security class to clean piVars against SQL injection or XSS for your whole TYPO3 installation or only for some extensions. See manual for integration or configuration.',
	'category' => 'fe',
	'shy' => 0,
	'version' => '2.0.0',
	'dependencies' => '',
	'conflicts' => '',
	'priority' => '',
	'loadOrder' => '',
	'module' => '',
	'state' => 'stable',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearcacheonload' => 0,
	'lockType' => '',
	'author' => 'Alex Kellner',
	'author_email' => 'alexander.kellner@einpraegsam.net',
	'author_company' => '',
	'CGLcompliance' => '',
	'CGLcompliance_note' => '',
	'constraints' => array(
		'depends' => array(
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:7:{s:35:"class.tx_wtdoorman_pivars_check.php";s:4:"e645";s:32:"class.tx_wtdoorman_removexss.php";s:4:"5335";s:31:"class.tx_wtdoorman_security.php";s:4:"b407";s:21:"ext_conf_template.txt";s:4:"70c3";s:12:"ext_icon.gif";s:4:"7a45";s:17:"ext_localconf.php";s:4:"aefd";s:14:"doc/manual.sxw";s:4:"c2e4";}',
);

?>