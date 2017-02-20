<?php
	if (isset($mensagens['message']['reply_to_message'])) {
		$mensagens['message'] = $mensagens['message']['reply_to_message'];
		unset($mensagens['message']['reply_to_message']);
	}

	$mensagem = ID[$idioma]['NOME'] . ': ' . $mensagens['message']['from']['first_name'];

	if (isset($mensagens['message']['from']['username'])) {
		$mensagem = $mensagem . ' ( @' . $mensagens['message']['from']['username'] . ' )';
	}

	$mensagem = $mensagem . "\n" . 'ID: ' . $mensagens['message']['from']['id'];

	if ($mensagens['message']['chat']['type'] == 'group' or
			$mensagens['message']['chat']['type'] == 'supergroup' or
			$mensagens['message']['chat']['type'] == 'private') {
		if ($mensagens['message']['chat']['type'] != 'private') {
			$mensagem = $mensagem . "\n\n" . 'Chat: ' . $mensagens['message']['chat']['title'] .
																			 ' (ID: ' . $mensagens['message']['chat']['id'] . ')';
		}

		$qntdMensagem = $mensagens['message']['from']['id'] != DADOS_BOT['result']['id'] ?
			$redis->hget('ranking:' . $mensagens['message']['chat']['id'] . ':' .
			$mensagens['message']['from']['id'], 'qntd_mensagem') : '10^100';

		$mensagem = $mensagem . "\n" . ID[$idioma]['MSGS'] . ': ' . $qntdMensagem;
	}

	if ($mensagens['edit_message'] === true) {
		sendMessage($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'],
								null, null, $mensagens['edit_message']);
	} else {

		$resultado = getUserProfilePhotos($mensagens['message']['from']['id']);

		isset($resultado['result']['photos'][0][0]['file_id']) ?
			sendPhoto($mensagens['message']['chat']['id'], $resultado['result']['photos'][0][0]['file_id'],
								$mensagens['message']['message_id'], null, $mensagem) :
			sendMessage($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'], null, null);
	}
