<?php
	$chavesLista = array(
		0 => 'store',
		1 => 'livros',
		2 => 'tv'
	);

	foreach ($chavesLista as $chave) {
		if ($redis->hexists('documentos:' . $chave, $mensagens['message']['text'])) {
			$teclado = array(
				'hide_keyboard' => TRUE
			);

			$replyMarkup = json_encode($teclado);

			$documento = $redis->hget('documentos:' . $chave, $mensagens['message']['text']);

			sendChatAction($mensagens['message']['chat']['id'], 'upload_document');

			if ($mensagens['message']['chat']['type'] == 'group' OR $mensagens['message']['chat']['type'] == 'supergroup') {
				sendDocument($mensagens['message']['chat']['id'], $documento,
										 $mensagens['message']['message_id'], $replyMarkup, '@' . DADOS_BOT['result']['username']);
			}

			sendDocument($mensagens['message']['from']['id'], $documento,
									 $mensagens['message']['message_id'], $replyMarkup, '@' . DADOS_BOT['result']['username']);
		}
	}

	if ($mensagens['message']['chat']['type'] == 'private' AND isset($mensagens['message']['document']['mime_type'])) {
		if (in_array($mensagens['message']['from']['id'], SUDOS)) {
			if (substr($mensagens['message']['document']['file_name'], -4) == '.apk' OR
					substr($mensagens['message']['document']['file_name'], -4) == '.obb') {
				$redis->hset('documentos:store', $mensagens['message']['document']['file_name'], $mensagens['message']['document']['file_id']);

				$mensagem = '<b> 📱 APK/OBB ADICIONADO 📱 </b>' . "\n\n" .
										'<b>Nome:</b> ' . $mensagens['message']['document']['file_name'] . "\n" .
											'<b>ID: </b>' . $mensagens['message']['document']['file_id'];

				notificarSudos($mensagem);
			}

			if (substr($mensagens['message']['document']['file_name'], -4) == '.pdf'	OR
					substr($mensagens['message']['document']['file_name'], -5) == '.epub'	OR
					substr($mensagens['message']['document']['file_name'], -5) == '.mobi'	) {
				$redis->hset('documentos:livros', $mensagens['message']['document']['file_name'], $mensagens['message']['document']['file_id']);

				$mensagem = '<b> 📱 LIVRO ADICIONADO 📱 </b>'																	 . "\n\n" .
										'<b>Nome:</b> ' . $mensagens['message']['document']['file_name'] . "\n"	 .
										'<b>ID: </b>'		. $mensagens['message']['document']['file_id'];

				notificarSudos($mensagem);
			}

			if (substr($mensagens['message']['document']['file_name'], -4) == '.mkv' OR
					substr($mensagens['message']['document']['file_name'], -4) == '.mp4' OR
					substr($mensagens['message']['document']['file_name'], -4) == '.avi' ) {
				$redis->hset('documentos:tv', $mensagens['message']['document']['file_name'], $mensagens['message']['document']['file_id']);

				$mensagem = '<b> 📱 VÍDEO ADICIONADO 📱 </b>'																	 . "\n\n" .
										'<b>Nome:</b> ' . $mensagens['message']['document']['file_name'] . "\n"	 .
										'<b>ID: </b>'		. $mensagens['message']['document']['file_id'];

				notificarSudos($mensagem);
			}
		}
	}
