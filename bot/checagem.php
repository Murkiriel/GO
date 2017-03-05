<?php
	set_error_handler('manipularErros');

	$mensagens = $this->mensagens;

	// # MENSAGENS

	$mensagens['edit_message'] = false;

	if (isset($mensagens['callback_query'])) {
		$from = $mensagens['callback_query']['from'];
		$mensagens['callback_query']['message']['text'] = $mensagens['callback_query']['data'];
		$mensagens['message'] = $mensagens['callback_query']['message'];

		unset($mensagens['callback_query']);

		$mensagens['message']['from'] = $from;
		$mensagens['edit_message'] = true;
	} else if (isset($mensagens['edited_message'])) {
		$mensagens['message'] = $mensagens['edited_message'];

		unset($mensagens['edited_message']);
	} else if (empty($mensagens['message']['text'])) {
		$exit = true;
	}

	// # IDIOMA

	$texto = str_ireplace('@' . DADOS_BOT['result']['username'], '', $mensagens['message']['text']);
	$texto = str_ireplace('_', ' ', $texto);
	$texto = explode(' ', $texto);
	$texto[0] = substr($texto[0], 1);

	switch (strtolower($texto[0])) {
		case 'portugues':
			$redis->set('idioma:' . $mensagens['message']['chat']['id'], 'PT');
			$texto[0] = 'start';
			break;
		case 'english':
			$redis->set('idioma:' . $mensagens['message']['chat']['id'], 'EN');
			$texto[0] = 'start';
			break;
		case 'espanol':
			$redis->set('idioma:' . $mensagens['message']['chat']['id'], 'ES');
			$texto[0] = 'start';
			break;
		case 'italiano':
			$redis->set('idioma:' . $mensagens['message']['chat']['id'], 'IT');
			$texto[0] = 'start';
			break;
		case 'idioma':
		case 'language':
		case 'lingua':
			$redis->del('idioma:' . $mensagens['message']['chat']['id']);
			break;
	}

	if ($redis->exists('idioma:' . $mensagens['message']['chat']['id']) === true) {
		$idioma = $redis->get('idioma:' . $mensagens['message']['chat']['id']);
	} else {
		$teclado = [
									'inline_keyboard'	=>	[
																					[
																						['text' => '🇧🇷 Português', 'callback_data' => '/portugues'],
																						['text' => '🇬🇧 English', 'callback_data' => '/english']
																					],
																					[
																						['text' => '🇪🇸 Español', 'callback_data' => '/espanol'],
																						['text' => '🇮🇹 Italiano', 'callback_data' => '/italiano']
																					]
																				]
								];

		$replyMarkup = json_encode($teclado);

		$mensagem =
			'<b>PT:</b> ' . TECLADO['PT'] . "\n" . '----------' . "\n" .
			'<b>EN:</b> ' . TECLADO['EN'] . "\n" . '----------' . "\n" .
			'<b>ES:</b> ' . TECLADO['ES'] . "\n" . '----------' . "\n" .
			'<b>IT:</b> ' . TECLADO['IT'];

		sendMessage($mensagens['message']['chat']['id'], $mensagem,
								$mensagens['message']['message_id'], $replyMarkup, true, $mensagens['edit_message']);

		$exit = true;
	}

	if (strcasecmp($mensagens['message']['text'], '/start' . '@' . DADOS_BOT['result']['username'] . ' new') == 0) {
		$exit = true;
	} else if (isset($mensagens['message']['left_chat_participant']['id'])) {
		$exit = true;
	} else if ($mensagens['channel_post']['chat']['type'] == 'channel') {
		$redis->set('canais:' . $mensagens['channel_post']['chat']['id'], '@' . $mensagens['channel_post']['chat']['username']);
		$exit = true;
	}

	if ($exit === false) {
		try {
			if ($redis->exists('idioma:' . $mensagens['message']['from']['id']) === false and isset($idioma)) {
				$redis->set('idioma:' . $mensagens['message']['from']['id'], $idioma);
			}
		} catch (Exception $e) {
			$redis->set('idioma:' . $mensagens['message']['chat']['id'], $idioma);
		}
	}

	// # RANKING

	if ($mensagens['edit_message'] === false) {
		if ($mensagens['message']['chat']['type'] == 'group' or
				$mensagens['message']['chat']['type'] == 'supergroup' or
				$mensagens['message']['chat']['type'] == 'private') {

				$redis->hset('ranking:' . $mensagens['message']['chat']['id'] . ':' .
										 $mensagens['message']['from']['id'], 'primeiro_nome', $mensagens['message']['from']['first_name']);

				$redis->hincrby('ranking:' . $mensagens['message']['chat']['id'] . ':' .
												$mensagens['message']['from']['id'], 'qntd_mensagem', 1);
		}
	}

	// # PARA LINKS

	if (strtolower($texto[0]) == 'start' and isset($texto[1])) {
		array_shift($texto);
	}
