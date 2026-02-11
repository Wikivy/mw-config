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

// Show custom database maintenance error page on these clusters.
$wgDatabaseClustersMaintenance = [];

require_once '/srv/mediawiki/config/initialise/WikivyFunctions.php';
$wi = new WikivyFunctions();

// Load PrivateSettings (e.g. $wgDBpassword)
require_once '/srv/mediawiki/config/PrivateSettings.php';

// Load global skins and extensions
require_once '/srv/mediawiki/config/GlobalExtensions.php';
require_once '/srv/mediawiki/config/GlobalSkins.php';

$wgPasswordSender = 'noreply@wikivy.com';
$wmgUploadHostname = 'static.wikivy.com';

$wmgSharedDomainPathPrefix = '';

$wgScriptPath = '/w';
$wgLoadScript = "$wgScriptPath/load.php";

$wgCanonicalServer = $wi->server;

if ( ( $_SERVER['HTTP_HOST'] ?? '' ) === $wi->getSharedDomain()
	|| getenv( 'MW_USE_SHARED_DOMAIN' )
) {
	if ( $wi->dbname === 'ldapwikiwiki' ) {
		print "Can only be used for SUL wikis\n";
		exit( 1 );
	}

	$wmgSharedDomainPathPrefix = "/$wgDBname";
	$wgScriptPath  = "$wmgSharedDomainPathPrefix/w";

	$wgCanonicalServer = 'https://' . $wi->getSharedDomain();
	$wgLoadScript = "{$wgCanonicalServer}$wgScriptPath/load.php";

	$wgUseSiteCss = false;
	$wgUseSiteJs = false;

	// We use load.php directly from auth for custom domains due to CSP
	$wgCentralAuthSul3SharedDomainRestrictions['allowedEntryPoints'] = [ 'load' ];
}

$wgScript = "$wgScriptPath/index.php";

$wgResourceBasePath = "$wmgSharedDomainPathPrefix/{$wi->version}";
$wgExtensionAssetsPath = "$wgResourceBasePath/extensions";
$wgStylePath = "$wgResourceBasePath/skins";
$wgLocalStylePath = $wgStylePath;

$wgConf->settings += [
	// Invalidates user sessions - do not change unless it is an emergency!
	'wgAuthenticationTokenVersion' => [
		'default' => '11',
	],

	'wgEnableEditRecovery' => [
		'default' => true
	],

	'wgPrivilegedGroups' => [
		'default' => [ 'bureaucrat', 'checkuser', 'interface-admin', 'suppress', 'sysop' ],
		'+metawiki' => [ 'steward', 'techteam' ],
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

	// CentralAuth
	'wgCentralAuthAutoCreateWikis' => [
		'default' => [
			'loginwiki',
			'metawiki',
		],
		'beta' => [
			'loginwikibeta',
			'metawikibeta',
		],
	],
	'wgCentralAuthAutoMigrate' => [
		'default' => true,
	],
	'wgCentralAuthAutoMigrateNonGlobalAccounts' => [
		'default' => true,
	],
	'wgCentralAuthCookies' => [
		'default' => true,
	],
	'wgCentralAuthCookiePrefix' => [
		'default' => 'centralauth_',
		'beta' => 'betacentralauth_',
	],
	'wgCentralAuthEnableGlobalRenameRequest' => [
		'default' => true,
	],
	'wgCentralAuthGlobalBlockInterwikiPrefix' => [
		'default' => 'meta',
	],
	'wgCentralAuthLoginWiki' => [
		'default' => 'loginwiki',
		'beta' => 'loginwikibeta',
	],
	'wgCentralAuthOldNameAntiSpoofWiki' => [
		'default' => 'metawiki',
		'beta' => 'metawikibeta',
	],
	'wgCentralAuthPreventUnattached' => [
		'default' => true,
	],
	'wgCentralAuthRestrictSharedDomain' => [
		'default' => true,
	],
	'wgCentralAuthCentralWiki' => [
		'default' => 'metawiki',
		'beta' => 'metawikibeta',
	],
	'wmgCentralAuthAutoLoginWikis' => [
		'default' => [
			'.wikivy.com' => 'metawiki'
		],
		'beta' => [
			'.wikivy.dev' => 'metawikibeta',
		],
	],
	'wgGlobalRenameDenylist' => [
		'default' => 'https://meta.wikivy.com/wiki/MediaWiki:Global_rename_blacklist?action=raw',
		'beta' => 'https://meta.wikivy.dev/wiki/MediaWiki:Global_rename_blacklist?action=raw',
	],
	'wgGlobalRenameDenylistRegex' => [
		'default' => true,
	],

	// CentralNotice
	'wgNoticeInfrastructure' => [
		'default' => false,
		'metawiki' => true,
		'metawikibeta' => true,
	],

	'wgCentralSelectedBannerDispatcher' => [
		'default' => 'https://meta.wikivy.com/wiki/Special:BannerLoader',
		'beta' => 'https://meta.wikivy.dev/wiki/Special:BannerLoader',
	],

	'wgCentralBannerRecorder' => [
		'default' => 'https://meta.wikivy.com/wiki/Special:RecordImpression',
		'beta' => 'https://meta.wikivy.dev/wiki/Special:RecordImpression',
	],

	'wgCentralDBname' => [
		'default' => 'metawiki',
		'beta' => 'metawikibeta',
	],

	'wgCentralHost' => [
		'default' => 'https://meta.wikivy.com',
		'beta' => 'https://meta.wikivy.dev',
	],

	'wgNoticeProjects' => [
		'default' => [
			'all',
			'optout',
		],
	],

	'wgNoticeUseTranslateExtension' => [
		'default' => true,
	],

	// CheckUser
	'wgCheckUserForceSummary' => [
		'default' => true,
	],

	'wgCheckUserEnableSpecialInvestigate' => [
		'default' => true,
	],

	'wgCheckUserLogLogins' => [
		'default' => true,
	],

	'wgCheckUserCAtoollink' => [
		'default' => 'metawiki',
		'beta' => 'metawikibeta',
	],

	'wgCheckUserGBtoollink' => [
		'default' => [
			'centralDB' => 'metawiki',
			'groups' => [
				'steward',
			],
		],
		'beta' => [
			'centralDB' => 'metawikibeta',
			'groups' => [
				'steward',
			],
		],
	],

	'wgCheckUserCAMultiLock' => [
		'default' => [
			'centralDB' => 'metawiki',
			'groups' => [
				'steward',
			],
		],
		'beta' => [
			'centralDB' => 'metawikibeta',
			'groups' => [
				'steward',
			],
		],
	],

	'wgCheckUserGlobalContributionsCentralWikiId' => [
		'default' => null,
		// 'default' => 'metawiki',
		//'beta' => 'metawikibeta',
	],

	// CodeMirror
	'wgCodeMirrorV6' => [
		'default' => false,
	],

	// Comments
	'wgCommentsDefaultAvatar' => [
		'default' => '/' . $wi->version . '/extensions/SocialProfile/avatars/default_ml.gif',
	],
	'wgCommentsInRecentChanges' => [
		'default' => false,
	],
	'wgCommentsSortDescending' => [
		'default' => false,
	],

	// CommentStreams
	'wgCommentStreamsEnableSearch' => [
		'default' => true,
	],
	'wgCommentStreamsNewestStreamsOnTop' => [
		'default' => false,
	],
	'wgCommentStreamsUserAvatarPropertyName' => [
		'default' => null,
	],
	'wgCommentStreamsEnableVoting' => [
		'default' => false,
	],
	'wgCommentStreamsModeratorFastDelete' => [
		'default' => false,
	],

	// ConfirmEdit
	'wgConfirmEditEnabledAbuseFilterCustomActions' => [
		'default' => [
			'showcaptcha',
		],
	],

	// Cookies
	'wgCookieExpiration' => [
		'default' => 30 * 86400,
	],
	'wgCookieSameSite' => [
		'default' => 'None',
	],
	'wgCookieSetOnAutoblock' => [
		'default' => true,
	],
	'wgCookieSetOnIpBlock' => [
		'default' => true,
	],
	'wgExtendedLoginCookieExpiration' => [
		'default' => 365 * 86400,
	],

	// Create Page
	'wgCreatePageEditExisting' => [
		'default' => false,
	],
	'wgCreatePageUseVisualEditor' => [
		'default' => false,
	],

	// CreateWiki
	'wgCreateWikiDisallowedSubdomains' => [
		'default' => [
			'(.*)wikivy(.*)',
			'subdomain',
			'example',
			'beta(meta)?',
			'prueba',
			'community',
			'testwiki',
			'wikitest',
			'help',
			'noc',
			'wc',
			'dc',
			'm',
			'sandbox',
			'outreach',
			'gazett?eer',
			'semantic(mediawiki)?',
			'accounts(internal)?',
			'(internal)?tech(internal)?',
			'sre',
			'smw',
			'wikitech',
			'wikis?',
			'www',
			'security',
			'donate',
			'blog',
			'health',
			'status',
			'acme',
			'ssl',
			'sslhost',
			'sslrequest',
			'letsencrypt',
			'deployment',
			'hostmaster',
			'wildcard',
			'list',
			'localhost',
			'mailman',
			'webmail',
			'phabricator',
			'static',
			'upload',
			'grafana',
			'icinga',
			'logging',
			'monitoring',
			'analytics',
			'csw(\d+)?',
			'phorge',
			'support',
			'forum',
			'forums',
			'matomo(\d+)?',
			'prometheus(\d+)?',
			'misc\d+',
			'db\d+',
			'cp\d+',
			'mw\d+',
			'jobrunner\d+',
			'gluster(fs)?(\d+)?',
			'ns\d+',
			'bacula\d+',
			'mail(\d+)?',
			'ldap(wiki)?(\d+)?',
			'cloud\d+',
			'mon\d+',
			'bots(\d+)',
			'kafka(\d+)?',
			'swift(ac|fs|object|proxy)?(\d+)?',
			'lizardfs\d+',
			'elasticsearch(\d+)?',
			'rdb\d+',
			'phab(\d+)?',
			'services\d+',
			'puppet\d+',
			'test\d+',
			'dbbackup\d+',
			'graylog(\d+)?',
			'mem\d+',
			'jobchron\d+',
			'mwtask(\d+)?',
			'es\d+',
			'os\d+',
			'bast(ion)?(\d+)?',
			'reports(\d+)?',
			'(.*)wiki(pedi)?a(.*)',
			'opensearch(\d+)?',
			'mywiki',
			'phorge(\d+)?',
			'issue-tracker',
		],
	],

	'wgCreateWikiCannedResponses' => [
		'default' => [
			'Approval reasons' => [
				'Perfect request' => 'Perfect. Clear purpose, scope, and topic. Please ensure your wiki complies with all aspects of the Content Policy at all times and that it does not deviate from the approved scope or else your wiki may be closed. Thank you for choosing Wikivy!',
				'Good request' => 'Pretty good. Purpose and description are a bit vague, but there is nonetheless a clear enough purpose, scope, and/or topic here. Please ensure your wiki complies with all aspects of the Content Policy at all times and that it does not deviate from the approved scope or else your wiki will be closed. Thank you for choosing Wikivy!',
				'Okay request' => 'Okay-ish. Description is somewhat vague, but the sitename, URL, and categorization suggest that this is a wiki that would follow the Content Policy made clear by the preceding fields, and it is conditionally approved as such. Please be advised that if your wiki deviates too much from this approval, remedial action can be taken by a Steward, up to and including wiki closure and potential revocation of wiki requesting privileges if necessary. Please ensure your wiki complies with all aspects of Content Policy at all times. Thank you.',
				'Categorized as private' => 'The purpose and scope of your wiki is clear enough. Please ensure your wiki complies with all aspects of the Content Policy at all times or it may be closed. Please also note that I have categorized your wiki as "Private". Thank you.',
				'Categorized as nsfw' => 'The purpose and scope of your wiki is clear enough. Please ensure your wiki complies with all aspects of the Content Policy at all times or it may be closed. Please also note that I have categorized your wiki as "NSFW". Thank you.',
			],
			'Decline reasons' => [
				'Needs more details' => 'Can you give us more details on the purpose for, scope of, and topic of your wiki, ideally in at least 2-3 sentences? Please update your request via the "Edit request" tab and add to, but do not replace, your existing description. Thank you.',
				'Invalid or unclear subdomain' => 'The scope and purpose of your wiki seem clear enough. However, the requested subdomain is either invalid, too generic, conveys a Wikivy affiliation, or suggests that the wiki is an English language or multilingual wiki when it is not. Please change it to something that better reflects your wiki\'s purpose and scope. Thank you.',
				'Invalid sitename/subdomain (obscene wording)' => 'The scope and purpose of your wiki seem clear enough. However, the requested wiki name or subdomain is in violation of our Content Policy, which prohibits obscene wording in wiki names and subdomains. Please change it to something that is appropriate. Thank you.',
				'Use Public Test Wiki' => 'Please use Public Test Wiki (https://publictestwiki.com) to test the administrator and bureaucrat tools, as well as Wikivy since the wiki is hosted by us. Please follow all local policies, reverting all tests you perform in the reverse order which you performed them. Local permissions can be requested at TestWiki:Request permissions. Thank you.',
				'Database exists (wiki active)' => 'A wiki already exists at the selected subdomain. Please visit the local wiki and contribute there. Please reach out to any local bureaucrat to request any permissions if you require them; if bureaucrats are not active on the wiki after a reasonable period of time, please start a local election and ask a Steward to evaluate it at Steward requests. Thank you.',
				'Database exists (wiki closed)' => 'A wiki already exists at the selected subdomain selected but is closed. Please visit the Requests for reopening wikis page to request to reopen the wiki or ask for help on the Community portal.',
				'Database exists (wiki already deleted)' => 'A wiki already exists at the selected subdomain but has been deleted in accordance with the Dormancy Policy. I will request a Steward undelete it for you. When it has been undeleted and reopened, please visit the local wiki and ensure you make at least one edit or log action every 45 days. Wikis are only deleted after 6 months of complete inactivity; if you require a Dormancy Policy exemption, you should review the policy and request it once your wiki has at least 40-60 content pages. Thank you.',
				'Database exists (wiki undeleted)' => 'A wiki already exists at the selected subdomain; it was previously closed/deleted but has been reopened. Please visit the wiki and ensure you make at least one edit or log action every 45 days. Wikis are only deleted after 6 months of complete inactivity. Please reach out to any local bureaucrat to request any permissions if you require them. If bureaucrats are not active on the wiki after a reasonable period of time, please start a local election and ask a Steward to evaluate it at Steward requests. Thank you.',
				'Database exists (unrelated purpose)' => 'A wiki already exists at the selected subdomain; however, the wiki does not seem to have the same purpose as the one you are requesting here, so you will need to request a different subdomain. Please update this request once you have selected a new subdomain to reopen it for consideration.',
				'Duplicate request (not enough information)' => 'Declining as a duplicate request that needs more information. Please do not edit this request; instead, you should go back into your original request and refrain from submitting duplicate requests in the future. Thank you.',
				'Duplicate request (already approved)' => 'Declining as a duplicate of a request that has already been approved. Any changes to your wiki should be made via ManageWiki locally or requested at Steward requests or Phabricator whenever unavailable via ManageWiki. Thank you.',
				'Excessive requests' => 'Declining as you have requested an excessive amount of wikis. If you believe you have legitimate need for this amount of wikis, please reply to this request with a 2-3 sentence reasoning on why you need the wikis.',
				'Vandal request' => 'Declining as this wiki request is product of either vandalism or trolling.',
				'Content Policy (commercial activity)' => 'Declining per Content Policy provision, "The primary purpose of your wiki cannot be for commercial activity." Thank you for understanding. If in error, please edit this wiki request and articulate a clearer purpose and scope for your wiki that makes it clear how this wiki would not violate this criterion of Content Policy.',
				'Content Policy (deceive, defraud or mislead)' => 'Declining per Content Policy provision, "Wikivy does not host wikis with the sole purpose of deceiving, defrauding, or misleading people." Thank you for your understanding.',
				'Content Policy (duplicate/similar wiki)' => 'Your proposed wiki appears to duplicate, either substantially or entirely, the scope of an existing wiki, which is prohibited by the Content Policy. Please contribute to the existing wiki instead; if you feel that this is in error, please describe in a few sentences how your wiki will not violate this policy. Thank you.',
				'Content Policy (file sharing service)' => 'Declining per Content Policy provision, "Wikivy does not host wikis whose main purpose is to act as a file sharing service." Thank you for your understanding.',
				'Content Policy (forks)' => 'Declining per Content Policy provision, "Direct forks of other Wikivy wikis where no attempts at mediations are made are not allowed." Thank you for your understanding.',
				'Content Policy (illegal US activity)' => 'Declining per Content Policy provision, "Wikivy does not host any content that is illegal in the United States." Thank you for understanding. If you believe this decline reason was used incorrectly, please address this with the declining wiki reviewer on their user talk page first before escalating your concern to Steward requests. Thank you.',
				'Content Policy (makes it difficult for other wikis)' => 'Declining per Content Policy provision, "A wiki must not create problems which make it difficult for other wikis." Thank you for your understanding.',
				'Content Policy (no anarchy wikis)' => 'Declining per Content Policy provision, "Wikivy does not host wikis that operate on the basis of an anarchy system (i.e. no leadership and no rules)." Thank you for your understanding.',
				'Content Policy (sexual nature involving minors)' => 'Declining per Content Policy provision, "Wikivy does not host wikis of a sexual nature which involve minors in any way." Thank you for your understanding.',
				'Content Policy (toxic communities)' => 'Declining per Content Policy provision, "Wikivy does not host wikis where the community has developed in such a way as to be characterised as toxic." Thank you for your understanding.',
				'Content Policy (unsubstantiated insult)' => 'Declining per Content Policy provision, "Wikivy does not host wikis which spread unsubstantiated insult, hate or rumours against a person or group of people." Thank you for your understanding.',
				'Content Policy (violence, hatred or harrassment)' => 'Declining per Content Policy provision, "Wikivy does not host wikis that promote violence, hatred, or harassment against a person or group of people." Thank you for your understanding.',
				'Content Policy (Wikimedia-like wikis/forks)' => 'Declining per Content Policy provision, "Direct forks and forks where a substantial amount of content is copied from a Wikimedia project are not allowed." Thank you for your understanding.',
				'Content Policy (Reception wiki)' => 'Declining per Content Policy provision, "Wikis should not be structured around bullet-point, good/bad commentary." Thank you for your understanding.',
				/* 'Content Policy (additional restrictions)' => 'Declining per the Content Policy's additional restrictions, which includes the topic of your wiki. Thank you for your understanding.', */
				'Author request' => 'Declined at the request of the wiki requester.',
			],
			'On hold reasons' => [
				'On hold pending response' => 'On hold pending response from the wiki requester (see the "Request Comments" tab). Please reply to the questions left by the wiki reviewer on this request, but do not create another wiki request. Thank you.',
				'On hold pending review from another wiki reviewer' => 'On hold pending review from another wiki reviewer or a Steward.',
			],
		],
	],

	'wgCreateWikiDatabaseClusters' => [
		'default' => [
			'db01 (c1)' => 'c1',
		],
		'beta' => [
			'db01 (c1)' => 'c1',
		],
	],

	'wgCreateWikiDatabaseSuffix' => [
		'default' => 'wiki',
		'beta' => 'wikibeta',
	],
	'wgCreateWikiDisableRESTAPI' => [
		'default' => true,
		'metawiki' => false,
		'metawikibeta' => false,
	],
	'wgCreateWikiEmailNotifications' => [
		'default' => true,
	],
	'wgCreateWikiEnableManageInactiveWikis' => [
		'default' => true,
	],
	'wgCreateWikiSQLFiles' => [
		'default' => [
			"$IP/sql/mysql/tables-generated.sql",
			"$IP/extensions/AbuseFilter/db_patches/mysql/tables-generated.sql",
			"$IP/extensions/AntiSpoof/sql/mysql/tables-generated.sql",
			"$IP/extensions/BetaFeatures/sql/tables-generated.sql",
			"$IP/extensions/CheckUser/schema/mysql/tables-generated.sql",
			"$IP/extensions/DataDump/sql/data_dump.sql",
			"$IP/extensions/Echo/sql/mysql/tables-generated.sql",
			"$IP/extensions/GlobalBlocking/sql/mysql/tables-generated-global_block_whitelist.sql",
			"$IP/extensions/Linter/sql/mysql/tables-generated.sql",
			"$IP/extensions/MediaModeration/schema/mysql/tables-generated.sql",
			"$IP/extensions/OAuth/schema/mysql/tables-generated.sql",
			"$IP/extensions/RottenLinks/sql/rottenlinks.sql",
			"$IP/extensions/UrlShortener/schemas/mysql/tables-generated.sql",
		],
	],
	'wgCreateWikiStateDays' => [
		'default' => [
			'inactive' => 60,
			'closed' => 60,
			'removed' => 245,
			'deleted' => 31
		],
	],
	'wgCreateWikiCacheDirectory' => [
		'default' => '/srv/mediawiki/cache'
	],
	'wgCreateWikiCategories' => [
		'default' => [
			'Select an option...' => '',
			'Art & Architecture' => 'artarc',
			'Automotive' => 'automotive',
			'Business & Finance' => 'businessfinance',
			'Community' => 'community',
			'Education' => 'education',
			'Electronics' => 'electronics',
			'Entertainment' => 'entertainment',
			'Fandom' => 'fandom',
			'Fantasy' => 'fantasy',
			'Gaming' => 'gaming',
			'Geography' => 'geography',
			'History' => 'history',
			'Humour/Satire' => 'humour',
			'Language/Linguistics' => 'langling',
			'Leisure' => 'leisure',
			'Literature/Writing' => 'literature',
			'Media/Journalism' => 'media',
			'Medicine/Medical' => 'medical',
			'Military/War' => 'military',
			'Music' => 'music',
			'Podcast' => 'podcast',
			'Politics' => 'politics',
			'Private' => 'private',
			'Religion' => 'religion',
			'Science' => 'science',
			'Software/Computing' => 'software',
			'Song Contest' => 'songcontest',
			'Sports' => 'sport',
			'Uncategorised' => 'uncategorised',
		],
	],
	'wgCreateWikiInactiveExemptReasonOptions' => [
		'default' => [
			'Wiki completed and made to be read' => 'comp',
			'Wiki made for time-based gathering' => 'tbg',
			'Wiki made to be read' => 'mtr',
			'Temporary exemption for exceptional hardship, see DPE' => 'temphardship',
			'Other, see DPE' => 'other',
		],
	],
	'wgCreateWikiRequestCountWarnThreshold' => [
		'default' => 5,
	],
	'wgCreateWikiSubdomain' => [
		'default' => 'wikivy.com',
		'beta' => 'wikivy.dev',
	],
	'wgCreateWikiUseClosedWikis' => [
		'default' => true,
	],
	'wgCreateWikiUseEchoNotifications' => [
		'default' => true,
	],
	'wgCreateWikiUseExperimental' => [
		'default' => true,
	],
	'wgCreateWikiUseInactiveWikis' => [
		'default' => true,
	],
	'wgCreateWikiUsePrivateWikis' => [
		'default' => true,
	],
	/*'wgCreateWikiContainers' => [
		'default' => [
			'avatars' => 'public-private',
			'awards' => 'public-private',
			'local-public' => 'public-private',
			'local-thumb' => 'public-private',
			'local-transcoded' => 'public-private',
			'local-temp' => 'private',
			'local-deleted' => 'private',
			'dumps-backup' => 'public-private',
			'phonos-render' => 'public-private',
			'timeline-render' => 'public-private',
			'upv2avatars' => 'public-private',
		],
	],*/
	'wgCreateWikiUseJobQueue' => [
		'default' => true,
	],
	'wgRequestWikiMinimumLength' => [
		'default' => 350,
	],
	'wgRequestWikiConfirmAgreement' => [
		'default' => true,
	],

	// CookieWarning
	'wgCookieWarningMoreUrl' => [
		'default' => 'https://meta.wikivy.com/wiki/Special:MyLanguage/Privacy_Policy#2._Cookies',
	],
	'wgCookieWarningEnabled' => [
		'default' => true,
	],
	'wgCookieWarningGeoIPLookup' => [
		'default' => 'php',
	],
	'wgCookieWarningGeoIp2' => [
		'default' => true,
	],
	'wgCookieWarningGeoIp2Path' => [
		'default' => '/srv/GeoLite2-City.mmdb',
	],

	// Database
	'wgAllowSchemaUpdates' => [
		'default' => false,
	],
	'wgDBadminuser' => [
		'default' => 'wikivy',
	],
	'wgDBuser' => [
		'default' => 'wikivy',
	],
	'wgReadOnly' => [
		'default' => false,
	],
	'wgSharedDB' => [
		'default' => null,
	],
	'wgSharedTables' => [
		'default' => [],
	],
	/*'wgDBmwschema' => [
		'default' => 'public'
	],*/
	'+wgVirtualDomainsMapping' => [
		'default' => [
			'virtual-botpasswords' => [
				'db' => $wi->getGlobalDatabase(),
			],
			'virtual-centralauth' => [
				'db' => $wi->getGlobalDatabase(),
			],
			'virtual-centralnotice' => [
				'db' => $wi->getCentralDatabase(),
			],
			'virtual-checkuser-global' => [
				'db' => $wi->getGlobalDatabase(),
			],
			'virtual-createwiki-central' => [
				'db' => $wi->getCentralDatabase(),
			],
			'virtual-globalblocking' => [
				'db' => $wi->getGlobalDatabase(),
			],
			'virtual-globaljsonlinks' => [
				'db' => 'commonswiki',
			],
			'virtual-globalnewfiles' => [
				'db' => $wi->getGlobalDatabase(),
			],
			'virtual-importdump' => [
				'db' => $wi->getCentralDatabase(),
			],
			'virtual-incidentreporting' => [
				'db' => $wi->getIncidentsDatabase(),
			],
			'virtual-interwiki' => [
				'db' => $wi->getCentralDatabase(),
			],
			'virtual-LoginNotify' => [
				'db' => $wi->getGlobalDatabase(),
			],
			'virtual-managewiki-central' => [
				'db' => $wi->getCentralDatabase(),
			],
			'virtual-matomoanalytics' => [
				'db' => $wi->getGlobalDatabase(),
			],
			'virtual-oathauth' => [
				'db' => $wi->getGlobalDatabase(),
			],
			'virtual-requestcustomdomain' => [
				'db' => $wi->getCentralDatabase(),
			],
		],
		'+beta' => [
			'virtual-botpasswords' => [
				'db' => 'metawikibeta',
			],
			'virtual-globaljsonlinks' => [
				'db' => 'commonswikibeta',
			],
		]
	],

	// DiscordNotifications
	'wgDiscordAvatarUrl' => [
		'default' => '',
	],
	'wgDiscordFromName' => [
		'default' => $wi->sitename,
	],
	'wgDiscordIgnoreMinorEdits' => [
		'default' => false,
	],
	'wgDiscordIncludePageUrls' => [
		'default' => true,
	],
	'wgDiscordIncludeUserUrls' => [
		'default' => true,
	],
	'wgDiscordIncludeDiffSize' => [
		'default' => true,
	],
	'wgDiscordNotificationEnabledActions' => [
		'default' => [
			'AddedArticle' => true,
			'EditedArticle' => true,
			'MovedArticle' => true,
			'ProtectedArticle' => true,
			'RemovedArticle' => true,
			'UnremovedArticle' => true,
			'AfterImportPage' => true,
			'FileUpload' => true,
			'BlockedUser' => true,
			'NewUser' => true,
			'UserGroupsChanged' => true,
			'ModerationPending' => true,
		],
	],
	'wgDiscordNotificationShowImage' => [
		'default' => true,
	],
	'wgDiscordNotificationShowSuppressed' => [
		'default' => false,
	],
	'wgDiscordNotificationCentralAuthWikiUrl' => [
		'default' => 'https://meta.wikivy.com/',
	],
	'wgDiscordNotificationIncludeAutocreatedUsers' => [
		'default' => true,
		'commonswiki' => false,
		'devwiki' => false,
		'loginwiki' => false,
		'metawiki' => false,
		'testwiki' => false,
	],
	'wgDiscordAdditionalIncomingWebhookUrls' => [
		'default' => [],
	],
	'wgDiscordDisableEmbedFooter' => [
		'default' => false,
	],
	'wgDiscordExcludeConditions' => [
		'default' => [
			'experimental' => [
				'article_inserted' => [
					'groups' => [
						'sysop',
					],
					'permissions' => [
						'bot',
						'managewiki-core',
						'managewiki-extensions',
						'managewiki-namespaces',
						'managewiki-permissions',
						'managewiki-settings',
					],
				],
				'article_saved' => [
					'groups' => [
						'sysop',
					],
					'permissions' => [
						'bot',
						'managewiki-core',
						'managewiki-extensions',
						'managewiki-namespaces',
						'managewiki-permissions',
						'managewiki-settings',
					],
				],
			],
			'users' => [
				// Exclude excessive bots from all feeds
				'Creaturawikibot',
				'FuzzyBot',
				'HispanoBOT',
			],
		],
		'+commonswiki' => [
			'groups' => [
				'bot',
			],
		],
		'+devwiki' => [
			'groups' => [
				'bot',
			],
		],
		'+metawiki' => [
			'article_inserted' => [
				'groups' => [
					'bot',
					'flood',
				],
			],
			'article_saved' => [
				'groups' => [
					'bot',
					'flood',
				],
			],
		],
		'+testwiki' => [
			'groups' => [
				'bot',
			],
		],
	],
	'wgDiscordEnableExperimentalCVTFeatures' => [
		'default' => true,
	],
	'wgDiscordExperimentalCVTMatchFilter' => [
		'default' => [ '(n[1i!*]gg[3*e]r|r[e3*]t[4@*a]rd|f[@*4]gg[0*o]t|ch[1!i*]nk)' ],
	],
	'wgDiscordExperimentalFeedLanguageCode' => [
		'default' => 'en',
	],

	// DiscussionTools
	'wgDiscussionTools_visualenhancements' => [
		'default' => 'default',
		'isvwiki' => 'available',
	],
	'wgDiscussionTools_visualenhancements_reply' => [
		'default' => 'default',
		'isvwiki' => 'available',
	],
	'wgDiscussionTools_visualenhancements_pageframe' => [
		'default' => 'default',
		'isvwiki' => 'available',
	],

	// Echo
	'wgEchoCrossWikiNotifications' => [
		'default' => true,
	],
	'wgEchoUseJobQueue' => [
		'default' => true,
	],
	'wgEchoSharedTrackingCluster' => [
		'default' => 'echo',
		'beta' => 'beta',
	],
	'wgEchoSharedTrackingDB' => [
		'default' => 'metawiki',
		'beta' => 'metawikibeta',
	],
	'wgEchoUseCrossWikiBetaFeature' => [
		'default' => true,
	],
	'wgEchoMentionStatusNotifications' => [
		'default' => true,
	],
	'wgEchoMaxMentionsInEditSummary' => [
		'default' => 0,
	],
	'wgEchoPerUserBlacklist' => [
		'default' => true,
	],
	'wgEchoWatchlistNotifications' => [
		'default' => false,
	],
	'wgEchoWatchlistEmailOncePerPage' => [
		'default' => true,
	],

	// Footers
	'+wgFooterIcons' => [
		'default' => [
			'wikivy' => [
				'wikivy' => [
					'src' => 'https://static.wikivy.com/commonswiki/1/15/Powered_by_Wikivy_%28no_box%29.svg',
					'url' => 'https://meta.wikivy.com/wiki/Special:MyLanguage/Wikivy_Meta',
					'alt' => 'Hosted by Wikivy',
				],
			],
		],
	],
	'wmgWikiapiaryFooterPageName' => [
		'default' => '',
	],

	'wgMaxCredits' => [
		'default' => 0,
	],
	'wgShowCreditsIfMax' => [
		'default' => true,
	],

	// Files
	'wgEnableUploads' => [
		'default' => true,
	],
	'wgEnableAsyncUploads' => [
		'default' => true,
	],
	'wgMaxUploadSize' => [
		'default' => 1024 * 1024 * 4096
	],
	'wgAllowCopyUploads' => [
		'default' => false,
	],
	'wgCopyUploadsFromSpecialUpload' => [
		'default' => false,
	],
	'wgFileExtensions' => [
		'default' => [
			'djvu',
			'gif',
			'ico',
			'jpg',
			'jpeg',
			'ogg',
			'pdf',
			'png',
			'svg',
			'webp',
		],
	],
	'wgUseQuickInstantCommons' => [
		'default' => true,
	],
	'wgQuickInstantCommonsPrefetchMaxLimit' => [
		'default' => 1000,
	],
	'wgMaxImageArea' => [
		'default' => 10e7,
	],
	'wgMaxAnimatedGifArea' => [
		'default' => '1.25e7',
	],
	'wgWikivyCommons' => [
		'default' => true,
	],
	'wgWikivyReportsBlockAlertKeywords' => [
		'default' => [
			'underage',
			'under age',
			'under 13',
			'death threats',
			'death threat',
			'child pornography',
			'images of children',
			'images of minors',
			'suicide',
			'kill me',
			'kill themselves',
			'kill themselfs',
			'kill themself',
			'murder',
			'terrorist',
			'terrorism',
			'bomb threat',
			'bomb hoax',
		],
	],
	'wgEnableImageWhitelist' => [
		'default' => false,
	],
	'wgImagePreconnect' => [
		'default' => true,
	],
	'wgImgTagSanitizeDomain' => [
		'default' => false,
	],
	'wgShowArchiveThumbnails' => [
		'default' => true,
	],
	'wgVerifyMimeType' => [
		'default' => true,
	],
	'wgSVGMetadataCutoff' => [
		'default' => 5242880,
	],
	'wgSVGConverter' => [
		'default' => 'rsvg',
	],
	'wgSVGConverterPath' => [
		'default' => '/usr/local/bin',
	],
	'wgSVGNativeRendering' => [
		'default' => true,
	],
	'wgUploadMissingFileUrl' => [
		'default' => false,
	],
	'wgUploadNavigationUrl' => [
		'default' => false,
	],

	// GlobalCssJs
	'wgGlobalCssJsConfig' => [
		'default' => [
			'wiki' => 'metawiki',
			'source' => 'metawiki',
		],
		'beta' => [
			'wiki' => 'metawikibeta',
			'source' => 'metawikibeta',
		],
	],
	'+wgResourceLoaderSources' => [
		'default' => [
			'metawiki' => [
				'apiScript' => '//meta.wikivy.com/w/api.php',
				'loadScript' => '//meta.wikivy.com/w/load.php',
			],
		],
		'beta' => [
			'metawikibeta' => [
				'apiScript' => '//meta.wikivy.com/w/api.php',
				'loadScript' => '//meta.wikivy.com/w/load.php',
			],
		],
	],
	'wgUseGlobalSiteCssJs' => [
		'default' => false,
	],

	// Grant Permissions for BotPasswords and OAuth
	'+wgGrantPermissions' => [
		'default' => [
			'basic' => [
				'user' => true,
			],
			'usedatadumpapi' => [
				'view-dump' => true,
				'generate-dump' => true,
				'delete-dump' => true,
			],
		],
	],
	'+wgGrantPermissionGroups' => [
		'default' => [],
	],

	// ImageMagick
	'wgUseImageMagick' => [
		'default' => true,
	],
	'wgImageMagickConvertCommand' => [
		'default' => '/usr/local/bin/mediawiki-firejail-convert',
	],
	'wgJpegPixelFormat' => [
		'default' => 'yuv420',
	],
	'wgSharpenParameter' => [
		'default' => '0x0.8',
	],
	'wgImageMagickTempDir' => [
		'default' => '/tmp/magick-tmp',
	],

	// Image Limits
	'wgImageLimits' => [
		'default' => [
			[ 320, 240 ],
			[ 640, 480 ],
			[ 800, 600 ],
			[ 1024, 768 ],
			[ 1280, 1024 ],
			[ 2560, 2048 ],
		],
		'dmlwikiwiki' => [
			[ 320, 240 ],
			[ 640, 480 ],
			[ 800, 800 ],
		],
	],

	// Interwiki
	'wgEnableScaryTranscluding' => [
		'default' => true,
	],
	'wgExtraInterlanguageLinkPrefixes' => [
		'default' => [
			'simple',
		],
	],
	'wgExtraLanguageNames' => [
		'default' => [
			// Prevent mh from being treated as an interlanguage link (T11615)
			'wv' => '',
		]
	],

	// InterwikiDispatcher
	'wgIWDPrefixes' => [
		'default' => [
			'fandom' => [
				/** Fandom */
				'interwiki' => 'fandom',
				'url' => 'https://$2.fandom.com/wiki/$1',
				'urlInt' => 'https://$2.fandom.com/$3/wiki/$1',
				'baseTransOnly' => true,
			],
			'miraheze' => [
				/** Miraheze */
				'interwiki' => 'mh',
				'url' => 'https://$2.miraheze.org/wiki/$1',
				'dbname' => '$2wiki',
				'baseTransOnly' => true,
			],
			'wikitide' => [
				/** WikiTide */
				'interwiki' => 'wt',
				'url' => 'https://$2.wikitide.org/wiki/$1',
				'dbname' => '$2wiki',
				'baseTransOnly' => true,
			],
			'wikioasis' => [
				/** WikiOasis */
				'interwiki' => 'wo',
				'url' => 'https://$2.wikioasis.org/wiki/$1',
				'dbname' => '$2wiki',
				'baseTransOnly' => true,
			],
			'wikivy' => [
				/** Wikivy */
				'interwiki' => 'wv',
				'url' => 'https://$2.wikivy.com/wiki/$1',
				'dbname' => '$2wiki',
				'baseTransOnly' => true,
			],
			'wiki_gg' => [
				/** Wiki.gg */
				'interwiki' => 'wgg',
				'url' => 'https://$2.wiki.gg/wiki/$1',
				'urlInt' => 'https://$2.wiki.gg/$3/wiki/$1',
				'baseTransOnly' => true,
			],
		],
	],

	// JsonConfig
	'wgJsonConfigEnableLuaSupport' => [
		'default' => true,
	],
	'wgJsonConfigInterwikiPrefix' => [
		'default' => 'commons',
		'commonswiki' => 'meta',
	],
	'wgJsonConfigModels' => [
		'default' => [
			'Map.JsonConfig' => JsonConfig\JCMapDataContent::class,
			'Tabular.JsonConfig' => JsonConfig\JCTabularContent::class,
		],
	],

	// Language
	'wgLanguageCode' => [
		'default' => 'en',
	],
	'wgUseXssLanguage' => [
		'beta' => true,
	],

	// Mail
	'wgEnableEmail' => [
		'default' => true,
	],
	'wgSMTP' => [
		'default' => [
			'host' => 'us1.workspace.org',
      		'port' => 465,
      		'username' => 'noreply@wikivy.com',
      		'password' => "$wvMailPassword",
      		'IDHost' => 'wikivy.com',
      		'auth' => true,
		]
	],
	'wgEnotifWatchlist' => [
		'default' => true,
	],
	'wgUserEmailUseReplyTo' => [
		'default' => true,
	],
	'wgEmailConfirmToEdit' => [
		'default' => false,
	],
	'wgEmergencyContact' => [
		'default' => 'noreply@wikivy.com',
	],
	'wgAllowHTMLEmail' => [
		'default' => true,
	],
	'wgEnableSpecialMute' => [
		'default' => true,
	],
	'wgEnableUserEmailMuteList' => [
		'default' => true,
	],

	// ManageWiki
	'wgManageWikiCacheDirectory' => [
		'default' => '/srv/mediawiki/cache',
	],
	'wgManageWikiExtensionsDefault' => [
		// WARNING: When adding a new extension here, please check whether there are any SQL files that need to be run
		// during installation! The installation steps defined in ManageWikiExtensions will not be executed here.
		// Instead, the relevant SQL files should be added to $wgCreateWikiSQLFiles (see also T14385 and T14400).
		'default' => [
			'categorytree',
			'cite',
			'citethispage',
			'codeeditor',
			'codemirror',
			'globaluserpage',
			'minervaneue',
			'mobilefrontend',
			'portableinfobox',
			'purge',
			'syntaxhighlight_geshi',
			'templatesandbox',
			'templatestyles',
			'textextracts',
			'thanks',
			'urlshortener',
			'wikiseo',
		],
	],
	'wgManageWikiForceSidebarLinks' => [
		'default' => false,
	],
	'wgManageWikiHandledUnknownContentModels' => [
		// Only add content models here that is not possible to get working on new wikis.
		// Content models that are possible should be setup when doing imports etc...
		// to avoid potential content model mismatch issues.
		'default' => [
			// Flow is being removed and no longer enabled no new wikis
			'flow-board',
			// Interactivemap is a Fandom extension and the compatibility
			// mode in DataMaps does not work.
			'interactivemap',
		],
	],
	'wgManageWikiHelpUrl' => [
		'default' => '//meta.wikivy.com/wiki/Special:MyLanguage/ManageWiki',
	],
	'wgManageWikiModulesEnabled' => [
		'default' => [
			'core' => true,
			'extensions' => true,
			'namespaces' => true,
			'permissions' => true,
			'settings' => true,
		],
	],
	'wgManageWikiPermissionsAdditionalAddGroups' => [
		'default' => [],
		'metawiki' => [
			'techteam' => [
				'techteam',
			],
			'trustandsafety' => [
				'trustandsafety',
			],
		],
	],
	'wgManageWikiPermissionsAdditionalRemoveGroups' => [
		'default' => [],
		'metawiki' => [
			'techteam' => [
				'techteam',
			],
			'trustandsafety' => [
				'trustandsafety',
			],
		],
	],
	'wgManageWikiPermissionsAdditionalRights' => [
		'default' => [
			'*' => [
				'autocreateaccount' => true,
				'read' => true,
				'oathauth-enable' => true,
				'viewmyprivateinfo' => true,
				'editmyoptions' => true,
				'editmyprivateinfo' => true,
				'editmywatchlist' => true,
				'reportincident' => true,
			],
			'checkuser' => [
				'checkuser' => true,
				'checkuser-log' => true,
				'abusefilter-privatedetails' => true,
				'abusefilter-privatedetails-log' => true,
			],
			'suppress' => [
				'abusefilter-hidden-log' => true,
				'abusefilter-hide-log' => true,
				'browsearchive' => true,
				'deletedhistory' => true,
				'deletedtext' => true,
				'deletelogentry' => true,
				'deleterevision' => true,
				'hideuser' => true,
				'suppressionlog' => true,
				'suppressrevision' => true,
				'viewsuppressed' => true,
			],
			'steward' => [
				'userrights' => true,
			],
			'user' => [
				'mwoauthmanagemygrants' => true,
				'sendemail' => false,
				'user' => true,
			],
		],
		'+metawiki' => [
			'checkuser' => [
				'abusefilter-privatedetails' => true,
				'abusefilter-privatedetails-log' => true,
				'checkuser' => true,
				'checkuser-log' => true,
				'securepoll-view-voter-pii' => true,
			],
			'confirmed' => [
				'mwoauthproposeconsumer' => true,
				'mwoauthupdateownconsumer' => true,
			],
			'electionadmin' => [
				'securepoll-create-poll' => true,
				'securepoll-edit-poll' => true,
			],
			'global-renamer' => [
				'centralauth-rename' => true,
			],
			'global-admin' => [
				'abusefilter-modify-global' => true,
				'centralauth-lock' => true,
				'centralauth-rename' => true,
				'globalblock' => true,
			],
			'proxybot' => [
				'globalblock' => true,
				'centralauth-lock' => true,
			],
			'steward' => [
				'abusefilter-modify-global' => true,
				'centralauth-lock' => true,
				'centralauth-suppress' => true,
				'centralauth-rename' => true,
				'createwiki' => true,
				'createwiki-deleterequest' => true,
				'sendemail' => true,
				'globalblock' => true,
				'handle-import-request-interwiki' => true,
				'handle-import-requests' => true,
				'managewiki-core' => true,
				'managewiki-extensions' => true,
				'managewiki-namespaces' => true,
				'managewiki-permissions' => true,
				'managewiki-settings' => true,
				'managewiki-restricted' => true,
				'noratelimit' => true,
				'oathauth-verify-user' => true,
				'userrights' => true,
				'userrights-interwiki' => true,
				'globalgroupmembership' => true,
				'globalgrouppermissions' => true,
				'view-private-import-requests' => true,
			],
			'techteam' => [
				'sendemail' => true,
				'globalgroupmembership' => true,
				'globalgrouppermissions' => true,
				'handle-custom-domain-requests' => true,
				'handle-import-request-interwiki' => true,
				'handle-import-requests' => true,
				'oathauth-verify-user' => true,
				'oathauth-disable-for-user' => true,
				'view-private-import-requests' => true,
			],
			'suppress' => [
				'createwiki-suppressrequest' => true,
				'createwiki-suppressionlog' => true,
			],
			'trustandsafety' => [
				'userrights' => true,
				'sendemail' => true,
				'globalblock' => true,
				'globalgroupmembership' => true,
				'globalgrouppermissions' => true,
				'userrights-interwiki' => true,
				'centralauth-lock' => true,
				'centralauth-rename' => true,
				'handle-pii' => true,
				'oathauth-disable-for-user' => true,
				'oathauth-verify-user' => true,
				'view-private-import-requests' => true,
			],
			'sysop' => [
				'interwiki' => true,
			],
			'user' => [
				'request-custom-domain' => true,
				'request-import' => true,
				'requestwiki' => true,
			],
			'wiki-creator' => [
				'createwiki' => true,
				'createwiki-deleterequest' => true,
			],
		],
		'+metawikibeta' => [
			'autopatrolled' => [
				'autopatrolled' => true,
			],
			'confirmed' => [
				'mwoauthproposeconsumer' => true,
				'mwoauthupdateownconsumer' => true,
			],
			'global-renamer' => [
				'centralauth-rename' => true,
			],
			'global-admin' => [
				'abusefilter-modify-global' => true,
				'centralauth-lock' => true,
				'globalblock' => true,
			],
			'proxybot' => [
				'globalblock' => true,
				'centralauth-lock' => true,
			],
			'requestwikiblocked' => [
				'read' => true,
			],
			'steward' => [
				'abusefilter-modify-global' => true,
				'centralauth-lock' => true,
				'centralauth-suppress' => true,
				'centralauth-rename' => true,
				'createwiki' => true,
				'sendemail' => true,
				'globalblock' => true,
				'handle-import-request-interwiki' => true,
				'handle-import-requests' => true,
				'managewiki-core' => true,
				'managewiki-extensions' => true,
				'managewiki-namespaces' => true,
				'managewiki-permissions' => true,
				'managewiki-settings' => true,
				'managewiki-restricted' => true,
				'noratelimit' => true,
				'userrights' => true,
				'userrights-interwiki' => true,
				'globalgroupmembership' => true,
				'globalgrouppermissions' => true,
				'view-private-import-requests' => true,
			],
			'techteam' => [
				'sendemail' => true,
				'globalgroupmembership' => true,
				'globalgrouppermissions' => true,
				'handle-import-request-interwiki' => true,
				'handle-import-requests' => true,
				'oathauth-verify-user' => true,
				'oathauth-disable-for-user' => true,
				'view-private-import-requests' => true,
			],
			'trustandsafety' => [
				'sendemail' => true,
				'userrights' => true,
				'globalblock' => true,
				'globalgroupmembership' => true,
				'globalgrouppermissions' => true,
				'userrights-interwiki' => true,
				'centralauth-lock' => true,
				'centralauth-rename' => true,
				'handle-pii' => true,
				'oathauth-disable-for-user' => true,
				'oathauth-verify-user' => true,
				'view-private-import-requests' => true,
			],
			'user' => [
				'request-custom-domain' => true,
				'request-import' => true,
				'requestwiki' => true,
			],
			'wiki-creator' => [
				'createwiki' => true,
			],
		],
		'+ext-Flow' => [
			'suppress' => [
				'flow-suppress' => true,
			],
		],
	],
	'wgManageWikiPermissionsDefaultPrivateGroup' => [
		'default' => 'member',
	],
	'wgManageWikiPermissionsDisallowedGroups' => [
		'default' => [
			'checkuser',
			'checkuser-temporary-account-viewer',
			'smwadministrator',
			'oversight',
			'steward',
			'staff',
			'suppress',
			'temporary-account-viewer',
			'techteam',
			'trustandsafety',
		],
		'+metawiki' => [
			'electionadmin',
		],
	],
	'wgManageWikiPermissionsDisallowedRights' => [
		'default' => [
			'any' => [
				'abusefilter-hide-log',
				'abusefilter-hidden-log',
				'abusefilter-modify-global',
				'abusefilter-private',
				'abusefilter-private-log',
				'abusefilter-privatedetails',
				'abusefilter-privatedetails-log',
				'aft-oversighter',
				'autocreateaccount',
				'bigdelete',
				'blockemail',
				'centralauth-createlocal',
				'centralauth-lock',
				'centralauth-suppress',
				'centralauth-rename',
				'centralauth-unmerge',
				'checkuser',
				'checkuser-log',
				'checkuser-temporary-account',
				'checkuser-temporary-account-no-preference',
				'checkuser-temporary-account-log',
				'checkuser-temporary-account-auto-reveal',
				'createwiki',
				'createwiki-deleterequest',
				'createwiki-suppressionlog',
				'createwiki-suppressrequest',
				'editincidents',
				'editothersprofiles-private',
				'sendemail',
				'flow-suppress',
				'generate-random-hash',
				'globalblock',
				'globalblock-exempt',
				'globalgroupmembership',
				'globalgrouppermissions',
				'handle-custom-domain-requests',
				'handle-import-request-interwiki',
				'handle-import-requests',
				'handle-pii',
				'hideuser',
				'investigate',
				'ipinfo',
				'ipinfo-view-basic',
				'ipinfo-view-full',
				'ipinfo-view-log',
				'managewiki-restricted',
				'managewiki-editdefault',
				'moderation-checkuser',
				'mwoauthmanageconsumer',
				'mwoauthmanagemygrants',
				'mwoauthsuppress',
				'mwoauthviewprivate',
				'mwoauthviewsuppressed',
				'oathauth-api-all',
				'oathauth-enable',
				'oathauth-disable-for-user',
				'oathauth-verify-user',
				'oathauth-view-log',
				'renameuser',
				'renameuser-global',
				'reportincident',
				'request-custom-domain',
				'request-import',
				'requestwiki',
				'siteadmin',
				'searchdigest-admin',
				'securepoll-view-voter-pii',
				'smw-admin',
				'smw-patternedit',
				'smw-viewjobqueuewatchlist',
				'stopforumspam',
				'suppressionlog',
				'suppressrevision',
				'themedesigner',
				'titleblacklistlog',
				'updatepoints',
				'userrights',
				'userrights-interwiki',
				'view-private-import-requests',
				'viewglobalprivatefiles',
				'viewpmlog',
				'viewsuppressed',
				'campaignevents-organize-events',
			],
			'user' => [
				'autoconfirmed',
				'noratelimit',
				'skipcaptcha',
				'managewiki-core',
				'managewiki-extensions',
				'managewiki-namespaces',
				'managewiki-permissions',
				'managewiki-settings',
				'globalblock-whitelist',
				'ipblock-exempt',
				'interwiki',
			],
			'*' => [
				'read',
				'skipcaptcha',
				'torunblocked',
				'centralauth-merge',
				'generate-dump',
				'editsitecss',
				'editsitejson',
				'editsitejs',
				'editusercss',
				'edituserjson',
				'edituserjs',
				'editmyoptions',
				'editmyprivateinfo',
				'editmywatchlist',
				'globalblock-whitelist',
				'interwiki',
				'ipblock-exempt',
				'viewmyprivateinfo',
				'viewmywatchlist',
				'managewiki-core',
				'managewiki-extensions',
				'managewiki-namespaces',
				'managewiki-permissions',
				'managewiki-settings',
				'noratelimit',
				'autoconfirmed',
			],
		],
	],
	'wgManageWikiUseCustomDomains' => [
		'default' => true,
	],

	// OAuth
	'wgMWOAuthCentralWiki' => [
		'default' => 'metawiki',
		'ldapwikiwiki' => false,
		'beta' => 'metawikibeta',
	],
	'wgOAuth2GrantExpirationInterval' => [
		'default' => 'PT4H',
	],
	'wgOAuth2RefreshTokenTTL' => [
		'default' => 'P365D',
	],
	'wgMWOAuthSharedUserSource' => [
		'default' => 'CentralAuth',
	],
	'wgMWOAuthSecureTokenTransfer' => [
		'default' => true,
	],
	'wgOAuth2PublicKey' => [
		'default' => '/srv/mediawiki/config/OAuth2.key.pub',
	],
	'wgOAuth2PrivateKey' => [
		'default' => '/srv/mediawiki/config/OAuth2.key',
	],

	// Permissions
	'wgGroupsAddToSelf' => [
		'default' => [],
	],
	'wgGroupsRemoveFromSelf' => [
		'default' => [],
	],
	'+wgRevokePermissions' => [
		'default' => [],
		'+metawiki' => [
			'requestwikiblocked' => [
				'requestwiki' => true,
			],
		],
	],
	'wgImplicitGroups' => [
		'default' => [
			'*',
			'user',
			'autoconfirmed'
		],
	],

	// RemovePII
	'wgRemovePIIAllowedWikis' => [
		'default' => [
			'metawiki',
			'metawikibeta',
		],
	],
	'wgRemovePIIAutoPrefix' => [
		'default' => 'WikivyGDPR_',
	],
	'wgRemovePIIDPAValidationEndpoint' => [
		'default' => 'https://reports.wikivy.com/api/dpa/{dpa_id}/{username}',
	],
	'wgRemovePIIHashPrefixOptions' => [
		'default' => [
			'Trust and Safety' => 'WikivyGDPR_',
			'Stewards' => 'Vanished user ',
		],
	],
	'wgRemovePIIHashPrefix' => [
		'default' => 'WikivyGDPR_',
	],

	// RequestCustomDomain
	'wgRequestCustomDomainDatabaseSuffix' => [
		'default' => 'wiki',
		'beta' => 'wikibeta',
	],
	'wgRequestCustomDomainDisallowedDomains' => [
		'default' => [
			'wikivy.com',
			'wikivy.wiki',
		],
	],
	'wgRequestCustomDomainSubdomain' => [
		'default' => 'wikivy.com',
		'beta' => 'wikivy.dev',
	],
	'wgRequestCustomDomainUsersNotifiedOnAllRequests' => [
		'default' => [
			'Spacetrain31'
		],
	],

	// Resources
	'wgResourceLoaderMaxQueryLength' => [
		'default' => 5000,
	],

	// Rights
	'+wgAvailableRights' => [
		'default' => [],
		'metawiki' => [
			'editautopatrolprotected',
		],
		'+ext-SocialProfile' => [
			'updatepoints',
		],
	],

	// RightFunctions
	'wgRightFunctionsUserGroups' => [
		'default' => [
			'*',
			'user',
			'autoconfirmed',
			'sysop',
			'bureaucrat',
		],
	],


	// Server
	'wgArticlePath' => [
		'default' => '/wiki/$1',
	],
	'wgDisableOutputCompression' => [
		'default' => true,
	],
	'wgShowHostnames' => [
		'default' => true,
	],
	'wgThumbPath' => [
		'default' => '/w/thumb_handler.php'
	],
	'wgUsePathInfo' => [
		'default' => true,
	],

	// Styling
	'wgAllowUserCss' => [
		'default' => true,
	],
	'wgAllowUserJs' => [
		'default' => true,
	],
	'wgAppleTouchIcon' => [
		'default' => '/apple-touch-icon.png',
	],
	'wgCentralAuthLoginIcon' => [
		'default' => '/srv/mediawiki/favicons/default.ico',
	],
	'wgDefaultSkin' => [
		'default' => 'vector-2022',
	],
	'wgFallbackSkin' => [
		'default' => 'vector-2022',
	],
	'wgFavicon' => [
		'default' => '/favicon.ico',
	],
	'wgLogo' => [
		'default' => "https://$wmgUploadHostname/metawiki/c/c9/Wikivy_Logo.svg",
	],
	'wgIcon' => [
		'default' => false,
	],
	'wgWordmark' => [
		'default' => false,
	],
	'wgWordmarkHeight' => [
		'default' => 18,
	],
	'wgWordmarkWidth' => [
		'default' => 116,
	],
	'wgMaxTocLevel' => [
		'default' => 999,
	],

	// Theme
	'wgDefaultTheme' => [
		'default' => '',
	],

	// Uploads
	'wmgEnableSharedUploads' => [
		'default' => false,
	],
	'wmgSharedUploadBaseUrl' => [
		'default' => false,
	],
	'wmgSharedUploadDBname' => [
		'default' => false,
	],
	'wmgSharedUploadClientDBname' => [
		'default' => false,
	],

	// Varnish
	'wgUseCdn' => [
		'default' => true,
	],
	'wgCdnServersNoPurge' => [
		'default' => [
			// localhost is a must!
			'127.0.0.1',
			// CloudFlare IPs - https://www.cloudflare.com/ips/
			// Sept. 2023 edition; make sure to keep updated or bad things happen!
			'103.21.244.0/22',
			'103.22.200.0/22',
			'103.31.4.0/22',
			'104.16.0.0/13',
			'104.24.0.0/14',
			'108.162.192.0/18',
			'131.0.72.0/22',
			'141.101.64.0/18',
			'162.158.0.0/15',
			'172.64.0.0/13',
			'173.245.48.0/20',
			'188.114.96.0/20',
			'190.93.240.0/20',
			'197.234.240.0/22',
			'198.41.128.0/17',
			'2400:cb00::/32',
			'2606:4700::/32',
			'2803:f800::/32',
			'2405:b500::/32',
			'2405:8100::/32',
			'2a06:98c0::/29',
			'2c0f:f248::/32',
		],
	],
	'wgCdnMaxAge' => [
		'default' => 432000,
	],

	// Wikibase
	'wmgAllowEntityImport' => [
		'default' => false,
	],
	'wmgCanonicalUriProperty' => [
		'default' => false,
	],
	'wmgEnableEntitySearchUI' => [
		'default' => false,
	],
	'wmgFederatedPropertiesEnabled' => [
		'default' => false,
	],
	'wmgFormatterUrlProperty' => [
		'default' => false,
	],
	'wmgWikibaseRepoDatabase' => [
		'default' => $wi->dbname
	],
	'wmgWikibaseRepoUrl' => [
		'default' => 'https://wikidata.org'
	],
	'wmgWikibaseItemNamespaceID' => [
		'default' => 0
	],
	'wmgWikibasePropertyNamespaceID' => [
		'default' => 120
	],
	'wmgWikibaseRepoItemNamespaceID' => [
		'default' => 860
	],
	'wmgWikibaseRepoPropertyNamespaceID' => [
		'default' => 862
	],

	// CreateWiki Defined Special Variables
	'cwClosed' => [
		'default' => false,
	],
	'cwDeleted' => [
		'default' => false,
	],
	'cwExperimental' => [
		'default' => false,
	],
	'cwInactive' => [
		'default' => false,
	],
	'cwPrivate' => [
		'default' => false,
	],

	// Meta namespace
	'wgMetaNamespace' => [
		'default' => str_replace( [ ' ', ':' ], '_', $wi->sitename ),
	],
	'wgMetaNamespaceTalk' => [
		'default' => str_replace( [ ' ', ':' ], '_', "{$wi->sitename}_talk" ),
	],
];

// ManageWiki settings
require_once __DIR__ . '/ManageWikiExtensions.php';

$globals = WikivyFunctions::getConfigGlobals();

// phpcs:ignore MediaWiki.Usage.ForbiddenFunctions.extract
extract($globals);

if ( $wmgSharedDomainPathPrefix ) {
	$wgArticlePath = $wmgSharedDomainPathPrefix . $wgArticlePath;
	$wgServer = '//' . $wi->getSharedDomain();
}

$wi->loadExtensions();

require_once __DIR__ . '/ManageWikiNamespaces.php';
require_once __DIR__ . '/ManageWikiSettings.php';

$wgUploadPath = "//$wmgUploadHostname/$wgDBname";
//$wgUploadDirectory = false;
$wgUploadDirectory = "/srv/static/$wgDBname";

if ($cwPrivate) {
	$wgUploadDirectory = "/srv/static/private/$wgDBname";
}

// These are not loaded by mergeMessageFileList.php due to not being on ExtensionRegistry
$wgMessagesDirs['SocialProfile'] = $IP . '/extensions/SocialProfile/i18n';
$wgExtensionMessagesFiles['SocialProfileAlias'] = $IP . '/extensions/SocialProfile/SocialProfile.alias.php';
$wgMessagesDirs['SocialProfileUserProfile'] = $IP . '/extensions/SocialProfile/UserProfile/i18n';
$wgExtensionMessagesFiles['SocialProfileNamespaces'] = $IP . '/extensions/SocialProfile/SocialProfile.namespaces.php';
$wgExtensionMessagesFiles['AvatarMagic'] = $IP . '/extensions/SocialProfile/UserProfile/includes/avatar/Avatar.i18n.magic.php';

$wgLocalisationCacheConf['storeClass'] = LCStoreStaticArray::class;
$wgLocalisationCacheConf['storeDirectory'] = '/srv/mediawiki/cache/' . $wi->version . '/l10n';
$wgLocalisationCacheConf['manualRecache'] = true;

if ( !file_exists( '/srv/mediawiki/cache/' . $wi->version . '/l10n/en.l10n.php' ) ) {
	$wgLocalisationCacheConf['manualRecache'] = false;
}


// Include other configuration files
require_once '/srv/mediawiki/config/Database.php';

if ( $wi->missing ) {
	require_once '/srv/mediawiki/ErrorPages/MissingWiki.php';
}

if ( $cwDeleted ) {
	if ( MW_ENTRY_POINT === 'cli' ) {
		wfHandleDeletedWiki();
	} else {
		$wgHooks['ApiBeforeMain'][] = 'wfHandleDeletedWiki';
		$wgHooks['BeforeInitialize'][] = 'wfHandleDeletedWiki';
	}
}

function wfHandleDeletedWiki() {
	require_once '/srv/mediawiki/ErrorPages/DeletedWiki.php';
}

// Define last to avoid all dependencies
require_once '/srv/mediawiki/config/GlobalSettings.php';
require_once '/srv/mediawiki/config/LocalWiki.php';

// Configure late to ensure $wgDBname is set properly
$wgCargoDBname = $wgDBname . 'cargo';

// Don't need a global here
unset( $wi );
