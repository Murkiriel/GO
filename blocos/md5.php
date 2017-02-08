<?php
	if (isset($texto[1])) {
		$mensagem = '<pre>' . md5(str_ireplace($texto[0] . ' ', '', $mensagens['message']['text'])) . '</pre>';
	} else {
		$mensagem = '📚: /md5 ' . DADOS_BOT['result']['first_name'];
	}

	sendMessage($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'], NULL, TRUE);
