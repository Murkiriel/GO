<?php
	if ($mensagens['message']['chat']['type'] == 'group' or $mensagens['message']['chat']['type'] == 'supergroup') {
		$resultado = getChatAdministrators($mensagens['message']['chat']['id']);
		 $mensagem = '';

		foreach ($resultado['result'] as $adminsGrupo) {
			if ($adminsGrupo['status'] == 'creator') {
				$mensagem = '👤 ' . $adminsGrupo['user']['first_name'] . "\n\n" . $mensagem;
			} else {
				$mensagem = $mensagem . '👥 ' . $adminsGrupo['user']['first_name'] . "\n";
			}
		}
	} else if ($mensagens['message']['chat']['type'] == 'private') {
		$mensagem = ERROS[$idioma]['SMT_GRUPO'];
	}

	sendMessage($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'], null, false);
