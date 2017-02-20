<?php
	if ($idioma == 'PT') {
		if (isset($texto[1]) and strtolower($texto[1]) == 'del' and isset($texto[2])) {
			if ($redis->exists('rastro:chats:' . $mensagens['message']['from']['id'] . ':' . $texto[2]) === true) {
				$redis->del('rastro:chats:' . $mensagens['message']['from']['id'] . ':' . $texto[2]);
				$redis->del('rastro:situacao:' . $mensagens['message']['from']['id'] . ':' . $texto[2]);

				$mensagem = 'Código de rastreio apagado.';
			} else {
				$mensagem = 'Código de rastreio não consta na sua lista ou não é válido.';
			}
		} else if (isset($texto[1]) and strtolower($texto[1]) != 'del') {
			$codigo = strtoupper($texto[1]);

			$requisicao = 'http://127.0.0.1:3000/json/' . $codigo;
			@$resultado = json_decode(file_get_contents($requisicao), true);

			if (is_array($resultado)) {
				$descricao = removerComando($texto[0], $mensagens['message']['text']);
				$descricao = str_ireplace($texto[1], '', $descricao);

				if (empty($descricao)) {
					$descricao = ' Sem descrição';
				}

				$mensagem = '<b>' . $codigo . '</b> -' . strip_tags($descricao) . "\n\n";

				foreach ($resultado as $dadosRastro) {
					$mensagem = $mensagem . '<b>Data:</b> ' . $dadosRastro['data'] . "\n" .
																	'<b>Local:</b> ' . $dadosRastro['local'] . "\n" .
																	'<b>Situação:</b> ' . $dadosRastro['situacao'] . "\n\n";
				}

				$mensagem = $mensagem . '<i>Você será notificado quando este status mudar</i>';

				$redis->setex('rastro:chats:' . $mensagens['message']['from']['id'] . ':' . $codigo, 1814400, $descricao);
				$redis->setex('rastro:situacao:' . $mensagens['message']['from']['id'] . ':' . $codigo, 1814400, md5($mensagem));
			} else {
				$mensagem = 'Código de rastreio informado não é válido!';
			}
		} else {
			$mensagem = '<pre>📚 RASTRO</pre>' . "\n\n".
									'/rastro AA123456789BR <i>Meu novo celular*</i>' . "\n\n" .
									'/rastro del AA123456789BR - Deletar código da lista' . "\n\n" .
									'<i>*Descrição NÃO obrigatória</i>';

			$rastros = $redis->keys('rastro:chats:' . $mensagens['message']['from']['id'] . '*');

			if (!empty($rastros)) {
				$mensagem = $mensagem . "\n\n" . '<pre>+---------------+</pre>' . "\n\n" . '<b>Códigos em sua lista:</b>' . "\n\n";

				foreach ($rastros as $rastro) {
					$codigo = str_ireplace('rastro:chats:' . $mensagens['message']['from']['id'] . ':', '', $rastro);
					$descricao = $redis->get($rastro);
					$mensagem = $mensagem . $codigo . ' -' . strip_tags($descricao) . "\n";
				}
			}
		}

		sendMessage($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'],
								null, true, $mensagens['edit_message']);
	}
