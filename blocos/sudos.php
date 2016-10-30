<?php
	if(	strcasecmp($mensagens['message']['text'], '/sudos')																					== 0	OR
			strcasecmp($mensagens['message']['text'], '/sudos' . '@' . DADOS_BOT['result']['username'])	== 0	){

		$mensagem = '<pre>COMANDOS SUDOS</pre>'		. "\n\n" .
								'/reiniciar - Reiniciar bot'	. "\n"	 .
								'/status - Ver status do bot';

		sendMessage($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'], null, true);
	}

	if(	strcasecmp($mensagens['message']['text'], '/reiniciar') == 0 OR
			strcasecmp($mensagens['message']['text'], '/reiniciar' . '@' . DADOS_BOT['result']['username']) == 0){
		echo '🔥  -> CACHE LIMPO!';
		echo "\n\n";
		echo '+-------------+' . "\n";
		echo '| REINICIANDO |' . "\n";
		echo '+-------------+' . "\n\n";

		system('rm -rf ' . CACHE_PASTA . '*');

		$loop = false;

		$mensagem = '<pre>Reiniciando...</pre>';

		sendMessage($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'], null, true);
	}

	if(	strcasecmp($mensagens['message']['text'], '/status')																					== 0	OR
			strcasecmp($mensagens['message']['text'], '/status' . '@' . DADOS_BOT['result']['username'])	== 0	){
				 $dados = carregarDados(RAIZ . '/dados/idioma.json');
				$grupos = 0;
			$usuarios	= 0;

			if(isset($dados)){
				 $indices = array_keys($dados);

				foreach($indices as $valor){
					if($valor < 0){
						++$grupos;
					}
					else{
						++$usuarios;
					}
				}
			}

							$dados = carregarDados(RAIZ . 'dados/ranking.json');
		 $totalMensagens = 0;
		$mensagensMinuto = 0;

		if(isset($dados)){
			 $totalMensagens = $dados['SG'] + $dados['PG'];
			$mensagensMinuto = $dados['MM'];
		}

		$mensagem = '<pre>STATUS DO ' . strtoupper(DADOS_BOT['result']['first_name']) . '</pre>' . "\n\n" .
								'<b>Versão:</b> '		 . VERSAO						. "\n\n" .
								'<b>Grupos:</b> '		 . $grupos					. "\n"	 .
								'<b>Usuários:</b> '	 . $usuarios				. "\n\n" .
								'<b>Msg / Min:</b> ' . $mensagensMinuto . ' ou ' . number_format($mensagensMinuto/60, 3, ',', '.') . ' m/s' . "\n" .
								'<b>Mensagens:</b> ' . $totalMensagens;

		sendMessage($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'], null, true);
	}
