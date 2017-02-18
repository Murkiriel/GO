<?php
	if ($idioma == 'PT') {
		if (strtolower($texto[1]) == 'del' and isset($texto[2])) {
			if ($redis->exists('rastro:chats' . $mensagens['message']['from']['id'] . ':' . $texto[2]) === true) {
					$redis->del('rastro:chats' . $mensagens['message']['from']['id'] . ':' . $texto[2]);
					$redis->del('rastro:situacao:' . $mensagens['message']['from']['id'] . ':' . $texto[2]);

				$mensagem = 'Código de rastreio apagado.';
			} else {
				$mensagem = 'Códigos de rastreio não consta na sua lista';
			}
		} else if (strtolower($texto[1]) != 'del' and isset($texto[1])) {
			$codigo = strtoupper($texto[1]);

			$requisicao = 'http://127.0.0.1:3000/json/' . $codigo;
			 $resultado = json_decode(file_get_contents($requisicao, false, CONTEXTO), true);

			if (is_array($resultado)){
				$descricao = str_ireplace($texto[0] . ' ' . $texto[1] . ' ', '', $mensagens['message']['text']);

				if (empty($descricao)) {
					$descricao = 'Sem descrição';
				}

				$mensagem = '<b>' . $codigo . '</b> - ' . strip_tags($descricao) . "\n\n";

				foreach ($resultado as $dadosRastro) {
					$mensagem = $mensagem . '<b>Data:</b> ' . $dadosRastro['data'] . "\n" .
																	'<b>Local:</b> ' . $dadosRastro['local'] . "\n" .
																	'<b>Situação:</b> ' . $dadosRastro['situacao'] . "\n\n";
				}

				$mensagem = $mensagem . '<i>Você será notificado quando este status mudar</i>';

				$redis->setex('rastro:chats' . $mensagens['message']['from']['id'] . ':' . $codigo, 1814400, $descricao);
				$redis->setex('rastro:situacao:' . $mensagens['message']['from']['id'] . ':' . $codigo, 1814400, md5($mensagem));
			} else {
				$mensagem = 'Código de rastreio informado não é válido!';
			}
		} else {
			$mensagem = '<pre>📚 RASTRO</pre>' . "\n\n".
									'/rastro AA123456789BR <i>Meu novo celular*</i>' . "\n\n" .
									'/rastro del AA123456789BR - Deletar código da lista' . "\n\n" .
									'<i>*Descrição NÃO obrigatória</i>';

			$rastros = $redis->keys('rastro:chats' . $mensagens['message']['from']['id'] . '*');

			if (!empty($rastros)){
				$mensagem = $mensagem . "\n\n" . '<pre>+---------------+</pre>' . "\n\n" . '<b>Códigos em sua lista:</b>' . "\n\n";

				foreach ($rastros as $codigosUsuario) {
						 $codigo = str_ireplace('rastro:chats' . $mensagens['message']['from']['id'] . ':', '', $codigosUsuario);
					$descricao = $redis->get($codigosUsuario);
					 $mensagem = $mensagem . $codigo . ' -' . strip_tags($descricao) . "\n";
				}
			}
		}

		sendMessage($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'], null, true);
	}
