<?php
	if (isset($texto[1])) {
		$mensagem = '<pre>' . hash('sha512', removerComando($texto[0], $mensagens['message']['text'])) . '</pre>';
	} else {
		$mensagem = '📚: /sha512 ' . DADOS_BOT['result']['first_name'];
	}

	sendMessage($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'], null, true);
