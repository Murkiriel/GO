<?php
	$tipo = '';

	if ($mensagens['message']['chat']['type'] == 'group' or $mensagens['message']['chat']['type'] == 'supergroup') {
		$resultado = getChatAdministrators($mensagens['message']['chat']['id']);
		$usuarioAdmin = validarAdmin($resultado['result'], $mensagens['message']['from']['id']);

		$mensagem = '';

		if ($usuarioAdmin === true) {
			if (isset($texto[1]) and strtolower($texto[1]) == 'on') {
				if ($redis->hexists('regras:' . $mensagens['message']['chat']['id'], 'conteudo') === true) {
					$redis->hset('regras:' . $mensagens['message']['chat']['id'], 'ativo', 'true');

					$mensagem = REGRAS[$idioma]['ATIVO'];
				} else {
					$mensagem = REGRAS[$idioma]['NAO_DEFINIDA'];
				}
			} else if (isset($texto[1]) and strtolower($texto[1]) == 'off') {
				if ($redis->hexists('regras:' . $mensagens['message']['chat']['id'], 'conteudo') === true) {
					$redis->hset('regras:' . $mensagens['message']['chat']['id'], 'ativo', 'false');

					$mensagem = REGRAS[$idioma]['DESATIVO'];
				} else {
					$mensagem = REGRAS[$idioma]['NAO_DEFINIDA'];
				}
			} else if (isset($mensagens['message']['reply_to_message']['text']) and strtolower($texto[1]) == 'set') {
				$redis->hset('regras:' . $mensagens['message']['chat']['id'], 'ativo', 'true');
				$redis->hset('regras:' . $mensagens['message']['chat']['id'], 'tipo', 'texto');
				$redis->hset('regras:' . $mensagens['message']['chat']['id'], 'conteudo',
										 $mensagens['message']['reply_to_message']['text']);

				$mensagem = REGRAS[$idioma]['CRIADA'];
			} else if (isset($mensagens['message']['reply_to_message']['document']['file_id']) and
								 isset($texto[1]) and strtolower($texto[1]) == 'set') {
				$redis->hset('regras:' . $mensagens['message']['chat']['id'], 'ativo', 'true');
				$redis->hset('regras:' . $mensagens['message']['chat']['id'], 'tipo', 'documento');
				$redis->hset('regras:' . $mensagens['message']['chat']['id'], 'conteudo',
										 $mensagens['message']['reply_to_message']['document']['file_id']
				);

				$mensagem = REGRAS[$idioma]['CRIADA'];
			} else if (isset($mensagens['message']['reply_to_message']['sticker']['file_id']) and
								 isset($texto[1]) and strtolower($texto[1]) == 'set') {
				$redis->hset('regras:' . $mensagens['message']['chat']['id'], 'ativo', 'true');
				$redis->hset('regras:' . $mensagens['message']['chat']['id'], 'tipo', 'documento');
				$redis->hset('regras:' . $mensagens['message']['chat']['id'], 'conteudo',
										 $mensagens['message']['reply_to_message']['sticker']['file_id']
				);

				$mensagem = REGRAS[$idioma]['CRIADA'];

			} else if (isset($mensagens['message']['reply_to_message']['photo'][0]['file_id']) and
								 isset($texto[1]) and strtolower($texto[1]) == 'set') {
				$redis->hset('regras:' . $mensagens['message']['chat']['id'], 'ativo', 'true');
				$redis->hset('regras:' . $mensagens['message']['chat']['id'], 'tipo', 'foto');
				$redis->hset('regras:' . $mensagens['message']['chat']['id'], 'conteudo',
										 $mensagens['message']['reply_to_message']['photo'][0]['file_id']
				);

				$mensagem = REGRAS[$idioma]['CRIADA'];
			} else if (isset($texto[1]) and $texto[1] == '?') {
				$mensagem = REGRAS[$idioma]['AJUDA'];
			} else if (isset($texto[1]) and strtolower($texto[1]) != 'set') {
				$conteudo = removerComando($texto[0], $mensagens['message']['text']);

				$redis->hset('regras:' . $mensagens['message']['chat']['id'], 'ativo', 'true');
				$redis->hset('regras:' . $mensagens['message']['chat']['id'], 'tipo', 'texto');
				$redis->hset('regras:' . $mensagens['message']['chat']['id'], 'conteudo', $conteudo);

				$mensagem = REGRAS[$idioma]['CRIADA'];
			}
		}

		if (empty($texto[1]) and $redis->hget('regras:' . $mensagens['message']['chat']['id'], 'ativo') === 'true') {
			$mensagem = $redis->hget('regras:' . $mensagens['message']['chat']['id'], 'conteudo');
			$tipo = $redis->hget('regras:' . $mensagens['message']['chat']['id'], 'tipo');
		} else if ($usuarioAdmin === false) {
		 	$mensagem = ERROS[$idioma]['SMT_ADMS'];
		}

		if (empty($mensagem)) {
			$mensagem = REGRAS[$idioma]['AJUDA'];
		}
	} else if ($mensagens['message']['chat']['type'] == 'private') {
		$mensagem = ERROS[$idioma]['SMT_GRUPO'];
	}

	if ($tipo == 'documento') {
		sendDocument($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'], null, null);
	} else if ($tipo == 'foto') {
		sendPhoto($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'], null, null);
	} else {
		sendMessage($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'], null, true);
	}
