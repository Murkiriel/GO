<?php
	if ($mensagens['message']['chat']['type'] == 'group' or $mensagens['message']['chat']['type'] == 'supergroup') {
		$resultado = getChatAdministrators($mensagens['message']['chat']['id']);
		$mensagem = '';

		foreach ($resultado['result'] as $adminsGrupo) {
			if ($adminsGrupo['status'] == 'creator') {
			 if (isset($adminsGrupo['user']['username'])) {
				 $mensagem = '👤 <a href="t.me/' . $adminsGrupo['user']['username'] . '">' .
				 						 strip_tags($adminsGrupo['user']['first_name']) . '</a> <b>(' . ADMS[$idioma] . ')</b>' . "\n\n" . $mensagem;
			 } else {
				 $mensagem = '👤 ' . strip_tags($adminsGrupo['user']['first_name']) . "\n\n" . $mensagem;
			 }
		 } else {
			 if (isset($adminsGrupo['user']['username'])) {
				 $mensagem = $mensagem . '👥 <a href="t.me/' . $adminsGrupo['user']['username'] . '">' .
				 						 strip_tags($adminsGrupo['user']['first_name']) . '</a>' . "\n";
			 } else {
				 $mensagem = $mensagem . '👥 ' . strip_tags($adminsGrupo['user']['first_name']) . "\n";
			 }
		 }
	 }
	} else if ($mensagens['message']['chat']['type'] == 'private') {
		$mensagem = ERROS[$idioma]['SMT_GRUPO'];
	}

	sendMessage($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'],
	 						null, true, $mensagens['edit_message']);
