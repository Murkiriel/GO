<?php
	$teclado = array(
		'hide_keyboard' => true
	);

	$replyMarkup = json_encode($teclado);

	if(isset($texto[1])){
		$dadosLivros = carregarDados(RAIZ . 'dados/livros.json');

		if(isset($dadosLivros)){
			$docs = array_keys($dadosLivros);
		}
		else{
			$dosc[0] = null;
		}

						 $cont = 0;
		$resultados[0] = null;

		foreach($docs as $lista){
			$posicao = strripos($lista, $texto[1]);

			if($posicao !== false){
				if(isset($texto[2])){
					$posicao = strripos($lista, $texto[2]);

					if($posicao !== false){
						$resultados[$cont] = $lista;
						++$cont;
					}
				}
				else{
					$resultados[$cont] = $lista;
					++$cont;
				}
			}

			if($cont == 99){
				break;
			}
		}

		if($mensagens['message']['chat']['type'] == 'private'){
							$selective = false;
			$one_time_keyboard = false;
		}
		else{
							$selective = true;
			$one_time_keyboard = true;
		}

		if($resultados[0] != null){
			$teclado = array(
				'keyboard' => array(
					array()
				),
					'resize_keyboard' => true,
				'one_time_keyboard' => $one_time_keyboard,
								'selective' => $selective
			);

			for($i=0;$i<$cont;$i++){
				$teclado['keyboard'][$i][0] = $resultados[$i];
			}

			$replyMarkup = json_encode($teclado);

			$mensagem = TECLADO[$mensagens['IDIOMA']];
		}
		else{
			$mensagem = ERROS[$mensagens['IDIOMA']]['SEM_RSULT'];
		}
	}
	else{
		$mensagem = '📚: /' . LIVROS[$mensagens['IDIOMA']] . ' Harry Potter';
	}

	sendMessage($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'], $replyMarkup, true);
