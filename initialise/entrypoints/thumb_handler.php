<?php
require_once '/srv/mediawiki/config/initialise/WikivyFunctions.php';

$currentDatabase = WikivyFunctions::getCurrentDatabase( true );

$primaryDomain = WikivyFunctions::getPrimaryDomain( $currentDatabase );
$defaultServer = WikivyFunctions::getDefaultServer( $currentDatabase );

if (
	$primaryDomain !== $defaultServer &&
	str_contains( strtolower( $_SERVER['HTTP_HOST'] ), strtolower( $defaultServer ) )
) {
	header( 'Location: ' . str_replace(
			$defaultServer, $primaryDomain,
			'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']
		), true, 301 );
	exit();
}

require WikivyFunctions::getMediaWiki( 'thumb_handler.php' );
