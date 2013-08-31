<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}



$extensionName = t3lib_div::underscoredToUpperCamelCase($_EXTKEY);
t3lib_extMgm::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Adobe DPS Configuration');


/**
 * Registers a Plugin to be listed in the Backend.
 * You also have to configure the Dispatcher in ext_localconf.php.
 */
Tx_Extbase_Utility_Extension::registerPlugin(
	$_EXTKEY,			// The extension name (in UpperCamelCase) or the extension key (in lower_underscore)
	'AuthApi',				// A unique name of the plugin in UpperCamelCase
	'Authentication Api Controller'	// A title shown in the backend dropdown field
);

$pluginSignature = strtolower($extensionName) . '_AuthApi';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignature] = 'layout,select_key,pages,recursive';
// activate this if you need Flexforms
// $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
// t3lib_extMgm::addPiFlexFormValue($pluginSignature, 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/list.xml');



	// Adding authtoken and uuids field to fe-users TCA
$tmpCol = array(
	'tx_adobedps_authtoken' => array(
		'exclude' => 1,
		'label' => 'Adobe DPS: Auth Token',
		'config' => array(
			'type' => 'input',
			'size' => 40
		)
	),
	'tx_adobedps_uuids' => array(
		'exclude' => 1,
		'label' => 'Adobe DPS: Used UUIDs',
		'config' => array(
			'type' => 'input',
			'size' => 40
		)
	),
);

t3lib_extMgm::addTCAcolumns('fe_users', $tmpCol, 1);
