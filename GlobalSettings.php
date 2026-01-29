<?php

$wgHooks['CreateWikiGenerateDatabaseLists'][] = 'WikivyFunctions::onGenerateDatabaseLists';
$wgHooks['ManageWikiCoreAddFormFields'][] = 'WikivyFunctions::onManageWikiCoreAddFormFields';
$wgHooks['ManageWikiCoreFormSubmission'][] = 'WikivyFunctions::onManageWikiCoreFormSubmission';
$wgHooks['MediaWikiServices'][] = 'WikivyFunctions::onMediaWikiServices';
$wgHooks['BeforePageDisplay'][] = static function ( &$out, &$skin ) {
	if ( $out->getTitle()->isSpecialPage() ) {
		$out->setRobotPolicy( 'noindex,nofollow' );
	}
	return true;
};

// Extensions
if ( $wi->dbname !== 'ldapwikiwiki' ) {
	wfLoadExtensions([
		'CentralAuth',
		'GlobalPreferences',
		'GlobalBlocking',
		'RemovePII',
	]);

	// Only allow users with global accounts to login
	$wgCentralAuthStrict = true;
	$wgCentralAuthEnableSul3 = false;

	$wgCentralAuthAutoLoginWikis = $wmgCentralAuthAutoLoginWikis;
	if ( isset( $wgAuthManagerAutoConfig['primaryauth'][LocalPasswordPrimaryAuthenticationProvider::class] ) ) {
		$wgAuthManagerAutoConfig['primaryauth'][LocalPasswordPrimaryAuthenticationProvider::class]['args'][0]['loginOnly'] = true;
	}

	$wgPasswordConfig['null'] = [ 'class' => InvalidPassword::class ];

	$wgLoginNotifyUseCentralId = true;
}

if ( $wi->isAnyOfExtensionsActive( 'WikibaseClient', 'WikibaseRepository' ) ) {
	// Includes Wikibase Configuration. There is a global and per-wiki system here.
	require_once '/srv/mediawiki/config/Wikibase.php';
}

// Dynamic cookie settings dependant on $wgServer
foreach ( $wi->getAllowedDomains() as $domain ) {
	if ( preg_match( '/' . preg_quote( $domain ) . '$/', $wi->server ) ) {
		$wgCentralAuthCookieDomain = '.' . $domain;
		$wgMFStopRedirectCookieHost = '.' . $domain;
		break;
	} else {
		$wgCentralAuthCookieDomain = '';
		if ( $wi->isExtensionActive( 'MobileFrontend' ) ) {
			$host = parse_url( $wi->server, PHP_URL_HOST );
			$wgMFStopRedirectCookieHost = $host !== false ? $host : null;

			// Don't need a global here
			unset( $host );
		}
	}
}
