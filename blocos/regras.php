<?php
	if ($mensagens['message']['chat']['type'] == 'group' OR $mensagens['message']['chat']['type'] == 'supergroup') {
		$usuarioAdmin = FALSE;
			 $resultado = getChatAdministrators($mensagens['message']['chat']['id']);

		foreach ($resultado['result'] as $adminsGrupo) {
			if ($adminsGrupo['user']['id'] == $mensagens['message']['from']['id']) {
				$usuarioAdmin = TRUE;
				break;
			}
		}

				$mensagem = REGRAS[$idioma]['AJUDA'];
		$tipoMensagem = '';

		if ($usuarioAdmin === TRUE) {
			if (strtolower($texto[1]) == 'on') {
				if ($redis->hexists('regras:' . $mensagens['message']['chat']['id'], 'conteudo')) {
					$redis->hset('regras:' . $mensagens['message']['chat']['id'], 'ativo', 'TRUE');

					$mensagem = REGRAS[$idioma]['ATIVO'];
				} else {
					$mensagem = REGRAS[$idioma]['NAO_DEFINIDA'];
				}
			} else if (strtolower($texto[1]) == 'off') {
				if ($redis->hexists('regras:' . $mensagens['message']['chat']['id'], 'conteudo')) {
					$redis->hset('regras:' . $mensagens['message']['chat']['id'], 'ativo', 'FALSE');

					$mensagem = REGRAS[$idioma]['DESATIVO'];
				} else {
					$mensagem = REGRAS[$idioma]['NAO_DEFINIDA'];
				}
			} else if (isset($mensagens['message']['reply_to_message']['text']) AND strtolower($texto[1]) == 'set') {
				$redis->hset('regras:' . $mensagens['message']['chat']['id'], 'ativo', 'TRUE');
				$redis->hset('regras:' . $mensagens['message']['chat']['id'], 'tipo', 'texto');
				$redis->hset('regras:' . $mensagens['message']['chat']['id'], 'conteudo', $mensagens['message']['reply_to_message']['text']);

				$mensagem = REGRAS[$idioma]['CRIADA'];
			} else if (isset($mensagens['message']['reply_to_message']['document']['file_id']) AND strtolower($texto[1]) == 'set') {
				$redis->hset('regras:' . $mensagens['message']['chat']['id'], 'ativo', 'TRUE');
				$redis->hset('regras:' . $mensagens['message']['chat']['id'], 'tipo', 'documento');
				$redis->hset('regras:' . $mensagens['message']['chat']['id'], 'conteudo', $mensagens['message']['reply_to_message']['document']['file_id']);

				$mensagem = REGRAS[$idioma]['CRIADA'];
			} else if (isset($mensagens['message']['reply_to_message']['sticker']['file_id']) AND strtolower($texto[1]) == 'set') {
				$redis->hset('regras:' . $mensagens['message']['chat']['id'], 'ativo', 'TRUE');
				$redis->hset('regras:' . $mensagens['message']['chat']['id'], 'tipo', 'documento');
				$redis->hset('regras:' . $mensagens['message']['chat']['id'], 'conteudo', $mensagens['message']['reply_to_message']['sticker']['file_id']);

				$mensagem = REGRAS[$idioma]['CRIADA'];

			} else if (isset($mensagens['message']['reply_to_message']['photo'][0]['file_id']) AND strtolower($texto[1]) == 'set') {
				$redis->hset('regras:' . $mensagens['message']['chat']['id'], 'ativo', 'TRUE');
				$redis->hset('regras:' . $mensagens['message']['chat']['id'], 'tipo', 'foto');
				$redis->hset('regras:' . $mensagens['message']['chat']['id'], 'conteudo', $mensagens['message']['reply_to_message']['photo'][0]['file_id']);

				$mensagem = REGRAS[$idioma]['CRIADA'];
			}
		}

		if (empty($texto[1]) AND $redis->hexists('regras:' . $mensagens['message']['chat']['id'], 'conteudo') AND $redis->hget('regras:' . $mensagens['message']['chat']['id'], 'ativo') === 'TRUE') {
					$mensagem = $redis->hget('regras:' . $mensagens['message']['chat']['id'], 'conteudo');
			$tipoMensagem = $redis->hget('regras:' . $mensagens['message']['chat']['id'], 'tipo');
		} else if ($usuarioAdmin === FALSE) {
		 	$mensagem = ERROS[$idioma]['SMT_ADMS'];
		}

		if ($tipoMensagem == 'documento') {
			sendDocument($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'], NULL, NULL);
		} else if ($tipoMensagem == 'foto') {
			sendPhoto($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'], NULL, NULL);
		} else {
			sendMessage($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'], NULL, TRUE);
		}
	} else if ($mensagens['message']['chat']['type'] == 'private') {
		$mensagem = ERROS[$idioma]['SMT_GRUPO'];

		sendMessage($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'], NULL, TRUE);
	}
