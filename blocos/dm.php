<?php
	$chave = md5($mensagens['message']['text']);

	if ($redis->exists('dm:' . $chave) === true) {
		$mensagem = $redis->get('dm:' . $chave);
	} else if (isset($texto[1])) {
		$pesquisa = urlencode(removerComando($texto[0], $mensagens['message']['text']));

		$requisicao = 'https://api.dailymotion.com/videos?limit=5&search=' . $pesquisa;
		$resultado = json_decode(file_get_contents($requisicao), true);

		$cont = count($resultado['list']);

		if ($cont != 0) {
			$mensagem = '📹 <b>Dailymotion:</b>' . "\n";

			$i = 0;

			while (isset($resultado['list'][$i]['id'])) {
				$mensagem = $mensagem . "\n" . ($i + 1) . ') <a href="http://www.dailymotion.com/video/' .
										$resultado['list'][$i]['id'] . '">' . $resultado['list'][$i]['title'] . '</a>' . "\n";

				++$i;

				if ($i>4) {
					break;
				}
			}
		} else {
			$mensagem = ERROS[$idioma]['SEM_RSULT'];
		}

		$redis->setex('dm:' . $chave, 3600, $mensagem);
	} else {
		$mensagem = '📚: /dm Humor';
	}

	sendMessage($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'], null, true);
