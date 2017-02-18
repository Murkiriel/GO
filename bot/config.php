<?php
	$api = 'https://api.telegram.org/bot';
	$bot = '156817358:AAG8ZpRni15oxG0MJoO2jLI3WhIYEWneUg0'; //TOKEN BOT

	define('SUDOS', array(
		'0' => '96438491', //SUDO ID
		'1' => '111111'
	));

	define('API_BOT', $api . $bot);
	define('CONTEXTO', stream_context_create(array('http' => array('header'=>'Connection: close\r\n'))));
	define('RAIZ', system('pwd') . '/');
	define('VERSAO', '4.0.0 (SGF)');
