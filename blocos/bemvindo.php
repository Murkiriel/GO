<?php
	if ($mensagens['message']['chat']['type'] == 'group' or $mensagens['message']['chat']['type'] == 'supergroup') {
			 $resultado = getChatAdministrators($mensagens['message']['chat']['id']);
		$usuarioAdmin = validarAdmin($resultado['result'], $mensagens['message']['from']['id']);

		if ($usuarioAdmin === true) {
			if (isset($texto[1]) and strtolower($texto[1]) == 'on') {
				if ($redis->hexists('bemvindo:' . $mensagens['message']['chat']['id'], 'conteudo') === true) {
					$redis->hset('bemvindo:' . $mensagens['message']['chat']['id'], 'ativo', 'true');

					$mensagem = BEMVINDO[$idioma]['ATIVO'];
				} else {
					$mensagem = BEMVINDO[$idioma]['NAO_DEFINIDA'];
				}
			} else if (isset($texto[1]) and strtolower($texto[1]) == 'off') {
				if ($redis->hexists('bemvindo:' . $mensagens['message']['chat']['id'], 'conteudo') == true) {
					$redis->hset('bemvindo:' . $mensagens['message']['chat']['id'], 'ativo', 'false');

					$mensagem = BEMVINDO[$idioma]['DESATIVO'];
				} else {
					$mensagem = BEMVINDO[$idioma]['NAO_DEFINIDA'];
				}
			} else if (isset($mensagens['message']['reply_to_message']['text'])) {
				$redis->hset('bemvindo:' . $mensagens['message']['chat']['id'], 'ativo', 'true');
				$redis->hset('bemvindo:' . $mensagens['message']['chat']['id'], 'tipo', 'texto');
				$redis->hset('bemvindo:' . $mensagens['message']['chat']['id'], 'conteudo',
										 $mensagens['message']['reply_to_message']['text']);

				$mensagem = BEMVINDO[$idioma]['CRIADA'];
			} else if (isset($mensagens['message']['reply_to_message']['document']['file_id'])) {
				$redis->hset('bemvindo:' . $mensagens['message']['chat']['id'], 'ativo', 'true');
				$redis->hset('bemvindo:' . $mensagens['message']['chat']['id'], 'tipo', 'documento');
				$redis->hset('bemvindo:' . $mensagens['message']['chat']['id'], 'conteudo',
										 $mensagens['message']['reply_to_message']['document']['file_id']);

				$mensagem = BEMVINDO[$idioma]['CRIADA'];
			} else if (isset($mensagens['message']['reply_to_message']['sticker']['file_id'])) {
				$redis->hset('bemvindo:' . $mensagens['message']['chat']['id'], 'ativo', 'true');
				$redis->hset('bemvindo:' . $mensagens['message']['chat']['id'], 'tipo', 'documento');
				$redis->hset('bemvindo:' . $mensagens['message']['chat']['id'], 'conteudo',
										 $mensagens['message']['reply_to_message']['sticker']['file_id']);

				$mensagem = BEMVINDO[$idioma]['CRIADA'];

			} else if (isset($mensagens['message']['reply_to_message']['photo'][0]['file_id'])) {
				$redis->hset('bemvindo:' . $mensagens['message']['chat']['id'], 'ativo', 'true');
				$redis->hset('bemvindo:' . $mensagens['message']['chat']['id'], 'tipo', 'foto');
				$redis->hset('bemvindo:' . $mensagens['message']['chat']['id'], 'conteudo',
										 $mensagens['message']['reply_to_message']['photo'][0]['file_id']);

				$mensagem = BEMVINDO[$idioma]['CRIADA'];
			} else if (isset($texto[1])) {
				$conteudo = removerComando($texto[0], $mensagens['message']['text']);
				$replyMarkup = montarTeclado($conteudo);
				$mensagem = removerTeclado($conteudo);

				$resultado = sendMessage($mensagens['message']['chat']['id'], $mensagem, null, $replyMarkup, true);

				if ($resultado['ok'] === true) {
					$redis->hset('bemvindo:' . $mensagens['message']['chat']['id'], 'ativo', 'true');
					$redis->hset('bemvindo:' . $mensagens['message']['chat']['id'], 'tipo', 'texto');
					$redis->hset('bemvindo:' . $mensagens['message']['chat']['id'], 'conteudo', $conteudo);

					$mensagem = BEMVINDO[$idioma]['CRIADA'];
				} else {
					$mensagem = ERROS[$idioma]['SINTAXE'] . "\n\n" . '<pre>' . json_encode($resultado) . '</pre>';
				}
			} else {
				$mensagem = BEMVINDO[$idioma]['AJUDA'];
			}
		} else {
			$mensagem = ERROS[$idioma]['SMT_ADMS'];
		}
	} else if ($mensagens['message']['chat']['type'] == 'private') {
		$mensagem = ERROS[$idioma]['SMT_GRUPO'];
	}

	sendMessage($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'],
							null, true, $mensagens['edit_message']);
