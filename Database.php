<?php

use Wikimedia\Rdbms\LBFactoryMulti;
use Wikimedia\Rdbms\LoadMonitor;
use Wikimedia\Rdbms\LoadMonitorNull;

if ( strpos( wfHostname(), 'test' ) === 0 ) {
	// Wikivy database configuration
	$wgLBFactoryConf = [
		'class' => LBFactoryMulti::class,
		'secret' => $wgSecretKey,
		'sectionsByDB' => $wi->wikiDBClusters,
		'sectionLoads' => [
			'DEFAULT' => [
				'db01' => 0,
			],
			'c1' => [
				'db01' => 0,
			],
		],
		'serverTemplate' => [
			'dbname' => $wgDBname,
			'user' => $wgDBuser,
			'password' => $wgDBpassword,
			'type' => 'postgres',
			//'dbschema' => 'public',
			'flags' => DBO_DEFAULT | ( MW_ENTRY_POINT === 'cli' ? DBO_DEBUG : 0 ),
			'variables' => [
				// https://mariadb.com/docs/reference/mdb/system-variables/innodb_lock_wait_timeout
				//'innodb_lock_wait_timeout' => 15,
			],
		],
		'hostsByName' => [
			'db01' => 'db01.wikivy.com',
		],
		'externalLoads' => [
			'beta' => [
				/** where the metawikibeta database is located */
				'db01' => 0,
			],
		],
		'readOnlyBySection' => [
			// 'DEFAULT' => 'Maintenance is in progress. Please try again in a few minutes.',
			// 'c1' => 'Maintenance is in progress. Please try again in a few minutes.',
		],
	];
} else {
	// Production database configuration
	$wgLBFactoryConf = [
		'class' => LBFactoryMulti::class,
		'secret' => $wgSecretKey,
		'sectionsByDB' => $wi->wikiDBClusters,
		'sectionLoads' => [
			'DEFAULT' => [
				'db01' => 0,
			],
			'c1' => [
				'db01' => 0,
			]
		],
		'serverTemplate' => [
			'dbname' => $wgDBname,
			'user' => $wgDBuser,
			'password' => $wgDBpassword,
			'type' => 'postgres',
			//'dbschema' => 'public',
			'flags' => DBO_DEFAULT | ( MW_ENTRY_POINT === 'cli' ? DBO_DEBUG : 0 ),
			'variables' => [
				// https://mariadb.com/docs/reference/mdb/system-variables/innodb_lock_wait_timeout
				//'innodb_lock_wait_timeout' => 120,
			],
		],
		'hostsByName' => [
			'db01' => 'db01.wikivy.com',
		],
		'externalLoads' => [
			'echo' => [
				/** where the metawiki database is located */
				'db01' => 0,
			],
		],
		'readOnlyBySection' => [
			// 'DEFAULT' => 'Maintenance is in progress. Please try again in a few minutes.',
			// 'c1' => 'Maintenance is in progress. Please try again in a few minutes.',
			// 'c2' => 'Maintenance is in progress. Please try again in a few minutes.',
			// 'c3' => 'Maintenance is in progress. Please try again in a few minutes.',
			// 'c4' => 'Maintenance is in progress. Please try again in a few minutes.',
			// 's1' => 'Maintenance is in progress. Please try again in a few minutes.',
		],
	];
}

$wgLBFactoryConf['loadMonitor']['class'] = LoadMonitor::class;
// Disable LoadMonitor in CLI, it doesn't provide much value in CLI.
if ( PHP_SAPI === 'cli' ) {
	$wgLBFactoryConf['loadMonitor']['class'] = LoadMonitorNull::class;
}

$wgLBFactoryConf['loadMonitor']['maxConnCount'] = 350;

// Disallow web request database transactions that are slower than 10 seconds
$wgMaxUserDBWriteDuration = 10;

// Max execution time for expensive queries of special pages (in milliseconds)
$wgMaxExecutionTimeForExpensiveQueries = 30000;

$wgMiserMode = true;

$wgSQLMode = null;
