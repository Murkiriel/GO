<?php
	if(	strcasecmp($mensagens['message']['text'], '/sudos')																					== 0	OR
			strcasecmp($mensagens['message']['text'], '/sudos' . '@' . $dadosBot['result']['username'])	== 0	){

		$mensagem = '<pre>COMANDOS SUDOS</pre>'		. "\n\n" .
								'/reiniciar - Reiniciar bot'	. "\n"	 .
								'/status - Ver status do bot';

		sendMessage($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'], null, true);
	}

	if(	strcasecmp($mensagens['message']['text'], '/status')																					== 0	OR
			strcasecmp($mensagens['message']['text'], '/status' . '@' . $dadosBot['result']['username'])	== 0	){
			$dados = carregarDados(RAIZ . '/dados/idioma.json');
		$indices = array_keys($dados);

			$grupos = 0;
		$usuarios	= 0;

		foreach($indices as $valor){
			if($valor < 0){
				++$grupos;
			}
			else{
				++$usuarios;
			}
		}

		$dados = carregarDados(RAIZ . 'dados/ranking.json');

		 $totalMensagens = $dados['SG'] + $dados['PG'];
		$mensagensMinuto = $dados['MM'];

		$mensagem = '<pre>STATUS DO ' . strtoupper($dadosBot['result']['first_name']) . '</pre>' . "\n\n" .
								'<b>Versão:</b> '		 . VERSAO						. "\n\n" .
								'<b>Grupos:</b> '		 . $grupos					. "\n"	 .
								'<b>Usuários:</b> '	 . $usuarios				. "\n\n" .
								'<b>Msg / Min:</b> ' . $mensagensMinuto . ' ou ' . number_format($mensagensMinuto/60, 3, ',', '.') . ' m/s' . "\n" .
								'<b>Mensagens:</b> ' . $totalMensagens;

		sendMessage($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'], null, true);
	}
