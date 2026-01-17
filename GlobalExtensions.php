<?php

wfLoadExtensions([
	'AbuseFilter',
	'AntiSpoof',
	'CentralNotice',
	'CheckUser',
	'CreateWiki',
	'CookieWarning',
	'ConfirmEdit',
	'ConfirmEdit/hCaptcha',
	'DataDump',
	'DiscordNotifications',
	'DismissableSiteNotice',
	'Echo',
	'EventBus',
	'EventLogging',
	'EventStreamConfig',
	'GlobalBlocking',
	'GlobalCssJs',
	'GlobalPreferences',
	'IPInfo',
	'LoginNotify',
	'ManageWiki',
	'Nuke',
	'OATHAuth',
	'OAuth',
	'ParserFunctions',
	'PortableInfobox',
	'RemovePII',
	'SpamBlacklist',
	'WikiDiscover',
	'WikiEditor',
	'WikivyMagic',
	'cldr'
]);

wfLoadExtension( 'Parsoid', "$IP/vendor/wikimedia/parsoid/extension.json" );
