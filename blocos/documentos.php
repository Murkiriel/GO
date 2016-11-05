<?php
	$bancos = array(
		0 => 'store',
		1 => 'livros'
	);

	foreach($bancos as $bancosArquivos){
		$dadosDocumentos = carregarDados(RAIZ . 'dados/' . $bancosArquivos . '.json');

		$nomeDocumento = $mensagens['message']['text'];

		if(isset($dadosDocumentos[$nomeDocumento]['arquivo_id'])){
			$teclado = array(
				'hide_keyboard' => true
			);

			$replyMarkup = json_encode($teclado);

			$document = $dadosDocumentos[$nomeDocumento]['arquivo_id'];

			sendChatAction($mensagens['message']['chat']['id'], 'upload_document');

			if($mensagens['message']['chat']['type'] == 'group' OR $mensagens['message']['chat']['type'] == 'supergroup'){
				sendDocument($mensagens['message']['chat']['id'], $document,
										 $mensagens['message']['message_id'], $replyMarkup, '@' . DADOS_BOT['result']['username']);
			}

			sendDocument($mensagens['message']['from']['id'], $document,
									 $mensagens['message']['message_id'], $replyMarkup, '@' . DADOS_BOT['result']['username']);
		}
	}

	if($mensagens['message']['chat']['type'] == 'private' AND isset($mensagens['message']['document']['mime_type'])){
		if(in_array($mensagens['message']['from']['id'], SUDOS)){
			if(substr($mensagens['message']['document']['file_name'], -4) == '.apk'	OR
				 substr($mensagens['message']['document']['file_name'], -4) == '.obb'	){
				$dadosDocumentos = carregarDados(RAIZ . 'dados/store.json');

				$dadosDocumentos[$mensagens['message']['document']['file_name']] = array(
				 'arquivo_id' => $mensagens['message']['document']['file_id']
				);

				salvarDados(RAIZ . 'dados/store.json', $dadosDocumentos);

				foreach(SUDOS as $sudo){
					$mensagem = '<b> 📱 APK/OBB ADICIONADO 📱 </b>'																	. "\n\n" .
											'<b>Nome:</b> ' . $mensagens['message']['document']['file_name']	. "\n" .
											'<b>ID: </b>'		. $mensagens['message']['document']['file_id'];

					sendMessage($sudo,$mensagem, null, null, true);
				}
			}

			if(substr($mensagens['message']['document']['file_name'], -4) == '.pdf'		OR
				 substr($mensagens['message']['document']['file_name'], -5) == '.epub'	OR
				 substr($mensagens['message']['document']['file_name'], -5) == '.mobi'	){

				$dadosDocumentos = carregarDados(RAIZ . 'dados/livros.json');

				$dadosDocumentos[$mensagens['message']['document']['file_name']] = array(
				 'arquivo_id' => $mensagens['message']['document']['file_id']
				);

				salvarDados(RAIZ . 'dados/livros.json', $dadosDocumentos);

				foreach(SUDOS as $sudo){
					$mensagem = '<b> 📱 LIVRO ADICIONADO 📱 </b>'																	 . "\n\n" .
											'<b>Nome:</b> ' . $mensagens['message']['document']['file_name'] . "\n"	 .
											'<b>ID: </b>'		. $mensagens['message']['document']['file_id'];

					sendMessage($sudo,$mensagem, null, null, true);
				}
			}
		}
	};
