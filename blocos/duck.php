<?php
	$chave = md5($mensagens['message']['text']);

	if ($redis->exists('duck:' . $chave) === true) {
		$mensagem = $redis->get('duck:' . $chave);
	} else if (isset($texto[1])) {

		$requisicao = 'http://api.duckduckgo.com/?format=json&q=' . urlencode(str_ireplace($texto[0] . ' ', '', $mensagens['message']['text']));
		 $resultado = json_decode(file_get_contents($requisicao, false, CONTEXTO), true);

		$cont = count($resultado['RelatedTopics']);

		if ($cont != 0) {
			$mensagem = '🔎 <b>DuckDuckGO:</b>' . "\n";

			for ($i = 0; $i<$cont; $i++) {
				if ($i > 4) {
					break;
				}

				$mensagem = $mensagem . "\n" . ($i+1) . ') ' . '<a href="' . $resultado['RelatedTopics'][$i]['FirstURL'] . '">' . $resultado['RelatedTopics'][$i]['Text'] . '</a>' . "\n";
			}
		} else {
			$mensagem = ERROS[$idioma][SEM_RSULT];
		}

		$redis->setex('duck:' . $chave, 3600, $mensagem);
	} else {
		$mensagem = '📚: /duck Telegram';
	}

	sendMessage($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'], null, true);
