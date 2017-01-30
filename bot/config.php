<?php
	$api = 'https://api.telegram.org/bot';
	$bot = ''; //TOKEN BOT

	$redis = conectarRedis();

	define('SUDOS', array(
		'0' => '000000', //SUDO ID
		'1' => '111111'
	));

	define('API_BOT', $api . $bot);
	define('CONTEXTO', stream_context_create(array('http' => array('header'=>'Connection: close\r\n'))));
	define('RAIZ', system('pwd') . '/');
	define('VERSAO', '4.0.0 (SGF)');
