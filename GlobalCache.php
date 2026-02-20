<?php

use MediaWiki\JobQueue\JobQueueRedis;
use Wikimedia\ObjectCache\MemcachedPeclBagOStuff;
use Wikimedia\ObjectCache\MultiWriteBagOStuff;
use Wikimedia\ObjectCache\RedisBagOStuff;

$wgMemCachedServers = [
	'127.0.0.1:11211'
];
$wgMemCachedPersistent = false;

$wgWikivyMagicMemcachedServers = [
	[ '127.0.0.1', 11211 ]
];

$wgObjectCaches['redis'] = [
	'class' => RedisBagOStuff::class,
	'servers' => [ 'mwtask01.wikivy.com:6379' ],
	'password' => $wmgRedisPassword,
	'loggroup' => 'redis',
	'reportDupes' => false,
	'persistent' => true,
];

$wgObjectCaches['redis-session'] = [
	'class' => RedisBagOStuff::class,
	'servers' => [ 'mwtask01.wikivy.com:6379' ],
	'password' => $wmgRedisPassword,
	'loggroup' => 'redis',
	'reportDupes' => false,
	'persistent' => true,
	'keyspace' => 'globalsession'
];

$wgSessionCacheType = 'redis-session';
$wgCentralAuthSessionCacheType = 'redis-session';
$wgEchoSeenTimeCacheType = 'redis-session';

$wgSessionName = $wgDBname . 'Session';

$wgMainCacheType = 'redis';
$wgMessageCacheType = 'redis';

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

$wgDPLAlwaysCacheResults = true;
$wgDPLQueryCacheTime = 120;

$wgSearchSuggestCacheExpiry = 10800;

// Disable sidebar cache for select wikis as needed here
$wgEnableSidebarCache = true;

$wgUseLocalMessageCache = true;
$wgInvalidateCacheOnLocalSettingsChange = false;

$wgCdnMatchParameterOrder = false;

$wgJobTypeConf['default'] = [
	'class' => JobQueueRedis::class,
	'redisServer' => 'mwtask01.wikivy.com:6379',
	'redisConfig' => [
		'connectTimeout' => 2,
		'password' => $wmgRedisPassword,
		'compression' => 'gzip',
	],
	'daemonized' => true,
];

if ( PHP_SAPI === 'cli' ) {
	// APC not available in CLI mode
	$wgLanguageConverterCacheType = CACHE_NONE;
}
