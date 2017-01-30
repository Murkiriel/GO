<?php
	if (isset($mensagens['message']['reply_to_message'])) {
		$mensagens['message'] = $mensagens['message']['reply_to_message'];
		unset($mensagens['message']['reply_to_message']);
	}

	$mensagem = ID[$idioma]['NOME'] . ': ' . $mensagens['message']['from']['first_name'];

	if (isset($mensagens['message']['from']['username'])) {
		$mensagem = $mensagem . ' ( @' . $mensagens['message']['from']['username'] . ' )' . "\n" .
									 'ID: ' . $mensagens['message']['from']['id'];
	} else {
		$mensagem = $mensagem . "\n" . 'ID: ' . $mensagens['message']['from']['id'];
	}

	if ($mensagens['message']['chat']['type'] == 'group' OR
			$mensagens['message']['chat']['type'] == 'supergroup' OR
			$mensagens['message']['chat']['type'] == 'private') {
		if ($mensagens['message']['chat']['type'] != 'private') {
			$mensagem = $mensagem . "\n\n" . 'Chat: ' . $mensagens['message']['chat']['title'] .
																			 ' (ID: ' . $mensagens['message']['chat']['id'] . ')';
		}

		if ($mensagens['message']['from']['id'] != DADOS_BOT['result']['id']) {
			$qntdMensagem = $redis->hget('ranking:' . $mensagens['message']['chat']['id'] . ':' . $mensagens['message']['from']['id'], 'qntd_mensagem');
		} else {
			$qntdMensagem = '10^100';
		}

		$mensagem = $mensagem . "\n" . ID[$idioma]['MSGS'] . ': ' . $qntdMensagem;
	}

	$resultado = getUserProfilePhotos($mensagens['message']['from']['id']);

	if (isset($resultado['result']['photos'][0][0]['file_id'])) {
		sendPhoto($mensagens['message']['chat']['id'], $resultado['result']['photos'][0][0]['file_id'], $mensagens['message']['message_id'], NULL, $mensagem);
	} else {
		sendMessage($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id']);
	}
