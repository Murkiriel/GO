<?php
	$redis = conectarRedis();

	while (true) {
		$minuto = date('i');

		// # RASTRO

		if ($minuto%15 == 0) {
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
							$chatID = str_ireplace($codigo, '', $chatID);

							sendMessage($chatID, $mensagem, null, null, true);

							$redis->setex('rastro:situacao:' . $chatID . ':' . $codigo, 2592000, md5($mensagem));
						}
					}
				}
			}
		}

		if ($minuto%30 == 0) {
			if ($redis->exists('rss:atualizando') === false) {
				$redis->setex('rss:atualizando', 60, 'true');

				foreach ($redis->keys('rss:chats:*') as $hash) {
					foreach ($redis->hgetall($hash) as $link => $md5Mensagem) {
						if ($redis->exists('rss:situacao:' . md5($link)) === false) {
							try {
								$rss = new SimpleXmlElement(file_get_contents($link));
							} catch (Exception $e) {
								$rss = [];
							}

							if (isset($rss->channel->item)) {
								$mensagem = mensagemRSS($rss->channel->item);

								$redis->set('rss:situacao:' . md5($link), $mensagem);
							}
						}
					}
				}

				$mensagensEnviadas = 0;

				foreach ($redis->keys('rss:chats:*') as $hash) {
					$chatID = str_ireplace('rss:chats:', '', $hash);

					foreach ($redis->hgetall($hash) as $link => $md5Mensagem) {
						if ($redis->exists('rss:situacao:' . md5($link)) === true) {
							$mensagem = $redis->get('rss:situacao:' . md5($link));

							if ($md5Mensagem != md5($mensagem)) {
								$resultado = sendMessage($chatID, $mensagem, null, null, true);

								if ($resultado['ok'] === true) {
									++$mensagensEnviadas;
								}

								if ($mensagensEnviadas%30 == 0) {
									sleep(1);
								}

								$redis->hset($hash, $link, md5($mensagem));
							}
						}
					}
				}

				foreach ($redis->keys('rss:situacao:*') as $hash) {
					$redis->del($hash);
				}
			}
		}

		sleep(45);
	}

	$redis->close();
