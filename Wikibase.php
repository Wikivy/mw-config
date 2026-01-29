<?php

// Documentation for Wikibase: https://www.mediawiki.org/wiki/Wikibase/Installation/Advanced_configuration#Configuration

// You should only need to set $wgWBClientSettings['repoUrl'], $wgWBClientSettings['repoDatabase'] and $wgWBClientSettings['changesDatabase']
// on the wiki.

$entitySources = [
	'local' => [
		'entityNamespaces' => [
			'item' => $wmgWikibaseRepoItemNamespaceID,
			'property' => $wmgWikibaseRepoPropertyNamespaceID,
		],
		'repoDatabase' => $wmgWikibaseRepoDatabase,
		'baseUri' => $wmgWikibaseRepoUrl . '/entity/',
		'interwikiPrefix' => '',
		'rdfNodeNamespacePrefix' => 'wd',
		'rdfPredicateNamespacePrefix' => '',
		'type' => 'db'
	],
];

$wgWBClientSettings['tmpUnconnectedPagePagePropMigrationStage'] = MIGRATION_NEW;

if ( $wi->isExtensionActive( 'WikibaseLexeme' ) ) {
	$entitySources['local']['entityNamespaces']['lexeme'] = 146;
	$wgWBRepoSettings['entityNamespaces']['lexeme'] = 146;
}

$wgWBRepoSettings['defaultEntityNamespaces'] = false;
$wgWBRepoSettings['entitySources'] = $entitySources;
$wgWBRepoSettings['localEntitySourceName'] = 'local';
$wgWBRepoSettings['entityNamespaces']['item'] = $wmgWikibaseRepoItemNamespaceID;
$wgWBRepoSettings['entityNamespaces']['property'] = $wmgWikibaseRepoPropertyNamespaceID;
$wgWBRepoSettings['allowEntityImport'] = $wmgAllowEntityImport;
$wgWBRepoSettings['enableEntitySearchUI'] = $wmgEnableEntitySearchUI;
$wgWBRepoSettings['federatedPropertiesEnabled'] = $wmgFederatedPropertiesEnabled;
$wgWBRepoSettings['formatterUrlProperty'] = $wmgFormatterUrlProperty ?: null;
$wgWBRepoSettings['canonicalUriProperty'] = $wmgCanonicalUriProperty ?: null;

$wgWBRepoSettings['siteGlobalID'] = $wgDBname;

$wgWBRepoSettings['siteLinkGroups'] = [
	'wikivy'
];

$wgWBRepoSettings['specialSiteLinkGroups'] = [];

$wgWBClientSettings['entitySources'] = $entitySources;
$wgWBClientSettings['itemAndPropertySourceName'] = 'local';
$wgWBClientSettings['repoUrl'] = $wmgWikibaseRepoUrl;
$wgWBClientSettings['repoDatabase'] = $wmgWikibaseRepoDatabase;
$wgWBClientSettings['changesDatabase'] = $wmgWikibaseRepoDatabase;
$wgWBClientSettings['repositories'] = [
	'' => [
		'repoDatabase' => $wmgWikibaseRepoDatabase,
		'baseUri' => $wmgWikibaseRepoUrl . '/entity/',
		'entityNamespaces' => [
			'item' => $wmgWikibaseItemNamespaceID,
			'property' => $wmgWikibasePropertyNamespaceID
		],
		'prefixMapping' => [
			'' => ''
		]
	]
];

$wgWBClientSettings['siteGlobalID'] = $wgDBname;
$wgWBClientSettings['repoScriptPath'] = '/w';
$wgWBClientSettings['repoArticlePath'] = '/wiki/$1';
$wgWBClientSettings['siteGroup'] = 'wikivy';
$wgWBClientSettings['repoNamespaces'] = [
	'wikibase-item' => 'Item',
	'wikibase-property' => 'Property'
];

$wgWBClientSettings['siteLinksGroups'] = [
	'wikivy'
];

$wgWBClientSettings['purgeCacheBatchSize'] = 100;
$wgWBClientSettings['recentChangesBatchSize'] = 100;

$wgMFUseWikibase = true;

// Per-wiki

// don't need these to be a global
unset( $entitySources );
