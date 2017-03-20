<?php
	if (isset($texto[1])) {
		if (isset($mensagens['message']['chat']['id'])) {
					$chatID = $mensagens['message']['chat']['id'];
			$mensagemID = $mensagens['message']['message_id'];
		} else {
					$chatID = $mensagens['message']['reply_to_message']['chat']['id'];
			$mensagemID = $mensagens['message']['reply_to_message']['message_id'];
		}

		if ($mensagens['message']['chat']['type'] == 'private') {
			$origem = $mensagens['message']['from']['id'];
		} else {
			$origem = $mensagens['message']['from']['id'] . ' do grupo ' . $mensagens['message']['chat']['id'];
		}

		sendMessage(SUDOS[0], '📬 <b>Mensagem recebida de ' . $origem . ':</b>', null, null, true);

		forwardMessage(SUDOS[0], $chatID, $mensagemID);

		$mensagem = SUPORTE[$idioma]['ENVIADA'];
	} else {
		$mensagem = '📚: /' . SUPORTE[$idioma]['AJUDA'];
	}

	sendMessage($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'],
							null, true, $mensagens['edit_message']);
