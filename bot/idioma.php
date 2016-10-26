<?php
	$continue = false;

	$dadosIdioma = carregarDados(RAIZ . 'dados/idioma.json');

	$texto = explode(' ', $mensagens['message']['text']);

	if(empty($texto[1])){
		$texto[0] = str_ireplace('@' . $dadosBot['result']['username'], '', $texto[0]);
	}

	switch(strtolower($texto[0])){
		case '/idioma':
		case '/language':
		case '/lingua':
			unset($dadosIdioma[$mensagens['message']['chat']['id']]);
	}

	if($mensagens['message']['text'] == '🇧🇷 Português'){
		$dadosIdioma[$mensagens['message']['chat']['id']] = array(
				'idioma' => 'pt'
		);

		$teclado = array(
			'hide_keyboard' => true
		);

		$replyMarkup = json_encode($teclado);

		$mensagem = DEF_IDIOMA['pt'];

		salvarDados(RAIZ . 'dados/idioma.json', $dadosIdioma);

		sendMessage($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'], $replyMarkup, true);
	}

	if($mensagens['message']['text'] == '🇬🇧 English'){
		$dadosIdioma[$mensagens['message']['chat']['id']] = array(
				'idioma' => 'en'
		);

		$teclado = array(
			'hide_keyboard' => true
		);

		$replyMarkup = json_encode($teclado);

		$mensagem = DEF_IDIOMA['en'];

		salvarDados(RAIZ . 'dados/idioma.json', $dadosIdioma);

		sendMessage($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'], $replyMarkup, true);
	}

	if($mensagens['message']['text'] == '🇪🇸 Español'){
		$dadosIdioma[$mensagens['message']['chat']['id']] = array(
				'idioma' => 'es'
		);

		$teclado = array(
			'hide_keyboard' => true
		);

		$replyMarkup = json_encode($teclado);

		$mensagem = DEF_IDIOMA['es'];

		salvarDados(RAIZ . 'dados/idioma.json', $dadosIdioma);

		sendMessage($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'], $replyMarkup, true);
	}

	if($mensagens['message']['text'] == '🇮🇹 Italiano'){
		$dadosIdioma[$mensagens['message']['chat']['id']] = array(
				'idioma' => 'it'
		);

		$teclado = array(
			'hide_keyboard' => true
		);

		$replyMarkup = json_encode($teclado);

		$mensagem = DEF_IDIOMA['it'];

		salvarDados(RAIZ . 'dados/idioma.json', $dadosIdioma);

		sendMessage($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'], $replyMarkup, true);
	}

	if(isset($mensagens['message']['left_chat_member']['id'])){
		if($mensagens['message']['left_chat_member']['id'] == $dadosBot['result']['id']){
			unset($dadosIdioma[$mensagens['message']['chat']['id']]);

			salvarDados(RAIZ . 'dados/idioma.json', $dadosIdioma);
		}
	}

	if(empty($dadosIdioma[$mensagens['message']['chat']['id']])){
		$teclado = array(
			'keyboard' => array(
				array("🇧🇷 Português", "🇬🇧 English"),
				array(  "🇪🇸 Español", "🇮🇹 Italiano")
			),
			'resize_keyboard'	=> true,
			'one_time_keyboard'	=> true
		);

		$replyMarkup = json_encode($teclado);

		$mensagem =
			'<b>PT:</b> ' . TECLADO['pt'] . "\n" .
			'——————————'									."\n".
			'<b>EN:</b> ' . TECLADO['en'] ."\n".
			'——————————'									."\n".
			'<b>ES:</b> ' . TECLADO['es'] ."\n".
			'——————————'									."\n".
			'<b>IT:</b> ' . TECLADO['it'];

		sendMessage($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'], $replyMarkup, true);

		$continue = true;
	}
	else if(strcasecmp($mensagens['message']['text'], '/stop')																				 == 0					AND
										 $mensagens['message']['chat']['type']																					 == 'private'	OR
					strcasecmp($mensagens['message']['text'], '/stop' . '@' . $dadosBot['result']['username']) == 0					AND
										 $mensagens['message']['chat']['type']																					 == 'private'	){
		unset($dadosIdioma[$mensagens['message']['from']['id']]);

		$teclado = array(
			'hide_keyboard' => true
		);

		$replyMarkup = json_encode($teclado);

		$mensagem = '<b>Stop!</b>';

		salvarDados(RAIZ . 'dados/idioma.json', $dadosIdioma);

		sendMessage($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'], $replyMarkup, true);
	}
	else{
		$IDIOMA = $dadosIdioma[$mensagens['message']['chat']['id']]['idioma'];
	}
