<?php
	$continue = false;

	switch(strtolower($texto[0])){
		case '/idioma':
		case '/language':
		case '/lingua':
			unset($dadosIdioma[$mensagens['message']['chat']['id']]);
	}

	if($mensagens['message']['text'] == '🇧🇷 Português'){
		$dadosIdioma[$mensagens['message']['chat']['id']] = array(
				'idioma' => 'PT'
		);

		$mensagens['message']['text'] = '/h';

		salvarDados(RAIZ . 'dados/idioma.json', $dadosIdioma);
	}

	if($mensagens['message']['text'] == '🇬🇧 English'){
		$dadosIdioma[$mensagens['message']['chat']['id']] = array(
				'idioma' => 'EN'
		);

		$mensagens['message']['text'] = '/h';

		salvarDados(RAIZ . 'dados/idioma.json', $dadosIdioma);
	}

	if($mensagens['message']['text'] == '🇪🇸 Español'){
		$dadosIdioma[$mensagens['message']['chat']['id']] = array(
				'idioma' => 'ES'
		);

		$mensagens['message']['text'] = '/h';

		salvarDados(RAIZ . 'dados/idioma.json', $dadosIdioma);
	}

	if($mensagens['message']['text'] == '🇮🇹 Italiano'){
		$dadosIdioma[$mensagens['message']['chat']['id']] = array(
				'idioma' => 'IT'
		);

		$mensagens['message']['text'] = '/h';

		salvarDados(RAIZ . 'dados/idioma.json', $dadosIdioma);
	}

	if(strcasecmp($mensagens['message']['text'], '/start' . '@' . DADOS_BOT['result']['username'] . ' new') == 0){
		$continue = true;
	}
	else if(empty($dadosIdioma[$mensagens['message']['chat']['id']])){
		$teclado =	[
									'inline_keyboard'	=>	[
																					[
																						['text' =>  '🇧🇷 Português', 'callback_data' => '🇧🇷 Português'	],
																						['text' =>  '🇬🇧 English'	, 'callback_data' => '🇬🇧 English'		]
																					],
																					[
																						['text' =>  '🇪🇸 Español'	, 'callback_data' => '🇪🇸 Español'		],
																						['text' =>  '🇮🇹 Italiano'	, 'callback_data' => '🇮🇹 Italiano'	]
																					]
																				]
								];

		$replyMarkup = json_encode($teclado);

		$mensagem =
			'<b>PT:</b> ' . TECLADO['PT'] . "\n" .
			'----------'									."\n".
			'<b>EN:</b> ' . TECLADO['EN'] ."\n".
			'----------'									."\n".
			'<b>ES:</b> ' . TECLADO['ES'] ."\n".
			'----------'									."\n".
			'<b>IT:</b> ' . TECLADO['IT'];

		sendMessage($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'], $replyMarkup, true);

		$continue = true;
	}
	else if(strcasecmp($mensagens['message']['text'], '/stop')																				 == 0					AND
										 $mensagens['message']['chat']['type']																					 == 'private'	OR
					strcasecmp($mensagens['message']['text'], '/stop' . '@' . DADOS_BOT['result']['username']) == 0					AND
										 $mensagens['message']['chat']['type']																					 == 'private'	){
	unset($dadosIdioma[$mensagens['message']['from']['id']]);

		$teclado =	[
									'inline_keyboard'	=>	[
																					[
																						['text' =>  '🤖'	, 'callback_data' => '/start'	]
																					]
																				]
								];

		$replyMarkup = json_encode($teclado);

		$mensagem = '<b>Stop!</b>';

		sendMessage($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'], $replyMarkup, true);

		salvarDados(RAIZ . 'dados/idioma.json', $dadosIdioma);

		$continue = true;
	}
	else if(isset($mensagens['message']['left_chat_participant']['id'])){
		if($mensagens['message']['left_chat_participant']['id'] == DADOS_BOT['result']['id']){
			unset($dadosIdioma[$mensagens['message']['chat']['id']]);

			salvarDados(RAIZ . 'dados/idioma.json', $dadosIdioma);

			$continue = true;
		}
	}
	else{
		$mensagens['IDIOMA'] = $dadosIdioma[$mensagens['message']['chat']['id']]['idioma'];
	}
