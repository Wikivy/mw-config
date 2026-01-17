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
