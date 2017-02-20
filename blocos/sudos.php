<?php
	if (in_array($mensagens['message']['from']['id'], SUDOS)) {
		if (strtolower($texto[0]) == 'sudos') {
			$mensagem = '<pre>COMANDOS SUDOS</pre>' . "\n\n" .
									'/promover - Enviar divulgação para usuários' . "\n" .
									'/postagem - Enviar divulgação para usuários, grupos e canais' . "\n" .
									'/reiniciar - Reiniciar bot' . "\n" .
									'/removerdocumento - Remover documento' . "\n" .
									'/status - Ver status';

			sendMessage($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'], null, true);
		} else if (strtolower($texto[0]) == 'promover') {
			if (isset($texto[1])) {
				$mensagensEnviadas = 0;

				$textoPromocao = removerComando($texto[0], $mensagens['message']['text']);

				$resultado = sendMessage($mensagens['message']['chat']['id'], $textoPromocao, null, null, true);

				if ($resultado['ok'] === true) {
					foreach ($redis->keys('idioma:*') as $hash) {
						$chatID = floatval(str_ireplace('idioma:', '', $hash));

						if ($chatID>0) {
							$resultado = sendMessage($chatID, $textoPromocao, null, null, true);

							if ($resultado['ok'] === true) {
								++$mensagensEnviadas;
							}

							if ($mensagensEnviadas%30 == 0) {
								sleep(1);
							}
						}
					}

					$mensagem = '<b>Promoção finalizada!</b>' . "\n" . 'Foram enviadas ' . $mensagensEnviadas . ' mensagens.';
				} else if ($resultado['ok'] === false) {
					$mensagem = json_encode($resultado);
				}
			} else if (isset($mensagens['message']['reply_to_message'])) {
				$mensagensEnviadas = 0;

				forwardMessage($mensagens['message']['chat']['id'], $mensagens['message']['reply_to_message']['chat']['id'],
																							 							$mensagens['message']['reply_to_message']['message_id']);

				foreach ($redis->keys('idioma:*') as $hash) {
					$chatID = floatval(str_ireplace('idioma:', '', $hash));

					if ($chatID>0) {
						$resultado = forwardMessage($chatID, $mensagens['message']['reply_to_message']['chat']['id'],
																									 $mensagens['message']['reply_to_message']['message_id']);
						if ($resultado['ok'] === true) {
							++$mensagensEnviadas;
						}

						if ($mensagensEnviadas%30 == 0) {
							sleep(1);
						}
					}
				}

				$mensagem = '<b>Promoção finalizada!</b>' . "\n" . 'Foram encaminhadas ' . $mensagensEnviadas . ' mensagens.';
			} else {
				$mensagem = '📚: /promover Telegram > WhatsApp' . "\n\n" . 'Responder mensagem com /promover';
			}

			sendMessage($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'], null, true);
		} else if (strtolower($texto[0]) == 'postagem') {
			if (isset($texto[1])) {
				$mensagensEnviadas = 0;

				$textoPostagem = removerComando($texto[0], $mensagens['message']['text']);

				$resultado = sendMessage($mensagens['message']['chat']['id'], $textoPostagem, null, null, true);

				if ($resultado['ok'] === true) {
					foreach ($redis->keys('canais:*') as $hash) {
						$chatID = floatval(str_ireplace('canais:', '', $hash));

						$resultado = sendMessage($chatID, $textoPostagem, null, null, true);

						if ($resultado['ok'] === true) {
							++$mensagensEnviadas;
						}

						if ($mensagensEnviadas%30 == 0) {
							sleep(1);
						}
					}

					foreach ($redis->keys('idioma:*') as $hash) {
						$chatID = floatval(str_ireplace('idioma:', '', $hash));

						$resultado = sendMessage($chatID, $textoPostagem, null, null, true);

						if ($resultado['ok'] === true) {
							++$mensagensEnviadas;
						}

						if ($mensagensEnviadas%30 == 0) {
							sleep(1);
						}
					}

					$mensagem = '<b>Postagem finalizada!</b>' . "\n" . 'Foram enviadas ' . $mensagensEnviadas . ' mensagens.';
				} else if ($resultado['ok'] === false) {
					$mensagem = json_encode($resultado);
				}
			} else if (isset($mensagens['message']['reply_to_message'])) {
				$mensagensEnviadas = 0;

				forwardMessage($mensagens['message']['chat']['id'], $mensagens['message']['reply_to_message']['chat']['id'],
																							 							$mensagens['message']['reply_to_message']['message_id']);

				foreach ($redis->keys('canais:*') as $hash) {
					$chatID = floatval(str_ireplace('canais:', '', $hash));

					$resultado = forwardMessage($chatID, $mensagens['message']['reply_to_message']['chat']['id'],
																								 $mensagens['message']['reply_to_message']['message_id']);
					if ($resultado['ok'] === true) {
						++$mensagensEnviadas;
					}

					if ($mensagensEnviadas%30 == 0) {
						sleep(1);
					}
				}

				foreach ($redis->keys('idioma:*') as $hash) {
					$chatID = floatval(str_ireplace('idioma:', '', $hash));

					$resultado = forwardMessage($chatID, $mensagens['message']['reply_to_message']['chat']['id'],
																									 $mensagens['message']['reply_to_message']['message_id']);
					if ($resultado['ok'] === true) {
						++$mensagensEnviadas;
					}

					if ($mensagensEnviadas%30 == 0) {
						sleep(1);
					}
				}

				$mensagem = '<b>Postagem finalizada!</b>' . "\n" . 'Foram encaminhadas ' . $mensagensEnviadas . ' mensagens.';
			} else {
				$mensagem = '📚: /postagem Telegram > WhatsApp' . "\n\n" . 'Responder mensagem com /postagem';
			}

			sendMessage($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'], null, true);
		} else if (strtolower($texto[0]) == 'reiniciar') {
			$redis->set('status_bot:loop', 'false');

			notificarSudos('<pre>Reiniciando...</pre>');

			echo "\n\n";
			echo '+-------------+' , "\n";
			echo '| REINICIANDO |' , "\n";
			echo '+-------------+' , "\n\n";
		} else if (strtolower($texto[0]) == 'removerdocumento') {
			$documentoRemovido = false;

			if ($mensagens['message']['chat']['type'] != 'private') {
				$mensagem = 'Apenas no <b>privado!</b>';
			} else if (isset($texto[1])) {
				$nomeDocumento = removerComando($texto[0], $mensagens['message']['text']);

				foreach ($redis->keys('documentos:*') as $hash) {
					if ($redis->hexists($hash, $nomeDocumento) === true) {
						$idDocumento = $redis->hget($hash, $nomeDocumento);
						$redis->hdel($hash, $nomeDocumento);

						$documentoRemovido = true;

						$teclado['hide_keyboard'] = true;

						$replyMarkup = json_encode($teclado);

						$mensagem = '<b> 📱 DOCUMENTO REMOVIDO 📱 </b>' . "\n\n" .
												'<b>Nome:</b> ' . $nomeDocumento . "\n" .
													'<b>ID: </b>' . $idDocumento;

						notificarSudos($mensagem);
					}
				}

				if ($documentoRemovido === false) {
					$mensagem = '<b>' . $nomeDocumento . '</b> não existe na lista!';
				}
			} else {
				$mensagem = '📚: /removerdocumento WhatsApp.apk';
			}

			if ($documentoRemovido === false) {
				sendMessage($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'], null, true);
			}
		} else if (strtolower($texto[0]) == 'status') {
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

			sendMessage($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'], null, true);
		}
	}
