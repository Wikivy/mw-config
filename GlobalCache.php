<?php

use MediaWiki\JobQueue\JobQueueRedis;
use Wikimedia\ObjectCache\MemcachedPeclBagOStuff;
use Wikimedia\ObjectCache\MultiWriteBagOStuff;
use Wikimedia\ObjectCache\RedisBagOStuff;

$wgMemCachedServers = [
	'mwtask01.wikivy.com:11211'
];
$wgMemCachedPersistent = false;

$wgWikivyMagicMemcachedServers = [
	[ 'mwtask01.wikivy.com', 11211 ]
];

$wgMainCacheType = 'redis';
$wgMessageCacheType = 'redis';

$wgParserCacheType = 'redis';

$wgLanguageConverterCacheType = CACHE_ACCEL;

$wgQueryCacheLimit = 5000;

// 15 days
$wgParserCacheExpireTime = 86400 * 15;

// 10 days
$wgDiscussionToolsTalkPageParserCacheExpiry = 86400 * 10;

// 3 days
$wgRevisionCacheExpiry = 86400 * 3;

// 1 day
$wgObjectCacheSessionExpiry = 86400;

// 7 days
$wgDLPMaxCacheTime = 604800;

$wgDLPQueryCacheTime = 120;
$wgDplSettings['queryCacheTime'] = 120;

$wgSearchSuggestCacheExpiry = 10800;

$wgEnableSidebarCache = true;

$wgUseLocalMessageCache = true;
$wgInvalidateCacheOnLocalSettingsChange = false;

$wgResourceLoaderUseObjectCacheForDeps = true;

$wgCdnMatchParameterOrder = false;

if ( PHP_SAPI === 'cli' ) {
	// APC not available in CLI mode
	$wgLanguageConverterCacheType = CACHE_NONE;
}

$wgUseGzip = true;
