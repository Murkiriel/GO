<?php
	function conectarRedis() {
		$redis = new Redis();
	  $redis->connect('127.0.0.1', '6379');
		$redis->select(4);

		return $redis;
	}

	/**
	 * @param string $requisicao
	 */
	function enviarRequisicao($requisicao, $conteudoRequisicao = null, $cabecalho = null) {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_URL, $requisicao);

		if ($conteudoRequisicao != null) {
			curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($conteudoRequisicao));
		}

		if ($cabecalho != null) {
			curl_setopt($curl, CURLOPT_HTTPHEADER, $cabecalho);
		}

		$resultado = curl_exec($curl);

		curl_close($curl);

		return $resultado;
	}

	/**
	 * @param string $mensagem
	 */
	function notificarSudos($mensagem) {
		foreach (SUDOS as $sudo) {
			sendMessage($sudo, $mensagem, null, null, true);
		}

		return;
	}

	function atualizarMensagens($resultado) {
		$updateID = 0;

		foreach ($resultado as $mensagens) {
			if (isset($mensagens['message']['date']) and time() - $mensagens['message']['date']<=5) {
				getUpdates($updateID);

				return null;
			}

			$updateID = $mensagens['update_id'];
		}

		++$updateID;

		return getUpdates($updateID);
	}

	function firstUpdate() {
		$loop = true;
		$requisicao = API_BOT . '/getUpdates';
		$conteudoRequisicao = ['allowed_updates' => ['message', 'edited_message', 'channel_post', 'callback_query']];
		$resultado = json_decode(enviarRequisicao($requisicao, $conteudoRequisicao), true);

		while ($loop === true) {
			$resultado = !empty($resultado['result']) ? atualizarMensagens($resultado['result']) : $loop = false;
		}

		return notificarSudos('<pre>Iniciando...</pre>');
	}

	/**
	 * @param string $arquivo
	 */
	 function carregarDados($arquivo) {
		 if (file_exists($arquivo)) {
			 return json_decode(file_get_contents($arquivo), true);
		 }

		 return null;
	 }

	/**
	 * @param string $arquivo
	 * @param string $dados
	 */
	function salvarDados($arquivo, $dados) {
		return file_put_contents($arquivo, json_encode($dados));
	}

	function mensagemRSS($conteudoRSS) {
		$mensagem = '〰〰〰〰〰〰〰' . "\n\n";

		foreach ($conteudoRSS as $item) {
			$item->title = html_entity_decode(strip_tags($item->title), ENT_QUOTES, 'UTF-8');
			$mensagem = $mensagem . '<a href="' . $item->link . '">' . $item->title . '</a>' . "\n\n";
			$mensagem = $mensagem . html_entity_decode(strip_tags($item->description), ENT_QUOTES, 'UTF-8');

			break;
		}

		return $mensagem . "\n\n" . '〰〰〰〰〰〰〰';
	}

	function removerComando($comando, $mensagem) {
		return str_ireplace('/' . $comando . ' ', '', $mensagem);
	}

	function validarAdmin($resultado, $usuarioID) {
		foreach ($resultado as $adminsGrupo) {
			if ($adminsGrupo['user']['id'] == $usuarioID) {
				return true;
			}
		}

		return false;
	}

	function montarTeclado($conteudoMensagem) {
		$teclado = null;

		$posicao1 = stripos($conteudoMensagem, '[');

		if ($posicao1 !== false) {
			$montarTeclado = substr($conteudoMensagem, $posicao1+1);
			$conteudoMensagem = str_ireplace('[' . $montarTeclado, '', $conteudoMensagem);
			$posicao2 = strripos($montarTeclado, ']');
			$montarTeclado = str_ireplace(substr($montarTeclado, $posicao2), '', $montarTeclado);

			$cont = 0;
			$linha = 0;

			foreach (explode('[', $montarTeclado) as $botao) {
				if (!empty($botao)) {
					$botao = str_ireplace(']', '', $botao);

					if ($cont%2 == 0) {
						$teclado['inline_keyboard'][$linha][0]['text'] = $botao;
					} else {
						$tipoBotao = !filter_var($botao, FILTER_VALIDATE_URL) === false ? 'url' : 'callback_data';

						$teclado['inline_keyboard'][$linha][0][$tipoBotao] = $botao;

						++$linha;
					}

					++$cont;
				}
			}

			return json_encode($teclado);
		} else {
			return null;
		}
	}

	function removerTeclado($conteudoMensagem) {
		$posicao = stripos($conteudoMensagem, '[');
		$teclado = substr($conteudoMensagem, $posicao+1);

		return str_ireplace('[' . $teclado, '', $conteudoMensagem);
	}

	function manipularErros($erroCodigo = null, $erroMensagem = null, $erroArquivo = null, $erroLinha = null) {
    if (error_reporting() == 0) {
      return null;
    }

    if (func_num_args() == 5) {
      list($erroCodigo, $erroMensagem, $erroArquivo, $erroLinha) = func_get_args();
    } else if (func_num_args() != 5) {
			$excecao = func_get_arg(0);
			$erroCodigo = $excecao->getCode();
			$erroMensagem = $excecao->getMessage();
			$erroArquivo = $excecao->getFile();
			$erroLinha = $excecao->getLine();
    }

    $erroTipo = array(
      E_COMPILE_ERROR => 'COMPILE ERROR', E_COMPILE_WARNING => 'COMPILE WARNING', E_CORE_ERROR => 'CORE ERROR',
			E_CORE_WARNING => 'CORE WARNING', E_ERROR => 'ERROR', E_NOTICE => 'NOTICE', E_PARSE => 'PARSING ERROR',
			E_RECOVERABLE_ERROR => 'RECOVERABLE ERROR', E_STRICT => 'STRICT NOTICE', E_USER_ERROR => 'USER ERROR',
			E_USER_NOTICE => 'USER NOTICE', E_USER_WARNING => 'USER WARNING', E_WARNING => 'WARNING'
  	);

		array_key_exists($erroCodigo, $erroTipo) ? $erroEncontrado = $erroTipo[$erroCodigo] : $erroEncontrado = 'CAUGHT EXCEPTION';

    $mensagem = '<pre>🐞 ERRO ENCONTRADO</pre>' . "\n\n";
		$mensagem .= '<b>Tipo:</b> ' . $erroEncontrado . "\n";
    $mensagem .= '<b>Arquivo:</b> ' . $erroArquivo . "\n";
		$mensagem .= '<b>Linha:</b> ' . $erroLinha . "\n";
    $mensagem .= '<b>Descrição:</b> ' . $erroMensagem . "\n";
		$mensagem .= '<b>Data e Hora:</b> ' . date('d/m/Y H:i:s') . "\n";

    echo '🐞  ERRO: ' , $erroMensagem , ' no arquivo ' , $erroArquivo , ' (Linha ' , $erroLinha , ')' , "\n\n";

		notificarSudos($mensagem);
  }

	set_error_handler('manipularErros');
