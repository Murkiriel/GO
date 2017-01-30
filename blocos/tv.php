<?php
	$teclado = array(
		'hide_keyboard' => TRUE
	);

	$replyMarkup = json_encode($teclado);

	if (isset($texto[1])) {
		$dadosTV = $redis->hgetall('documentos:tv');

		if (isset($dadosTV)) {
			$docs = array_keys($dadosTV);
		} else {
			$dosc[0] = NULL;
		}

						 $cont = 0;
		$resultados[0] = NULL;

		foreach ($docs as $lista) {
			$posicao = strripos($lista, $texto[1]);

			if ($posicao !== FALSE) {
				if (isset($texto[2])) {
					$posicao = strripos($lista, $texto[2]);

					if ($posicao !== FALSE) {
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
						$selective = FALSE;
			$oneTimeKeyboard = FALSE;
		} else {
						$selective = TRUE;
			$oneTimeKeyboard = TRUE;
		}

		if ($resultados[0] != NULL) {
			$teclado = array(
				'keyboard' => array(
					array()
				),
					'resize_keyboard' => TRUE,
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
		$mensagem = '📚: /tv Star Wars';
	}

	sendMessage($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'], $replyMarkup, TRUE);
