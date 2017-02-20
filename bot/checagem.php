<?php
	set_error_handler('manipularErros');

	// # MENSAGENS

	$mensagens = $this->mensagens;

	$mensagens['edit_message'] = false;

	if (isset($mensagens['callback_query'])) {
		$mensagens['callback_query']['message']['from'] = $mensagens['callback_query']['from'];
		$mensagens['callback_query']['message']['text'] = $mensagens['callback_query']['data'];
		$mensagens['message'] = $mensagens['callback_query']['message'];

		if (isset($mensagens['message']['reply_to_message']['from'])) {
			$mensagens['message']['from'] = $mensagens['message']['reply_to_message']['from'];
		}

		$mensagens['edit_message'] = true;

		unset($mensagens['callback_query']);
	} else if (isset($mensagens['edited_message'])) {
		$mensagens['message'] = $mensagens['edited_message'];

		unset($mensagens['edited_message']);
	} else if (empty($mensagens['message']['text'])) {
		$exit = true;
	}

	// # IDIOMA

	$texto = explode(' ', $mensagens['message']['text']);
	$texto[0] = substr(str_ireplace('@' . DADOS_BOT['result']['username'], '', $texto[0]), 1);

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
								$mensagens['message']['message_id'], $replyMarkup, true, $mensagens['edit_message']
		);

		$exit = true;
	}

	if (strcasecmp($mensagens['message']['text'], '/start' . '@' . DADOS_BOT['result']['username'] . ' new') == 0) {
		$exit = true;
	} else if (isset($mensagens['message']['left_chat_participant']['id']) and
									 $mensagens['message']['left_chat_participant']['id'] == DADOS_BOT['result']['id']) {
		$exit = true;
	} else if ($mensagens['channel_post']['chat']['type'] == 'channel') {
		$redis->set('canais:' . $mensagens['channel_post']['chat']['id'], '@' . $mensagens['channel_post']['chat']['username']);
		$exit = true;
	} else if ($exit === false) {
		try {
			if ($redis->exists('idioma:' . $mensagens['message']['from']['id']) === false and isset($idioma)) {
				$redis->set('idioma:' . $mensagens['message']['from']['id'], $idioma);
			}
		} catch (Exception $e) {
			$redis->set('idioma:' . $mensagens['message']['chat']['id'], $idioma);
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

		// # BEM-VINDO

		if (isset($mensagens['message']['new_chat_participant'])) {
			if ($redis->hget('bemvindo:' . $mensagens['message']['chat']['id'], 'ativo') === 'true') {
						$tipoMensagem = $redis->hget('bemvindo:' . $mensagens['message']['chat']['id'], 'tipo');
				$conteudoMensagem = $redis->hget('bemvindo:' . $mensagens['message']['chat']['id'], 'conteudo');

				if ($tipoMensagem == 'documento') {
					sendDocument($mensagens['message']['chat']['id'], $conteudoMensagem, $mensagens['message']['message_id'], null, null);
				} else if ($tipoMensagem == 'foto') {
					sendPhoto($mensagens['message']['chat']['id'], $conteudoMensagem, $mensagens['message']['message_id'], null, null);
				} else {
					$replyMarkup = montarTeclado($conteudoMensagem);
					$conteudoMensagem = removerTeclado($conteudoMensagem);

					$conteudoMensagem = str_ireplace('$nome', $mensagens['message']['new_chat_participant']['first_name'],
																					 $conteudoMensagem);
					$conteudoMensagem = str_ireplace('$grupo', $mensagens['message']['chat']['title'],
																					 $conteudoMensagem);

					if (isset($mensagens['message']['new_chat_participant']['username'])) {
						$conteudoMensagem = str_ireplace('$usuario', '@' . $mensagens['message']['new_chat_participant']['username'],
																						 $conteudoMensagem);
					} else {
						$conteudoMensagem = str_ireplace('$usuario', $mensagens['message']['new_chat_participant']['first_name'],
																						 $conteudoMensagem);
					}

					sendMessage($mensagens['message']['chat']['id'], $conteudoMensagem,
											$mensagens['message']['message_id'], $replyMarkup, true);
				}
			}
		}
	}

	// # STATUS

	if ($mensagens['message']['chat']['type'] == 'private' or $mensagens['message']['chat']['type'] == 'group') {
		$redis->set('status_bot:privateorgroup', $mensagens['message']['message_id']);
	} else if ($mensagens['message']['chat']['type'] == 'supergroup') {
		$redis->set('status_bot:supergroup', $mensagens['message']['message_id']);
	}
