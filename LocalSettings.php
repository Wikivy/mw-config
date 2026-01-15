<?php

/**
 * LocalSettings.php for Wikivy
 */
// Don't allow web access.
if ( !defined( 'MEDIAWIKI' ) ) {
	die( 'Not an entry point.' );
}

if ( PHP_SAPI !== 'cli' ) {
	header( "Cache-control: no-cache" );
}

setlocale( LC_ALL, 'en_US.UTF-8' );

require_once '/srv/mediawiki/config/initialise/WikivyFunctions.php';
$wi = new WikivyFunctions();
