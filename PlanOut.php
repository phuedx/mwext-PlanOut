<?php

if ( !defined( 'MEDIAWIKI' ) ) {
	echo "This is a MediaWiki extension and cannot run standalone.\n";
	die( -1 );
}

// Classes

$autoloadClasses = array(
	'Hooks',
	'Experiments\\SimpleExperiment',
	'Experiments\\ExperimentLoader',
	'Experiments\\HookExperimentLoader',
	'Api\\QueryExperimentParams',
	'Log\\EventLoggingLogger',
);

foreach ( $autoloadClasses as $autoloadClass ) {
	$partialPath = str_replace( '\\', DIRECTORY_SEPARATOR, $autoloadClass ) . '.php';
	$path = implode( DIRECTORY_SEPARATOR, array(
		__DIR__,
		'includes',
		$partialPath,
	) );

	$wgAutoloadClasses["PlanOut\\{$autoloadClass}"] = $path;
}

// Hooks

$wgHooks['UnitTestsList'][] = function ( &$files ) {
	$files[] = __DIR__ . '/test/phpunit';

	return true;
};

$wgHooks['ResourceLoaderTestModules'][] =
	function ( array &$testModules, ResourceLoader &$resourceLoader ) {
		$testModules['qunit']['ext.planOut.test'] = array(
			'scripts' => 'modules/PlanOut.js',
			'localBasePath' => __DIR__ . '/test/qunit',
			'remoteExtPath' => 'PlanOut/test/qunit',
			'dependencies' => array(
				'ext.planOut',
			),
		);
	};

// API modules

$wgAPIModules['queryexperimentparams'] = 'PlanOut\\Api\\QueryExperimentParams';

// Modules

$wgResourceModules['ext.planOut'] = array(
	'scripts' => 'modules/PlanOut.js',
	'localBasePath' => __DIR__,
	'remoteExtPath' => 'PlanOut',
	'dependencies' => 'mediawiki.api',
	'targets' => array(
		'desktop',
		'mobile'
	),
);

// Schemas

$wgEventLoggingSchemas['PlanOutExperimentExposure'] = 10745784;
