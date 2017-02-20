<?php
	if (isset($texto[1])) {
		if (isset($mensagens['message']['chat']['id'])) {
					$chatID = $mensagens['message']['chat']['id'];
			$mensagemID = $mensagens['message']['message_id'];
		} else {
					$chatID = $mensagens['message']['reply_to_message']['chat']['id'];
			$mensagemID = $mensagens['message']['reply_to_message']['message_id'];
		}

		foreach (SUDOS as $sudo) {
			sendMessage($sudo, '📬 <b>Mensagem recebida:</b>', null, null, true);
			forwardMessage($sudo, $chatID, $mensagemID);
			$mensagem = SUPORTE[$idioma]['ENVIADA'];
		}
	} else {
		$mensagem = '📚: /' . SUPORTE[$idioma]['AJUDA'];
	}

	sendMessage($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'], null, true);
