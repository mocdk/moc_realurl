<?php
if (!defined ('TYPO3_MODE')) {
	die('Access denied.');
}

$_EXTCONF = unserialize($_EXTCONF);
if (intval($_EXTCONF['clear_cache_recursive']) === 1) {
	$TYPO3_CONF_VARS['BE']['XCLASS']['ext/realurl/class.tx_realurl_tcemain.php'] = t3lib_extMgm::extPath($_EXTKEY) . 'class.ux_tx_realurl_tcemain.php';
}