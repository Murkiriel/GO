<?php
	if(isset($texto[1])){
		$expressao = str_replace($texto[0] . ' ', '', $mensagens['message']['text']);
		$expressao = str_replace('x', '*', $expressao);
		$expressao = str_replace('X', '*', $expressao);
		$expressao = str_replace('÷', '/', $expressao);
		$expressao = str_replace(',', '.', $expressao);

		sendChatAction($mensagens['message']['chat']['id'], 'typing');

		$mensagem = '<b>' . file_get_contents('http://api.mathjs.org/v1/?expr=' . urlencode($expressao), false, CONTEXTO) . '</b>';
	}
	else{
		$mensagem = '<b>Ex.:</b> /calc 2+2';
	}

	sendMessage($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'], null, true);
