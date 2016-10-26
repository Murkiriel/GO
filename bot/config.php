<?php
	date_default_timezone_set('America/Sao_Paulo');

	$api = 'https://api.telegram.org/bot';
	$bot = ''; //TOKEN BOT

	define('SUDOS', array(
		'0' => '123456' //SUDO ID
	));

	define('API_BOT',	$api . $bot);
	define('CONTEXTO', stream_context_create(array('http' => array('header'=>'Connection: close\r\n'))));
	define('RAIZ', '/home/$USER/GO/'); //PASTA BOT
	define('VERSAO', '4.0.0 (SGF)');
