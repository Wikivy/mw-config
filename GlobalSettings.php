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
