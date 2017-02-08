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
			sendMessage($sudo, '📬 <b>Mensagem recebida:</b>', NULL, NULL, TRUE);
			forwardMessage($sudo, $chatID, $mensagemID);
			$mensagem = '📬 <b>Mensagem enviada!</b>';
		}
	} else {
		$mensagem = '📚: /' . SUPORTE[$idioma];
	}

	sendMessage($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'], NULL, TRUE);
