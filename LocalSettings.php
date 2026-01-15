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

	'wmgCentralAuthAutoLoginWikis' => [
		'default' => [
			'.wikivy.com' => 'metawiki'
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
				"$IP/sql/postgres/tables-generated.sql",
				"$IP/extensions/AbuseFilter/db_patches/postgres/tables-generated.sql",
				"$IP/extensions/AntiSpoof/sql/postgres/tables-generated.sql",
				"$IP/extensions/BetaFeatures/sql/tables-generated.sql",
				"$IP/extensions/CheckUser/schema/postgres/tables-generated.sql",
				"$IP/extensions/DataDump/sql/data_dump.sql",
				"$IP/extensions/Echo/sql/postgres/tables-generated.sql",
				"$IP/extensions/GlobalBlocking/sql/postgres/tables-generated-global_block_whitelist.sql",
				"$IP/extensions/Linter/sql/postgres/tables-generated.sql",
				"$IP/extensions/MediaModeration/schema/postgres/tables-generated.sql",
				"$IP/extensions/OAuth/schema/postgres/tables-generated.sql",
				//"$IP/extensions/RottenLinks/sql/rottenlinks.sql",
				"$IP/extensions/UrlShortener/schemas/postgres/tables-generated.sql",
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
		'wgCreateWikiContainers' => [
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
		],
		'wgCreateWikiUseJobQueue' => [
			'default' => true,
		],
		'wgRequestWikiMinimumLength' => [
			'default' => 350,
		],
		'wgRequestWikiConfirmAgreement' => [
			'default' => true,
		],
	],
];
