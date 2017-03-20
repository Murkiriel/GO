<?php
	if ($idioma == 'PT') {
		if (isset($texto[1])) {
			$placa = removerComando($texto[0], $mensagens['message']['text']);
			$placa = strtoupper(str_ireplace(['-', ' '], '', $placa));

			if (preg_match('/^[A-Z]{3}[0-9]{4}$/', $placa)) {
				$proxy[0] = ['ip' => '201.16.147.193', 'porta' => '80'];
				$proxy[1] = ['ip' => '177.190.209.10', 'porta' => '8080'];
				$proxy[2] = ['ip' => '177.55.253.68', 'porta' => '8080'];

				for ($i = 0; $i<2; $i++) {
					$veiculo = json_decode(shell_exec('python ' . RAIZ . 'py/placa.py ' .
										 $proxy[$i]['ip'] . ' ' . $proxy[$i]['porta'] . ' ' . escapeshellarg($placa)), true);

					if (!empty($veiculo['model'])) {
						$mensagem = '<b>Placa:</b> ' . substr($placa, 0, 3) . '-' . substr($placa, 3) . "\n";

						$mensagem = $mensagem . '<b>Veículo:</b> ' . $veiculo['model'] . "\n" .
														'<b>Ano/Modelo:</b> ' . $veiculo['year'] . '/' . $veiculo['model_year'] . "\n" .
														'<b>Cor:</b> ' . ucfirst(strtolower($veiculo['color'])) . "\n" .
														'<b>Município-UF:</b> ' . ucfirst(strtolower($veiculo['city'])) . '-' . $veiculo['state'] . "\n" .
														'<b>Chassi:</b> ' . str_ireplace('************', 'Final ', $veiculo['chassis']) . "\n" .
														'<b>Situação:</b> ' . $veiculo['status_message'] . "\n\n" .
														'<b>Data:</b> ' . $veiculo['date'];
						break;
					} else {
						$resultado = sendMessage($mensagens['message']['chat']['id'], '<pre>Tentativa ' . ($i + 1) . '/3...</pre>',
												$mensagens['message']['message_id'], null, true, $mensagens['edit_message']);

						$mensagem = 'Desculpe, a sua pesquisa não pôde ser concluída.';

						if (isset($resultado['result'])) {
							$mensagens['message']['message_id'] = $resultado['result']['message_id'];
						}

						$mensagens['edit_message'] = true;
					}
				}
			} else {
				$mensagem = 'Placa informada não é válida!';
			}
		} else {
			$mensagem = '📚 /placa AAA-0001';
		}

		sendMessage($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'],
								null, true, $mensagens['edit_message']);
	}
