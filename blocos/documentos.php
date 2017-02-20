<?php
	$teclado['hide_keyboard'] = true;

	$replyMarkup = json_encode($teclado);

	if (isset($texto[1])) {
		if ($texto[0] == 'books' or $texto[0] == 'libri' or $texto[0] == 'libros') {
			$texto[0] = 'livros';
		}

		$dados = $redis->hgetall('documentos:' . $texto[0]);

		if (isset($dados)) {
			$docs = array_keys($dados);
		} else {
			$dosc[0] = null;
		}

						 $cont = 0;
		$resultados[0] = null;

		foreach ($docs as $lista) {
			$posicao = strripos($lista, $texto[1]);

			if ($posicao !== false) {
				if (isset($texto[2])) {
					$posicao = strripos($lista, $texto[2]);

					if ($posicao !== false) {
						$resultados[$cont] = $lista;
					}
				} else {
					$resultados[$cont] = $lista;
				}

				++$cont;
			}

			if ($cont == 99) {
				break;
			}
		}

		if ($mensagens['message']['chat']['type'] == 'private') {
						$selective = false;
			$oneTimeKeyboard = false;
		} else {
						$selective = true;
			$oneTimeKeyboard = true;
		}

		if ($resultados[0] != null) {
			$teclado = array(
					'resize_keyboard' => true,
				'one_time_keyboard' => $oneTimeKeyboard,
								'selective' => $selective
			);

			sort($resultados);

			for ($i = 0; $i<$cont; $i++) {
				$teclado['keyboard'][$i][0] = $resultados[$i];
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
