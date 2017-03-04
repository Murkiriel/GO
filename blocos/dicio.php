<?php
	if ($idioma == 'PT') {
		$chave = md5($mensagens['message']['text']);

		if ($redis->exists('dicio:' . $chave) === true) {
			$mensagem = $redis->get('dicio:' . $chave);
		} else if (isset($texto[1])) {
			$palavra = str_ireplace(' ', '-', removerComando($texto[0], $mensagens['message']['text']));

			$requisicao = 'http://dicionario-aberto.net/search-json/' . $palavra;

			try{
				@$resultado = json_decode(file_get_contents($requisicao), true);

				if (!empty($resultado['entry']['form']['orth']) and !empty($resultado['entry']['sense'][0]['def'])) {
					$mensagem = '<b>' . $resultado['entry']['form']['orth'] . ':</b> ' .
											str_ireplace('_', '', str_ireplace('<br/>', ' ', $resultado['entry']['sense'][0]['def']));
				}
			} catch(Exception $e){
				echo 'Exceção capturada: ',  $e->getMessage(), "\n";
			}

			if (empty($mensagem)) {
				$mensagem = ERROS[$idioma]['SEM_RSULT'];
			}

			$redis->setex('dicio:' . $chave, 3600, $mensagem);
		} else {
			$mensagem = '📚: /dicio Capitão';
		}

		sendMessage($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'],
								null, true, $mensagens['edit_message']);
	}
