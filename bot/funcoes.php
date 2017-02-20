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

	function firstUpdate() {
		$updateID = 0;

		$requisicao = API_BOT . '/getUpdates';
		$conteudoRequisicao = ['allowed_updates' => ['message', 'edited_message', 'channel_post', 'callback_query']];
		$resultado = json_decode(enviarRequisicao($requisicao, $conteudoRequisicao), true);

		while (true) {
			if (!empty($resultado['result'])) {
				foreach ($resultado['result'] as $mensagens) {
					if (isset($mensagens['message']['date']) and time() - $mensagens['message']['date']<=5) {
						getUpdates($updateID);
						return notificarSudos('<pre>Iniciando...</pre>');
					}

					$updateID = $mensagens['update_id'] + 1;
				}

				$resultado = getUpdates($updateID);
			} else {
				return notificarSudos('<pre>Iniciando...</pre>');
			}
		}
	}

	/**
	 * @param string $arquivo
	 */
	function carregarDados($arquivo) {
		if (file_exists($arquivo)) {
			return json_decode(file_get_contents($arquivo), true);
		} else {
			return null;
		}
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

		$mensagem = $mensagem . "\n\n" . '〰〰〰〰〰〰〰';

		return $mensagem;
	}

	function removerComando($comando, $mensagem) {
		return str_ireplace('/' . $comando . ' ', '', $mensagem);
	}

	function manipularErros($erroCodigo = null, $erroMensagem = null, $erroArquivo = null, $erroLinha = null) {
    if (error_reporting() == 0) {
      return null;
    }

    if (func_num_args() == 5) {
      list($erroCodigo, $erroMensagem, $erroArquivo, $erroLinha) = func_get_args();
    } else {
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

    if (array_key_exists($erroCodigo, $erroTipo)) {
      $erroEncontrado = $erroTipo[$erroCodigo];
    } else {
      $erroEncontrado = 'CAUGHT EXCEPTION';
    }

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
