<?php
	if (in_array($mensagens['message']['from']['id'], SUDOS)) {
		if (strcasecmp($mensagens['message']['text'], '/sudos') == 0) {
			$mensagem = '<pre>COMANDOS SUDOS</pre>' . "\n\n" .
									'/promover - Promover texto' . "\n" .
									'/reiniciar - Reiniciar bot' . "\n" .
									'/removerdocumento - Remover documento' . "\n" .
									'/status - Ver status';

			sendMessage($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'], NULL, TRUE);
		} else if (strcasecmp($texto[0], '/promover') == 0) {
			if (isset($texto[1])) {
				$mensagensEnviadas = 0;
					$textoDivulgacao = str_ireplace($texto[0], '', $mensagens['message']['text']);

				foreach ($redis->keys('idioma:*') as $chatID) {
					$chatID = floatval(str_ireplace('idioma:', '', $chatID));

					if ($chatID>0) {
						$resultado = sendMessage($chatID, $textoDivulgacao, NULL, NULL, TRUE);

						if ($resultado['ok'] === TRUE) {
							++$mensagensEnviadas;
						}

						if ($mensagensEnviadas%30 == 0) {
							sleep(1);
						}
					}
				}

				$mensagem = '<b>Promoção finalizada!</b>' . "\n" . 'Foram enviadas ' . $mensagensEnviadas . ' mensagens.';
			} else if (isset($mensagens['message']['reply_to_message'])) {
				$mensagensEnviadas = 0;

				foreach ($redis->keys('idioma:*') as $chatID) {
					$chatID = floatval(str_ireplace('idioma:', '', $chatID));

					if ($chatID>0) {
						$resultado = forwardMessage($chatID, $mensagens['message']['reply_to_message']['chat']['id'],
																								 $mensagens['message']['reply_to_message']['message_id']);
						if ($resultado['ok'] === TRUE) {
							++$mensagensEnviadas;
						}
					}
				}

				$mensagem = '<b>Promoção finalizada!</b>' . "\n" . 'Foram encaminhadas ' . $mensagensEnviadas . ' mensagens.';
				} else {
				$mensagem = '📚: /promover Telegram > WhatsApp' . "\n\n" . 'Responder mensagem com /promover';
			}

			sendMessage($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'], NULL, TRUE);
		} else if (strcasecmp($texto[0], '/reiniciar') == 0) {
			$redis->set('status_bot:loop', 'FALSE');

			notificarSudos('<pre>Reiniciando...</pre>');

			echo "\n\n";
			echo '+-------------+' . "\n";
			echo '| REINICIANDO |' . "\n";
			echo '+-------------+' . "\n\n";
		} else if (strcasecmp($texto[0], '/removerdocumento') == 0) {
			$documentoRemovido = FALSE;

			if ($mensagens['message']['chat']['type'] != 'private') {
				$mensagem = 'Apenas no <b>privado!</b>';
			} else if (isset($texto[1])) {
				$nomeDocumento = substr(str_ireplace($texto[0], '', $mensagens['message']['text']), 1);

				$chavesLista = array(
					0 => 'store',
					1 => 'livros',
					2 => 'tv',
					3 => 'psp',
					4 => 'snes'
				);

				foreach ($chavesLista as $chave) {
					if ($redis->hexists('documentos:' . $chave, $nomeDocumento)) {
						$idDocumento = $redis->hget('documentos:' . $chave, $nomeDocumento);
						$redis->hdel('documentos:' . $chave, $nomeDocumento);

						$documentoRemovido = TRUE;

						$teclado = array(
							'hide_keyboard' => TRUE
						);

						$replyMarkup = json_encode($teclado);

						$mensagem = '<b> 📱 DOCUMENTO REMOVIDO 📱 </b>' . "\n\n" .
												'<b>Nome:</b> ' . $nomeDocumento . "\n" .
													'<b>ID: </b>' . $idDocumento;

						notificarSudos($mensagem);
					}
				}

				if ($documentoRemovido === FALSE) {
					$mensagem = '<b>' . $nomeDocumento . '</b> não existe na lista!';
				}
			} else {
				$mensagem = '📚: /removerdocumento WhatsApp.apk';
			}

			if ($documentoRemovido === FALSE) {
				sendMessage($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'], NULL, TRUE);
			}
		} else if (strcasecmp($texto[0], '/status') == 0) {
				 $grupos = count($redis->keys('idioma:-*'));
			 $usuarios = count($redis->keys('idioma:*')) - $grupos;
			$atendidas = count($redis->keys('status_bot:msg_atendidas:*'));
					$total = $redis->get('status_bot:privateorgroup') + $redis->get('status_bot:supergroup');

			$mensagem = '<pre>STATUS DO ' . strtoupper(DADOS_BOT['result']['first_name']) . '</pre>' . "\n\n" .
									'<b>Versão:</b> ' . VERSAO . "\n\n" .
									'<b>Grupos:</b> ' . $grupos . "\n\n" .
									'<b>Usuários:</b> ' . $usuarios . "\n\n" .
									'<b>Mensagens:</b> ' . number_format($total, 0, ',', '.') . "\n\n" .
									'<b>Msg / Seg:</b> ' . number_format($atendidas/60, 3, ',', '.') . ' m/s';

			sendMessage($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'], NULL, TRUE);
		}
	}
