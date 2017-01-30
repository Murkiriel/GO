<?php
	$api = 'https://api.telegram.org/bot';
	$bot = '156817358:AAHTJjK48KX3rBjrYv9GPZP3CKalGbtGcr0'; //TOKEN BOT

	$redis = conectarRedis();

	define('SUDOS', array(
		'0' => '96438491', //SUDO ID
		'1' => '279798801', // @FSMGORobot
		'2' => '138504783', // @SrTornado
		'3' => '263799625' // @Paulo6
	));

	define('API_BOT', $api . $bot);
	define('CONTEXTO', stream_context_create(array('http' => array('header'=>'Connection: close\r\n'))));
	define('RAIZ', system('pwd') . '/');
	define('VERSAO', '4.0.0 (SGF)');
