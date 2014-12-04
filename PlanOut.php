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

// API modules

$wgAPIModules['queryexperimentparams'] = 'PlanOut\\Api\\QueryExperimentParams';
