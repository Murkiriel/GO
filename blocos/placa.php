<?php
	require(RAIZ . 'lib/sinesp.php');

	if ($idioma == 'PT') {
		$mensagemID = $mensagens['message']['message_id'];
		$editarMensagem = false;

		if (isset($texto[1])) {
			$placa = removerComando($texto[0], $mensagens['message']['text']);
			$placa = strtoupper(str_ireplace(['-', ' '], '', $placa));

			if (preg_match('/[A-Z]{3}[0-9]{4}/', $placa)) {
				$veiculo = new Sinesp;

				$proxy[0] = ['ip' => '201.16.147.193', 'porta' => '80'];
				$proxy[1] = ['ip' => '186.250.96.1', 'porta' => '8080'];
				$proxy[2] = ['ip' => '177.55.253.68', 'porta' => '8080'];

				for ($i = 0; $i<2; $i++) {
					try {
						$veiculo->buscar($placa, $proxy[$i]);

						if ($veiculo->existe()) {
							$mensagem = '<b>Placa:</b> ' . substr($placa, 0, 3) . '-' . substr($placa, 3) . "\n";

							$mensagem = $mensagem . '<b>Veículo:</b> ' . $veiculo->modelo . "\n" .
																			'<b>Ano/Modelo:</b> ' . $veiculo->ano . '/' . $veiculo->anoModelo . "\n" .
																			'<b>Cor:</b> ' . ucfirst(strtolower($veiculo->cor)) . "\n" .
																			'<b>Município-UF:</b> ' . ucfirst(strtolower($veiculo->municipio)) . '-' . $veiculo->uf . "\n" .
																			'<b>Chassi:</b> ' . str_ireplace('************', 'Final ', $veiculo->chassi) . "\n" .
																			'<b>Situação:</b> ' . $veiculo->situacao . "\n\n" .
																			'<b>Data:</b> ' . $veiculo->data;
							break;
						} else {
							$resultado = sendMessage($mensagens['message']['chat']['id'], '<pre>Tentativa ' . ($i + 1) . '/3...</pre>',
													$mensagemID, null, true, $editarMensagem);

							$mensagem = 'Desculpe, a sua pesquisa não pôde ser concluída.';
							$mensagemID = $resultado['result']['message_id'];
							$editarMensagem = true;
						}
					} catch (\Exception $e) {
						echo 'Erro ao tentar conectar com o proxy ', $proxy[$i]['ip'], ':', $proxy[$i]['porta'], "\n\n";
					}
				}
			}

			if (empty($mensagem)) {
				$mensagem = 'Placa informada não é válida!';
			}
		} else {
			$mensagem = '📚 /placa AAA-0001';
		}

		sendMessage($mensagens['message']['chat']['id'], $mensagem, $mensagemID, null, true, $editarMensagem);
	}
