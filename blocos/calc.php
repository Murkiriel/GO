<?php
	$mensagem = '📚: /calc 2+2';

	if (isset($texto[1])) {
		$expressao = removerComando($texto[0], $mensagens['message']['text']);
		$expressao = str_ireplace('***', '*', $expressao);
		$expressao = str_ireplace('x', '*', $expressao);
		$expressao = str_ireplace('÷', '/', $expressao);
		$expressao = str_ireplace(',', '.', $expressao);

		$resultado = shell_exec('calc "' . $expressao . '"');

		if ($resultado == '\'\'') {
			$mensagem = '<b>' . $resultado . '</b>';
		}
	}

	sendMessage($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'],
							null, true, $mensagens['edit_message']);
