<?php

/***************************************************************
* Extension Manager/Repository config file for ext "amazingless".
*
* Auto generated 07-11-2012 15:16
*
* Manual updates:
* Only the data in the array - everything else is removed by next
* writing. "version" and "dependencies" must not be touched!
***************************************************************/

$EM_CONF[$_EXTKEY] = array (
	'title' => 'Adobe Digital Publishing Suite',
	'description' => 'Allows authentication for the digital publishing suite.',
	'category' => 'fe',
	'author' => 'Benjamin Mack',
	'author_email' => 'typo3@b13.de',
	'shy' => '',
	'dependencies' => '',
	'conflicts' => '',
	'priority' => '',
	'module' => '',
	'state' => 'stable',
	'internal' => '',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 1,
	'lockType' => '',
	'author_company' => 'b:dreizehn, Stuttgart',
	'version' => '1.0.0',
	'constraints' => 
	array (
		'depends' => 
		array (
		),
		'conflicts' => 
		array (
		),
		'suggests' => 
		array (
		),
	),
	'_md5_values_when_last_written' => 'a:66:{s:16:"ext_autoload.php";s:4:"2f90";s:12:"ext_icon.gif";s:4:"7e66";s:17:"ext_localconf.php";s:4:"1995";s:24:"Classes/PageRenderer.php";s:4:"2b09";s:24:"Documentation/manual.rst";s:4:"b5b2";s:39:"Resources/Contrib/lessphp/composer.json";s:4:"c6dd";s:39:"Resources/Contrib/lessphp/lessc.inc.php";s:4:"ceea";s:33:"Resources/Contrib/lessphp/LICENSE";s:4:"2887";s:32:"Resources/Contrib/lessphp/plessc";s:4:"2081";s:35:"Resources/Contrib/lessphp/README.md";s:4:"dc4a";s:38:"Resources/Contrib/lessphp/docs/docs.md";s:4:"0093";s:44:"Resources/Contrib/lessphp/tests/bootstrap.sh";s:4:"81f4";s:41:"Resources/Contrib/lessphp/tests/README.md";s:4:"19ec";s:40:"Resources/Contrib/lessphp/tests/sort.php";s:4:"36b1";s:40:"Resources/Contrib/lessphp/tests/test.php";s:4:"5ecb";s:61:"Resources/Contrib/lessphp/tests/inputs/accessors.less.disable";s:4:"8282";s:49:"Resources/Contrib/lessphp/tests/inputs/arity.less";s:4:"ad20";s:54:"Resources/Contrib/lessphp/tests/inputs/attributes.less";s:4:"1cf5";s:52:"Resources/Contrib/lessphp/tests/inputs/builtins.less";s:4:"29ce";s:50:"Resources/Contrib/lessphp/tests/inputs/colors.less";s:4:"6ed1";s:60:"Resources/Contrib/lessphp/tests/inputs/compile_on_mixin.less";s:4:"2f93";s:50:"Resources/Contrib/lessphp/tests/inputs/escape.less";s:4:"1b77";s:55:"Resources/Contrib/lessphp/tests/inputs/font_family.less";s:4:"9763";s:50:"Resources/Contrib/lessphp/tests/inputs/guards.less";s:4:"e86a";s:49:"Resources/Contrib/lessphp/tests/inputs/hacks.less";s:4:"4227";s:50:"Resources/Contrib/lessphp/tests/inputs/import.less";s:4:"6615";s:53:"Resources/Contrib/lessphp/tests/inputs/keyframes.less";s:4:"843c";s:48:"Resources/Contrib/lessphp/tests/inputs/math.less";s:4:"459f";s:49:"Resources/Contrib/lessphp/tests/inputs/media.less";s:4:"0bf2";s:48:"Resources/Contrib/lessphp/tests/inputs/misc.less";s:4:"da00";s:59:"Resources/Contrib/lessphp/tests/inputs/mixin_functions.less";s:4:"f9dc";s:65:"Resources/Contrib/lessphp/tests/inputs/mixin_merging.less.disable";s:4:"2a0b";s:50:"Resources/Contrib/lessphp/tests/inputs/mixins.less";s:4:"6a6f";s:50:"Resources/Contrib/lessphp/tests/inputs/nested.less";s:4:"8472";s:60:"Resources/Contrib/lessphp/tests/inputs/pattern_matching.less";s:4:"1158";s:50:"Resources/Contrib/lessphp/tests/inputs/scopes.less";s:4:"7b89";s:64:"Resources/Contrib/lessphp/tests/inputs/selector_expressions.less";s:4:"e012";s:54:"Resources/Contrib/lessphp/tests/inputs/site_demos.less";s:4:"4699";s:53:"Resources/Contrib/lessphp/tests/inputs/variables.less";s:4:"3fd7";s:62:"Resources/Contrib/lessphp/tests/inputs/test-imports/file1.less";s:4:"e904";s:62:"Resources/Contrib/lessphp/tests/inputs/test-imports/file2.less";s:4:"cccf";s:53:"Resources/Contrib/lessphp/tests/outputs/accessors.css";s:4:"661e";s:49:"Resources/Contrib/lessphp/tests/outputs/arity.css";s:4:"04c4";s:54:"Resources/Contrib/lessphp/tests/outputs/attributes.css";s:4:"c647";s:52:"Resources/Contrib/lessphp/tests/outputs/builtins.css";s:4:"ccb5";s:50:"Resources/Contrib/lessphp/tests/outputs/colors.css";s:4:"ec11";s:60:"Resources/Contrib/lessphp/tests/outputs/compile_on_mixin.css";s:4:"59a5";s:50:"Resources/Contrib/lessphp/tests/outputs/escape.css";s:4:"3bd5";s:55:"Resources/Contrib/lessphp/tests/outputs/font_family.css";s:4:"6fd0";s:50:"Resources/Contrib/lessphp/tests/outputs/guards.css";s:4:"cf6f";s:49:"Resources/Contrib/lessphp/tests/outputs/hacks.css";s:4:"bcb4";s:50:"Resources/Contrib/lessphp/tests/outputs/import.css";s:4:"2462";s:53:"Resources/Contrib/lessphp/tests/outputs/keyframes.css";s:4:"a572";s:48:"Resources/Contrib/lessphp/tests/outputs/math.css";s:4:"6681";s:49:"Resources/Contrib/lessphp/tests/outputs/media.css";s:4:"c1aa";s:48:"Resources/Contrib/lessphp/tests/outputs/misc.css";s:4:"519a";s:59:"Resources/Contrib/lessphp/tests/outputs/mixin_functions.css";s:4:"3d70";s:57:"Resources/Contrib/lessphp/tests/outputs/mixin_merging.css";s:4:"807f";s:50:"Resources/Contrib/lessphp/tests/outputs/mixins.css";s:4:"d378";s:50:"Resources/Contrib/lessphp/tests/outputs/nested.css";s:4:"6760";s:51:"Resources/Contrib/lessphp/tests/outputs/nesting.css";s:4:"7864";s:60:"Resources/Contrib/lessphp/tests/outputs/pattern_matching.css";s:4:"155d";s:50:"Resources/Contrib/lessphp/tests/outputs/scopes.css";s:4:"d5f0";s:64:"Resources/Contrib/lessphp/tests/outputs/selector_expressions.css";s:4:"34ed";s:54:"Resources/Contrib/lessphp/tests/outputs/site_demos.css";s:4:"65c7";s:53:"Resources/Contrib/lessphp/tests/outputs/variables.css";s:4:"ad2e";}',
	'suggests' => 
	array (
	),
);

?>