<?php
	if(			in_array($mensagens['message']['from']['id'], SUDOS)){
		if(	strcasecmp($mensagens['message']['text'], '/sudos')																					== 0 OR
				strcasecmp($mensagens['message']['text'], '/sudos' . '@' . DADOS_BOT['result']['username']) == 0 ){

			$mensagem = '<pre>COMANDOS SUDOS</pre>'							. "\n\n" .
									'/promover - Promover texto'						. "\n" .
									'/reiniciar - Reiniciar bot'						. "\n" .
									'/removerdocumento - Remover documento' . "\n" .
									'/status - Ver status';

			sendMessage($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'], null, true);
		}

		if(	strcasecmp($texto[0], '/promover')																														 == 0 OR
				strcasecmp($mensagens['message']['text'], '/promover' . '@' . DADOS_BOT['result']['username']) == 0 ){
			if(isset($texto[1])){
				$textoDivulgacao = str_ireplace($texto[0], '', $mensagens['message']['text']);

				if(isset($dadosIdioma)){
					 $indices = array_keys($dadosIdioma);

					 $mensagensEnviadas = 0;

					foreach($indices as $usuarioID){
						if($usuarioID > 0){
							$resposta = sendMessage($usuarioID, $textoDivulgacao, null, null, true);

							if($resposta['ok'] == true){
								++$mensagensEnviadas;
							}
						}
					}

					$mensagem = '<b>Promoção finalizada!</b>' . "\n" . 'Foram enviadas ' . $mensagensEnviadas . ' mensagens.';
				}
			}
			else if(isset($mensagens['message']['reply_to_message'])){
				if(isset($dadosIdioma)){
					 $indices = array_keys($dadosIdioma);

					 $mensagensEnviadas = 0;

					foreach($indices as $usuarioID){
						if($usuarioID > 0){
							$resposta = forwardMessage($usuarioID, $mensagens['message']['reply_to_message']['chat']['id'],
																				 						 $mensagens['message']['reply_to_message']['message_id'], false);
							if($resposta['ok'] == true){
								++$mensagensEnviadas;
							}
						}
					}

					$mensagem = '<b>Promoção finalizada!</b>' . "\n" . 'Foram enviadas ' . $mensagensEnviadas . ' mensagens.';
				}
			}
			else{
				$mensagem = '📚: /promover Telegram > WhatsApp' . "\n\n" . 'Responder mensagem com /promover';
			}

			sendMessage($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'], null, true);
		}

		if(	strcasecmp($mensagens['message']['text'], '/reiniciar')																					== 0 OR
				strcasecmp($mensagens['message']['text'], '/reiniciar' . '@' . DADOS_BOT['result']['username']) == 0 ){
			echo '🔥  -> CACHE LIMPO!';
			echo "\n\n";
			echo '+-------------+' . "\n";
			echo '| REINICIANDO |' . "\n";
			echo '+-------------+' . "\n\n";

			system('rm -rf ' . CACHE_PASTA . '*');

					$loop = false;
			$mensagem = '<pre>Reiniciando...</pre>';

			sendMessage($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'], null, true);
		}

		if(	strcasecmp($mensagens['message']['text'], '/status')																					== 0 OR
				strcasecmp($mensagens['message']['text'], '/status' . '@' . DADOS_BOT['result']['username'])	== 0 ){
			$usuarios = 0;
				$grupos = 0;

			if(isset($dadosIdioma)){
				$indices = array_keys($dadosIdioma);

				foreach($indices as $valor){
					if($valor < 0){
						++$grupos;
					}
					else{
						++$usuarios;
					}
				}
			}

			if(isset($dadosRanking['QM'])){
				$qntdMensagens = $dadosRanking['QM'];
			}
			else{
				$qntdMensagens = 0;
			}

			$mensagem = '<pre>STATUS DO ' . strtoupper(DADOS_BOT['result']['first_name']) . '</pre>'		. "\n\n" .
									'<b>Versão:</b> '		 . VERSAO																										. "\n\n" .
									'<b>Grupos:</b> '		 . $grupos																									. "\n\n" .
									'<b>Usuários:</b> '	 . $usuarios																								. "\n\n" .
									'<b>Msg / Seg:</b> ' . number_format($qntdMensagens/60, 3, ',', '.') . ' m/s';

			sendMessage($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'], null, true);
		}

		if(	strcasecmp($texto[0], '/removerdocumento')																														 == 0 OR
				strcasecmp($mensagens['message']['text'], '/removerdocumento' . '@' . DADOS_BOT['result']['username']) == 0 ){
			$documentoRemovido = false;

			if(isset($texto[1])){
				$nomeDocumento = substr(str_ireplace($texto[0], '', $mensagens['message']['text']), 1);

				$bancos = array(
					0 => 'store',
					1 => 'livros'
				);

				foreach($bancos as $bancosArquivos){
					$dados = carregarDados(RAIZ . 'dados/' . $bancosArquivos . '.json');

					if(isset($dados[$nomeDocumento])){
						$idDocumento = $dados[$nomeDocumento]['arquivo_id'];
						unset($dados[$nomeDocumento]);

						$documentoRemovido = true;

						$teclado = array(
							'hide_keyboard' => true
						);

						$replyMarkup = json_encode($teclado);

						salvarDados(RAIZ . 'dados/' . $bancosArquivos . '.json', $dados);

						foreach(SUDOS as $sudo){
							$mensagem = '<b> 📱 DOCUMENTO REMOVIDO 📱 </b>'																. "\n\n" .
												'<b>Nome:</b> ' . $nomeDocumento . "\n"	 .
												'<b>ID: </b>'		. $idDocumento;

							sendMessage($sudo,$mensagem, null, null, true);
						}
					}
				}

				if($documentoRemovido == false){
					$mensagem = '<b>' . $nomeDocumento . '</b> não existe na lista!';
				}
			}
			else{
				$mensagem = '📚: /removerdocumento WhatsApp.apk';
			}

			if($documentoRemovido == false){
				sendMessage($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'], null, true);
			}
		}
	}
