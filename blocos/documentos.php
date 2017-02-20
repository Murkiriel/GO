<?php
	$teclado['hide_keyboard'] = true;

	$replyMarkup = json_encode($teclado);

	if (isset($texto[1])) {
		if ($texto[0] == 'books' or $texto[0] == 'libri' or $texto[0] == 'libros') {
			$texto[0] = 'livros';
		}

		$dados = $redis->hgetall('documentos:' . $texto[0]);

		$docs = isset($dados) ? array_keys($dados) : null;

				 $cont = 0;
		$resultado = [];

		foreach ($docs as $doc) {
			$posicao = strripos($doc, $texto[1]);

			if ($posicao !== false) {
				if (isset($texto[2])) {
					$posicao = strripos($doc, $texto[2]);

					if ($posicao !== false) {
						$resultado[$cont] = $doc;
					}
				} else {
					$resultado[$cont] = $doc;
				}

				++$cont;
			}

			if ($cont == 99) {
				break;
			}
		}

		if ($mensagens['message']['chat']['type'] == 'group' or $mensagens['message']['chat']['type'] == 'supergroup') {
						$selective = true;
			$oneTimeKeyboard = true;
		} else if ($mensagens['message']['chat']['type'] == 'private') {
						$selective = false;
			$oneTimeKeyboard = false;
		}

		if ($resultado[0] != null) {
			$teclado = [
				'resize_keyboard' => true,
				'one_time_keyboard' => $oneTimeKeyboard,
				'selective' => $selective
			];

			sort($resultado);

			for ($i = 0; $i<$cont; $i++) {
				$teclado['keyboard'][$i][0] = $resultado[$i];
			}

			$replyMarkup = json_encode($teclado);

			$mensagem = TECLADO[$idioma];
		} else {
			$mensagem = ERROS[$idioma]['SEM_RSULT'];
		}
	} else {
		$mensagem = '📚: /' . $texto[0] . ' Telegram';
	}

	sendMessage($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'], $replyMarkup, true);
