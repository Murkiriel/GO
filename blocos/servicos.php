<?php

// # BEM-VINDO

if (isset($mensagens['message']['new_chat_participant'])) {
	if ($redis->hget('bemvindo:' . $mensagens['message']['chat']['id'], 'ativo') === 'true') {
		$tipo = $redis->hget('bemvindo:' . $mensagens['message']['chat']['id'], 'tipo');
		$mensagem = $redis->hget('bemvindo:' . $mensagens['message']['chat']['id'], 'conteudo');

		if ($tipo == 'documento') {
			sendDocument($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'], null, null);
		} else if ($tipo == 'foto') {
			sendPhoto($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'], null, null);
		} else {
			$replyMarkup = montarTeclado($mensagem);
			$mensagem = removerTeclado($mensagem);

			$mensagem = str_ireplace('$nome', $mensagens['message']['new_chat_participant']['first_name'],
																			 $mensagem);
			$mensagem = str_ireplace('$grupo', $mensagens['message']['chat']['title'],
																			 $mensagem);

			if (isset($mensagens['message']['new_chat_participant']['username'])) {
				$mensagem = str_ireplace('$usuario', '@' . $mensagens['message']['new_chat_participant']['username'], $mensagem);
			} else {
				$mensagem = str_ireplace('$usuario', $mensagens['message']['new_chat_participant']['first_name'], $mensagem);
			}

			sendMessage($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'], $replyMarkup, true);
		}
	}
}

	// # DOCUMENTOS

	foreach ($redis->keys('documentos:*') as $hash) {
		if ($redis->hexists($hash, $mensagens['message']['text']) === true) {
			$teclado['hide_keyboard'] = true;

			$replyMarkup = json_encode($teclado);

			$idDocumento = $redis->hget($hash, $mensagens['message']['text']);

			sendChatAction($mensagens['message']['chat']['id'], 'upload_document');

			if ($mensagens['message']['chat']['type'] == 'group' or $mensagens['message']['chat']['type'] == 'supergroup') {
				sendDocument($mensagens['message']['chat']['id'], $idDocumento,
										 $mensagens['message']['message_id'], $replyMarkup);

				$mensagens['message']['message_id'] = null;
			}

			sendDocument($mensagens['message']['from']['id'], $idDocumento,
									 $mensagens['message']['message_id'], $replyMarkup);

			break;
		}
	}

	if ($mensagens['message']['chat']['type'] == 'private' and isset($mensagens['message']['document']['mime_type'])) {
		if (in_array($mensagens['message']['from']['id'], SUDOS)) {
			if (substr($mensagens['message']['document']['file_name'], -4) == '.apk' or
					substr($mensagens['message']['document']['file_name'], -4) == '.obb') {
				$redis->hset('documentos:store', $mensagens['message']['document']['file_name'], $mensagens['message']['document']['file_id']);

				$mensagem = '<b> 📱 APK/OBB ADICIONADO 📱 </b>' . "\n\n" .
										'<b>Nome:</b> ' . $mensagens['message']['document']['file_name'] . "\n" .
										'<b>ID:</b> ' . $mensagens['message']['document']['file_id'];

				enviarLog($mensagem);
			} else if (substr($mensagens['message']['document']['file_name'], -4) == '.pdf' or
								 substr($mensagens['message']['document']['file_name'], -5) == '.epub' or
								 substr($mensagens['message']['document']['file_name'], -5) == '.mobi') {
				$redis->hset('documentos:livros', $mensagens['message']['document']['file_name'], $mensagens['message']['document']['file_id']);

				$mensagem = '<b> 📱 LIVRO ADICIONADO 📱 </b>' . "\n\n" .
										'<b>Nome:</b> ' . $mensagens['message']['document']['file_name'] . "\n" .
										'<b>ID:</b> ' . $mensagens['message']['document']['file_id'];

				enviarLog($mensagem);
			} else if (substr($mensagens['message']['document']['file_name'], -4) == '.mkv' or
								 substr($mensagens['message']['document']['file_name'], -4) == '.mp4' or
								 substr($mensagens['message']['document']['file_name'], -4) == '.avi') {
				$redis->hset('documentos:tv', $mensagens['message']['document']['file_name'], $mensagens['message']['document']['file_id']);

				$mensagem = '<b> 📱 VÍDEO ADICIONADO 📱 </b>' . "\n\n" .
										'<b>Nome:</b> ' . $mensagens['message']['document']['file_name'] . "\n" .
										'<b>ID:</b> ' . $mensagens['message']['document']['file_id'];

				enviarLog($mensagem);
			} else if (substr($mensagens['message']['document']['file_name'], -4) == '.cso') {
				$redis->hset('documentos:psp', $mensagens['message']['document']['file_name'], $mensagens['message']['document']['file_id']);

				$mensagem = '<b> 📱 PSP ADICIONADO 📱 </b>' . "\n\n" .
										'<b>Nome:</b> ' . $mensagens['message']['document']['file_name'] . "\n" .
										'<b>ID:</b> ' . $mensagens['message']['document']['file_id'];

				enviarLog($mensagem);
			} else if (substr($mensagens['message']['document']['file_name'], -4) == '.smc') {
				$redis->hset('documentos:snes', $mensagens['message']['document']['file_name'], $mensagens['message']['document']['file_id']);

				$mensagem = '<b> 📱 SNES ADICIONADO 📱 </b>' . "\n\n" .
										'<b>Nome:</b> ' . $mensagens['message']['document']['file_name'] . "\n" .
										'<b>ID:</b> ' . $mensagens['message']['document']['file_id'];

				enviarLog($mensagem);
			}
		}
	}

	// # STATUS

	if ($mensagens['message']['chat']['type'] == 'private' or $mensagens['message']['chat']['type'] == 'group') {
		$redis->set('status_bot:privateorgroup', $mensagens['message']['message_id']);
	}
