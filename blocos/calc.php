<?php
	if (isset($texto[1])) {
		$expressao = str_replace($texto[0] . ' ', '', $mensagens['message']['text']);
		$expressao = str_replace('x', '*', $expressao);
		$expressao = str_replace('X', '*', $expressao);
		$expressao = str_replace('÷', '/', $expressao);
		$expressao = str_replace(',', '.', $expressao);

		$mensagem = '<b>' . shell_exec('calc "' . $expressao . '"') . '</b>';
	} else {
		$mensagem = '📚: /calc 2+2';
	}

	sendMessage($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'], null, true);
