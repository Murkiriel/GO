<?php
	date_default_timezone_set('America/Sao_Paulo');			//TIME ZONE

	$horaCache = date('H');

	$api = 'https://api.telegram.org/bot';
	$bot = '';			//TOKEN BOT

	define('SUDOS', array(
		'0' => '000000',			//SUDO ID
		'1' => '111111'
	));

	define('API_BOT',	$api . $bot);
	define('CONTEXTO', stream_context_create(array('http' => array('header'=>'Connection: close\r\n'))));
	define('RAIZ', system('pwd') . '/');
	define('VERSAO', '4.0.0 (SGF)');

	define('CACHE_PASTA', '/tmp/381ed5f/');

	if(!file_exists(CACHE_PASTA)){
		mkdir(CACHE_PASTA);
	}
