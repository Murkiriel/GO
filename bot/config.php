<?php
	$api = 'https://api.telegram.org/bot';
	$bot = ''; //TOKEN BOT

	define('SUDOS', [
		0 => '000000', //SUDO ID
		1 => '111111'
	]);

	define('API_BOT', $api . $bot);
	define('RAIZ', system('pwd') . '/');
	define('VERSAO', '4.0.0 (SGF)');

	define('YT_KEY', ''); //YOUTUBE KEY
