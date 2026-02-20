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

$wgObjectCaches['redis'] = [
	'class' => RedisBagOStuff::class,
	'servers' => [ 'mwtask01.wikivy.com:6379' ],
	'password' => $wmgRedisPassword,
	'loggroup' => 'redis',
	'reportDupes' => false,
];

$wgObjectCaches['redis-session'] = [
	'class' => RedisBagOStuff::class,
	'servers' => [ 'mwtask01.wikivy.com:6379' ],
	'password' => $wmgRedisPassword,
	'loggroup' => 'redis',
	'reportDupes' => false,
];

$wgSessionCacheType = 'redis-session';
$wgCentralAuthSessionCacheType = 'redis-session';
$wgEchoSeenTimeCacheType = 'redis-session';

$wgSessionName = $wgDBname . 'Session';

$wgMainCacheType = 'redis';
$wgMessageCacheType = 'redis';

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
