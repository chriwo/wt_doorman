<?php
if (!defined ('TYPO3_MODE')) die ("Access denied.");

#####################################################
## Hook for POST / GET variables checking     #######
#####################################################

$TYPO3_CONF_VARS['SC_OPTIONS']['tslib/class.tslib_fe.php']['checkDataSubmission'][] ='EXT:wt_doorman/class.tx_wtdoorman_pivars_check.php:&tx_wtdoorman_pivars_check';

?>