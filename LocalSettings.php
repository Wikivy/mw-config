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

// Load PrivateSettings (e.g. $wgDBpassword)
require_once '/srv/mediawiki/config/PrivateSettings.php';

// Load global skins and extensions
require_once '/srv/mediawiki/config/GlobalExtensions.php';
require_once '/srv/mediawiki/config/GlobalSkins.php';

$wgPasswordSender = 'noreply@wikivy.com';
$wmgUploadHostname = 'static.wikivy.com';

$wgConf->settings += [
	// Invalidates user sessions - do not change unless it is an emergency!
	'wgAuthenticationTokenVersion' => [
		'default' => '10',
	],

	'wgEnableEditRecovery' => [
		'default' => true
	],

	'wgPrivilegedGroups' => [
		'default' => [ 'bureaucrat', 'checkuser', 'interface-admin', 'suppress', 'sysop' ],
		'+metawiki' => [ 'steward', 'techteam', 'safetyteam' ],
	],

	// AbuseFilter
	'wgAbuseFilterActions' => [
		'default' => [
			'block' => true,
			'blockautopromote' => true,
			'degroup' => false,
			'disallow' => true,
			'rangeblock' => false,
			'tag' => true,
			'throttle' => true,
			'warn' => true,
		],
	],
	'wgAbuseFilterCentralDB' => [
		'default' => 'metawiki',
		'beta' => 'metawikibeta',
	],
	'wgAbuseFilterIsCentral' => [
		'default' => false,
		'metawiki' => true,
		'metawikibeta' => true,
	],
	'wgAbuseFilterBlockDuration' => [
		'default' => 'indefinite',
	],
	'wgAbuseFilterAnonBlockDuration' => [
		'default' => 2592000,
	],
	'wgAbuseFilterNotifications' => [
		'default' => 'udp',
	],
	'wgAbuseFilterLogPrivateDetailsAccess' => [
		'default' => true,
	],
	'wgAbuseFilterPrivateDetailsForceReason' => [
		'default' => true,
	],
	'wgAbuseFilterEmergencyDisableThreshold' => [
		'default' => [
			'default' => 0.05,
		],
	],
	'wgAbuseFilterEmergencyDisableCount' => [
		'default' => [
			'default' => 2,
		],
	],

	// https://www.mediawiki.org/wiki/Special:MyLanguage/Extension:SpamBlacklist#Block_list_syntax
	'wgBlacklistSettings' => [
		'default' => [
			'spam' => [
				'files' => [
					'https://meta.wikivy.com/wiki/MediaWiki:Global_spam_blacklist?action=raw&sb_ver=1',
				],
			],
		],
		'beta' => [
			'spam' => [
				'files' => [
					'https://meta.wikivy.dev/wiki/MediaWiki:Global_spam_blacklist?action=raw&sb_ver=1',
				],
			],
		],
	],

	'wgLogSpamBlacklistHits' => [
		'default' => true,
	],

	// Cache
	'wgCacheDirectory' => [
		'default' => '/srv/mediawiki/cache',
	],

	'wgExtensionEntryPointListFiles' => [
		'default' => [
			'/srv/mediawiki/config/extension-list'
		],
	],

	// Captcha
	'wgCaptchaTriggers' => [
		'default' => [
			'edit' => false,
			'create' => false,
			'sendemail' => false,
			'addurl' => true,
			'createaccount' => true,
			'badlogin' => true,
			'badloginperuser' => true
		],
		'+metawiki' => [
			'contactpage' => true,
		],
		'+ext-WikiForum' => [
			'wikiforum' => true,
		],
	],

	'wgHCaptchaSiteKey' => [
		'default' => '94b699c0-849a-45ee-ad8a-d44e8ba2e5be',
	],
];
