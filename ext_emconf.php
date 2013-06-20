<?php

########################################################################
# Extension Manager/Repository config file for ext "moc_realurl".
#
# Auto generated 12-10-2010 13:41
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'MOC RealURL',
	'description' => 'A few optional tools for improving RealURL',
	'category' => 'be',
	'shy' => 0,
	'version' => '1.0.0',
	'dependencies' => 'realurl',
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
	'author' => 'Aske Ertmann',
	'author_email' => 'aske@moc.net',
	'author_company' => 'MOC',
	'CGLcompliance' => '',
	'CGLcompliance_note' => '',
	'constraints' => array(
		'depends' => array(
			'php' => '5.2.0-0.0.0',
			'typo3' => '4.4.0-0.0.0',
			'realurl' => '1.12.0-0.0.0'
		),
		'conflicts' => array(
		),
		'suggests' => array(
		)
	),
	'_md5_values_when_last_written' => '',
	'suggests' => array(
	)
);