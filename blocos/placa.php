<?php
	if ($idioma == 'PT') {
		if (isset($texto[1])) {
			$placa = str_ireplace($texto[0] . ' ', '', $mensagens['message']['text']);
			$placa = strtoupper(str_ireplace('-', '', str_ireplace(' ', '', $placa)));

			$veiculo = new Sinesp;

			try {
				$veiculo->buscar($placa);

				if ($veiculo->existe()) {
					$mensagem = '<b>Placa:</b> ' . substr($placa, 0, 3) . '-' . substr($placa,3) . "\n";

					$mensagem = $mensagem . '<b>Veículo:</b> ' . $veiculo->modelo . "\n" .
																	'<b>Ano/Modelo:</b> ' . $veiculo->ano . '/' . $veiculo->anoModelo . "\n" .
																	'<b>Cor:</b> ' . ucfirst(strtolower($veiculo->cor)) . "\n" .
																	'<b>Município-UF:</b> ' . ucfirst(strtolower($veiculo->municipio)) . '-' . $veiculo->uf . "\n" .
																	'<b>Chassi:</b> ' . str_ireplace('************', 'Final ', $veiculo->chassi) . "\n" .
																	'<b>Situação:</b> ' . $veiculo->situacao . "\n\n" .
																	'<b>Data:</b> ' . $veiculo->data;
				} else {
					$mensagem = 'Placa informada não é válida!';
				}
			} catch (\Exception $e) {
				$mensagem = 'Placa informada não é válida!';
			}
		} else {
			$mensagem = '📚 /placa AAA-0001';
		}

		sendMessage($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'], null, true);
	}
