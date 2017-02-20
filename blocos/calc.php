<?php
	if (isset($texto[1])) {
		$expressao = removerComando($texto[0], $mensagens['message']['text']);
		$expressao = str_ireplace('***', '*', $expressao);
		$expressao = str_ireplace('x', '*', $expressao);
		$expressao = str_ireplace('÷', '/', $expressao);
		$expressao = str_ireplace(',', '.', $expressao);

		$mensagem = '<b>' . shell_exec('calc "' . $expressao . '"') . '</b>';
	} else {
		$mensagem = '📚: /calc 2+2';
	}

	sendMessage($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'], null, true);
