<?php
	if (isset(YT_KEY)) {
		$chave = md5($mensagens['message']['text']);

		if ($redis->exists('yt:' . $chave) === true) {
			$mensagem = $redis->get('yt:' . $chave);
		} else if (isset($texto[1])) {
			$pesquisa = urlencode(removerComando($texto[0], $mensagens['message']['text']));

			$requisicao = 'https://www.googleapis.com/youtube/v3/search?key=' . YT_KEY;
			$requisicao = $requisicao . '&type=video&part=snippet&maxResults=5&q=' . $pesquisa;
			$resultado = json_decode(file_get_contents($requisicao), true);

			$cont = count($resultado['items']);

			if ($cont != 0) {
				$mensagem = '📹 <b>YouTube:</b>' . "\n";

				$i = 0;

				while (isset($resultado['items'][$i]['id']['videoId'])) {
					$mensagem = $mensagem . "\n" . ($i + 1) . ') <a href="https://www.youtube.com/watch?v=' .
						$resultado['items'][$i]['id']['videoId'] . '">' . $resultado['items'][$i]['snippet']['title'] . '</a>' . "\n";

					++$i;

					if ($i>4) {
						break;
					}
				}
			} else {
				$mensagem = ERROS[$idioma]['SEM_RSULT'];
			}

			$redis->setex('yt:' . $chave, 3600, $mensagem);
		} else {
			$mensagem = '📚: /yt Humor';
		}

		sendMessage($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'],
								null, true, $mensagens['edit_message']);
	} else {
		echo 'Inserir chave de acesso para o YouTube!' . "\n\n";
	}
