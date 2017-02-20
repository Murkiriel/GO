<?php
	$chave = md5($mensagens['message']['text']);

	if ($redis->exists('duck:' . $chave) === true) {
		$mensagem = $redis->get('duck:' . $chave);
	} else if (isset($texto[1])) {
		$pesquisa = urlencode(removerComando($texto[0], $mensagens['message']['text']));

		$requisicao = 'http://api.duckduckgo.com/?format=json&q=' . $pesquisa;
		$resultado = json_decode(file_get_contents($requisicao), true);

		$cont = count($resultado['RelatedTopics']);

		if ($cont != 0) {
			$mensagem = '🔎 <b>DuckDuckGO:</b>' . "\n";

			$i = 0;

			while (isset($resultado['RelatedTopics'][$i]['FirstURL'])) {
				$mensagem = $mensagem . "\n" . ($i + 1) . ') ' . '<a href="' . $resultado['RelatedTopics'][$i]['FirstURL'] . '">' .
										$resultado['RelatedTopics'][$i]['Text'] . '</a>' . "\n";

				++$i;

				if ($i>4) {
					break;
				}
			}
		} else {
			$mensagem = ERROS[$idioma]['SEM_RSULT'];
		}

		$redis->setex('duck:' . $chave, 3600, $mensagem);
	} else {
		$mensagem = '📚: /duck Telegram';
	}

	sendMessage($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'],
							null, true, $mensagens['edit_message']);
