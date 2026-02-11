<?php

use MediaWiki\Actions\ActionEntryPoint;
use MediaWiki\Context\RequestContext;
use MediaWiki\FileRepo\ForeignDBViaLBRepo;
use MediaWiki\Html\Html;
use MediaWiki\Language\LanguageCode;
use MediaWiki\MediaWikiServices;
use MediaWiki\Output\OutputPage;
use MediaWiki\Parser\Parser;
use MediaWiki\Parser\Sanitizer;
use MediaWiki\Request\WebRequest;
use MediaWiki\Skin\Skin;
use MediaWiki\SpecialPage\DisabledSpecialPage;
use MediaWiki\Title\Title;
use MediaWiki\User\User;

// Per-wiki settings that are incompatible with LocalSettings.php
switch ( $wi->dbname ) {
	case 'commonswiki':
		$wgJsonConfigs['Map.JsonConfig']['store'] = true;
		$wgJsonConfigs['Tabular.JsonConfig']['store'] = true;

		break;
	case 'metawiki':
		wfLoadExtensions([
			'ContactPage',
			'GlobalWatchlist',
			'ReportIncident',
			'RequestCustomDomain',
			'SecurePoll',
		]);

		$wgContactConfig = [
			'default' => [
				'RecipientUser' => null,
				'SenderName' => 'Contact Form on ' . $wgSitename,
				'RequireDetails' => false,
				'IncludeIP' => false,
				'MustBeLoggedIn' => false,
			],
		];

		break;
}
