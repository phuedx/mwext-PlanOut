<?php

if ( !defined( 'MEDIAWIKI' ) ) {
	echo "This is a MediaWiki extension and cannot run standalone.\n";
	die( -1 );
}

// Classes

$autoloadClasses = array(
	'Hooks',
	'SimpleExperiment',
	'ExperimentCollection',
);

foreach ( $autoloadClasses as $autoloadClass ) {
	$wgAutoloadClasses["PlanOut\\{$autoloadClass}"] = __DIR__ . "/includes/{$autoloadClass}.php";
}

// Hooks

$wgHooks[ 'ResourceLoaderGetConfigVars' ][] = 'PlanOut\\Hooks::onResourceLoaderGetConfigVars';
