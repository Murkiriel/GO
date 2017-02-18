<?php
	 $redis = conectarRedis();
	$minuto = date('i');
	// # RASTRO

	if ($minuto%15 == 0) {
		if (!$redis->exists('rastro:atualizando')) {
			$redis->setex('rastro:atualizando', 60, 'true');

			$rastros = $redis->keys('rastro:situacao:*');

			foreach ($rastros as $chave => $valor) {
				$situacao = $redis->get($valor);
					$codigo = substr(strrchr($valor, ':'), 1);

				$requisicao = 'http://127.0.0.1:3000/json/' . $codigo;
		 		 $resultado = json_decode(file_get_contents($requisicao, false, CONTEXTO), true);

				if (is_array($resultado)){
							 $hash = str_ireplace(':situacao', '', $valor);
					$descricao = $redis->get($hash);

					$mensagem = '<b>' . $codigo . '</b> -' . strip_tags($descricao) . "\n\n";

					foreach ($resultado as $chave => $valor) {
						$mensagem = $mensagem . '<b>Data:</b> ' . $valor['data'] . "\n" .
																		'<b>Local:</b> ' . $valor['local'] . "\n" .
																		'<b>Situação:</b> ' . $valor['situacao'] . "\n\n";
					}

					$mensagem = $mensagem . '<i>Você será notificado quando este status mudar</i>';

					if (md5($mensagem) != $situacao) {
						$chatID = str_ireplace('rastro:', '', $hash);
						$chatID = str_ireplace(':' . $codigo, '', $chatID);

						sendMessage($chatID, $mensagem, null, null, true);

						$redis->setex('rastro:situacao:' . $chatID . ':' . $codigo, 2592000, md5($mensagem));
					}
				}

				sleep(1);
			}
		}
	}

	if ($minuto%30 == 0){
		if (!$redis->exists('rss:atualizando')) {
			$redis->setex('rss:atualizando', 60, 'true');

			$feeds = $redis->keys('rss:feed:*');

			foreach ($feeds as $hash) {
				$dadosRSS = $redis->hgetall($hash);

				foreach ($dadosRSS as $chave => $valor) {
					$feed = file_get_contents($chave, false, CONTEXTO);

					try{
						$rss = new SimpleXmlElement($feed);
					}
					catch(Exception $e){
						notificarSudos($e->getMessage());
					}

					if (isset($rss->channel->item)) {
						$mensagem = '〰〰〰〰〰〰〰' . "\n\n";

						foreach($rss->channel->item as $item){
							$item->title = html_entity_decode(strip_tags($item->title), ENT_QUOTES, 'UTF-8');
							$mensagem = $mensagem . '<b>' . $item->title . '</b>' . "\n\n";
							$item->description = html_entity_decode(strip_tags($item->description), ENT_QUOTES, 'UTF-8');
							$mensagem = $mensagem . $item->description . "\n\n" . '👉 <a href="' . $item->link . '">Continuar lendo</a>';

							break;
						}

						$mensagem = $mensagem . "\n\n" . '〰〰〰〰〰〰〰';

						if ($valor != md5($item->title)) {
							$chatID = str_ireplace('rss:feed:', '', $hash);

							$redis->hset('rss:feed:' . $chatID, $chave, md5($item->title));

							sendMessage($chatID, $mensagem, null, null, true);
						}
					}

					sleep(1);
				}
			}
		}
	}

	$redis->close();
