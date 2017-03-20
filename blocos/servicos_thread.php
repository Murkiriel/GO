<?php
	$redis = conectarRedis();

	while (true) {
		$minuto = date('i');

		// # RASTRO

		if ($minuto%5 == 0) {
			if ($redis->exists('rastro:atualizando') === false) {
				$redis->setex('rastro:atualizando', 60, 'true');

				foreach ($redis->keys('rastro:situacao:*') as $hash) {
					$situacao = $redis->get($hash);
						$codigo = substr(strrchr($hash, ':'), 1);

					$requisicao = 'http://127.0.0.1:3000/json/' . $codigo;
			 		 $resultado = json_decode(file_get_contents($requisicao), true);

					if (is_array($resultado)) {
								 $hash = str_ireplace('situacao', 'chats', $hash);
						$descricao = $redis->get($hash);

						$mensagem = '<b>' . $codigo . '</b> -' . strip_tags($descricao) . "\n\n";

						foreach ($resultado as $posto) {
							$mensagem = $mensagem . '<b>Data:</b> ' . $posto['data'] . "\n" .
																			'<b>Local:</b> ' . $posto['local'] . "\n" .
																			'<b>Situação:</b> ' . $posto['situacao'] . "\n\n";
						}

						$mensagem = $mensagem . '<i>Você será notificado quando este status mudar</i>';

						if (md5($mensagem) != $situacao) {
							$chatID = str_ireplace('rastro:chats:', '', $hash);
							$chatID = str_ireplace(':' . $codigo, '', $chatID);

							sendMessage($chatID, $mensagem, null, null, true);

							$redis->setex('rastro:situacao:' . $chatID . ':' . $codigo, 2592000, md5($mensagem));
						}
					}
				}
			}
		}

		sleep(45);
	}

	$redis->close();
