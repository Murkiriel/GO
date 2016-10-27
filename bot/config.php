<?php
<<<<<<< HEAD
	date_default_timezone_set('America/Sao_Paulo'); //ZONE
=======
	date_default_timezone_set('America/Sao_Paulo');
>>>>>>> origin/master

	$api = 'https://api.telegram.org/bot';
	$bot = ''; //TOKEN BOT

	define('SUDOS', array(
<<<<<<< HEAD
		'0' => '9876543210' //SUDO ID
=======
		'0' => '123456' //SUDO ID
>>>>>>> origin/master
	));

	define('API_BOT',	$api . $bot);
	define('CONTEXTO', stream_context_create(array('http' => array('header'=>'Connection: close\r\n'))));
	define('RAIZ', '/home/$USER/GO/'); //ROOT BOT
	define('VERSAO', '4.0.0 (SGF)');
