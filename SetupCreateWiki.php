<?php
# Protect against web entry
if ( !defined( 'MEDIAWIKI' ) ) {
	exit;
}

use MediaWiki\MediaWikiServices;
use Miraheze\CreateWiki\WikiInitialize;
use Wikimedia\Rdbms\DBQueryError;

$wgWikimediaJenkinsCI = true;

define('CW_DB', 'wvglobal');
define('WV_MEDIAWIKI_VERSION', '1.45');
define('WV_INSTALL_PATH', '/srv/mediawiki');

$wvMediaWikiVersion = WV_MEDIAWIKI_VERSION;

require_once "/srv/mediawiki/$wvMediaWikiVersion/extensions/CreateWiki/includes/WikiInitialize.php";

$wgHooks['MediaWikiServices'][] = 'insertWiki';

function insertWiki( MediaWikiServices $services ) {
	wfLoadConfiguration();
	try {
		if ( getenv( 'WIKI_CREATION_SQL_EXECUTED' ) ) {
			return;
		}

		$db = wfInitDBConnection();

		$db->selectDomain( 'wvglobal' );
		$db->newInsertQueryBuilder()
			->insertInto( 'cw_wikis' )
			->ignore()
			->row( [
				'wiki_dbname' => 'testwiki',
				'wiki_dbcluster' => 'c1',
				'wiki_sitename' => 'TestWiki',
				'wiki_language' => 'en',
				'wiki_private' => (int)0,
				'wiki_creation' => $db->timestamp(),
				'wiki_category' => 'uncategorised',
				'wiki_closed' => (int)0,
				'wiki_deleted' => (int)0,
				'wiki_locked' => (int)0,
				'wiki_inactive' => (int)0,
				'wiki_inactive_exempt' => (int)0,
				'wiki_url' => 'https://test.wikivy.com', # CHANGE THIS
			] )
			->caller( __METHOD__ )
			->execute();

		putenv( 'WIKI_CREATION_SQL_EXECUTED=true' );
	} catch ( DBQueryError $e ) {
		return;
	}
}

function wfLoadConfiguration() {
	global $wgCreateWikiGlobalWiki, $wgCreateWikiDatabase,
		   $wgCreateWikiCacheDirectory, $wgConf;

	$wgCreateWikiGlobalWiki = 'wvglobal';
	$wgCreateWikiDatabase = 'wvglobal';
	$wgCreateWikiCacheDirectory = WV_INSTALL_PATH . '/cache';

	$wi = new WikiInitialize();

	$wi->setVariables(
		WV_INSTALL_PATH . '/cache',
		[
			''
		],
		[
			'127.0.0.1' => ''
		]
	);

	$wi->config->settings += [
		'cwClosed' => [
			'default' => false,
		],
		'cwInactive' => [
			'default' => false,
		],
		'cwPrivate' => [
			'default' => false,
		],
		'cwExperimental' => [
			'default' => false,
		],
	];

	$wi->readCache();
	$wi->config->extractAllGlobals( $wi->dbname );
	$wgConf = $wi->config;
}

function wfInitDBConnection() {
	return MediaWikiServices::getInstance()->getDatabaseFactory()->create( 'postgres', [
		'host' => $GLOBALS['wgDBserver'],
		'user' => 'wikivy',
		'password' => '' # CHANGE THIS
	] );
}
