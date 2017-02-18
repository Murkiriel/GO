<?php
	if ($idioma == 'PT') {
		if (isset($texto[1])) {
			$placa = str_ireplace($texto[0] . ' ', '', $mensagens['message']['text']);
			$placa = strtoupper(str_ireplace('-', '', str_ireplace(' ', '', $placa)));

			$requisicao = 'http://localhost:8080/api/consultaPlaca?placa=' . $placa;
			 $resultado = json_decode(file_get_contents($requisicao), true);

			if (isset($resultado['modelo'])){
				$mensagem = '<b>Placa:</b> ' . substr($placa, 0, 3) . '-' . substr($placa,3) . "\n";

				$mensagem = $mensagem . '<b>Veículo:</b> ' . $resultado['modelo'] . "\n" .
																'<b>Ano/Modelo:</b> ' . $resultado['ano'] . '/' . $resultado['anoModelo'] . "\n" .
																'<b>Cor:</b> ' . ucfirst(strtolower($resultado['cor'])) . "\n" .
																'<b>Município-UF:</b> ' . ucfirst(strtolower($resultado['municipio'])) . '-' . $resultado['uf'] . "\n" .
																'<b>Chassi:</b> ' . str_ireplace('************', 'Final ', $resultado['chassi']) . "\n" .
																'<b>Situação:</b> ' . $resultado['situacao'] . "\n\n" .
																'<b>Data:</b> ' . $resultado['data'];
			} else {
				$mensagem = 'Placa informada não é válida!';
			}
		} else {
			$mensagem = '📚 /placa AAA-0001';
		}

		sendMessage($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'], null, true);
	}
