<?php
	$mensagens = unserialize($_SERVER['argv'][1]);
	 $dadosBot = unserialize($_SERVER['argv'][2]);

	include('funcoes.php');
	include('config.php');
	include('idioma.php');

	$texto = explode(' ', $mensagens['message']['text']);

	if(empty($texto[1])){
		$texto[0] = str_ireplace('@' . $dadosBot['result']['username'], '', $texto[0]);
	}

	switch(strtolower($texto[0])){
		case '/start':
		case '/ajuda':
		case '/help':
		case '/ayuda':
		case '/aiuto':
      include(RAIZ . '/blocos/ajuda.php');
      break;
		case '/id':
      include(RAIZ . '/blocos/id.php');
      break;
		case '/ranking':
      include(RAIZ . '/blocos/ranking.php');
      break;
	}

	if(in_array($mensagens['message']['from']['id'], $sudos)){
		include(RAIZ . 'blocos/sudos.php');
	}

	die();
