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

		if ($usuarioAdmin === TRUE) {
			if (strtolower($texto[1]) == 'on') {
				if ($redis->hexists('bemvindo:' . $mensagens['message']['chat']['id'], 'conteudo')) {
					$redis->hset('bemvindo:' . $mensagens['message']['chat']['id'], 'ativo', 'TRUE');

					$mensagem = BEMVINDO[$idioma]['ATIVO'];
				} else {
					$mensagem = BEMVINDO[$idioma]['NAO_DEFINIDA'];
				}
			} else if (strtolower($texto[1]) == 'off') {
				if ($redis->hexists('bemvindo:' . $mensagens['message']['chat']['id'], 'conteudo')) {
					$redis->hset('bemvindo:' . $mensagens['message']['chat']['id'], 'ativo', 'FALSE');

					$mensagem = BEMVINDO[$idioma]['DESATIVO'];
				} else {
					$mensagem = BEMVINDO[$idioma]['NAO_DEFINIDA'];
				}
			} else if (isset($mensagens['message']['reply_to_message']['text'])) {
				$redis->hset('bemvindo:' . $mensagens['message']['chat']['id'], 'ativo', 'TRUE');
				$redis->hset('bemvindo:' . $mensagens['message']['chat']['id'], 'tipo', 'texto');
				$redis->hset('bemvindo:' . $mensagens['message']['chat']['id'], 'conteudo', $mensagens['message']['reply_to_message']['text']);

				$mensagem = BEMVINDO[$idioma]['CRIADA'];
			} else if (isset($mensagens['message']['reply_to_message']['document']['file_id'])) {
				$redis->hset('bemvindo:' . $mensagens['message']['chat']['id'], 'ativo', 'TRUE');
				$redis->hset('bemvindo:' . $mensagens['message']['chat']['id'], 'tipo', 'documento');
				$redis->hset('bemvindo:' . $mensagens['message']['chat']['id'], 'conteudo', $mensagens['message']['reply_to_message']['document']['file_id']);

				$mensagem = BEMVINDO[$idioma]['CRIADA'];
			} else if (isset($mensagens['message']['reply_to_message']['sticker']['file_id'])) {
				$redis->hset('bemvindo:' . $mensagens['message']['chat']['id'], 'ativo', 'TRUE');
				$redis->hset('bemvindo:' . $mensagens['message']['chat']['id'], 'tipo', 'documento');
				$redis->hset('bemvindo:' . $mensagens['message']['chat']['id'], 'conteudo', $mensagens['message']['reply_to_message']['sticker']['file_id']);

				$mensagem = BEMVINDO[$idioma]['CRIADA'];

			} else if (isset($mensagens['message']['reply_to_message']['photo'][0]['file_id'])) {
				$redis->hset('bemvindo:' . $mensagens['message']['chat']['id'], 'ativo', 'TRUE');
				$redis->hset('bemvindo:' . $mensagens['message']['chat']['id'], 'tipo', 'foto');
				$redis->hset('bemvindo:' . $mensagens['message']['chat']['id'], 'conteudo', $mensagens['message']['reply_to_message']['photo'][0]['file_id']);

				$mensagem = BEMVINDO[$idioma]['CRIADA'];
			} else {
				$mensagem = BEMVINDO[$idioma]['AJUDA'];
			}
		} else {
			$mensagem = ERROS[$idioma]['SMT_ADMS'];
		}
	} else if ($mensagens['message']['chat']['type'] == 'private') {
		$mensagem = ERROS[$idioma]['SMT_GRUPO'];
	}

	sendMessage($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'], NULL, TRUE);
