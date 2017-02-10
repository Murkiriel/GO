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
	function enviarRequisicao($requisicao, $conteudoRequisicao = null) {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_URL, $requisicao);

		if ($conteudoRequisicao != null) {
			curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($conteudoRequisicao));
		}

		return curl_exec($curl);
	}

	/**
	 * @param integer $updateID
	 */
	function getUpdates($updateID) {
		$requisicao = API_BOT . '/getUpdates';

		$conteudoRequisicao = array(
			 'offset' => $updateID,
			'timeout' => 20
		);

		return json_decode(enviarRequisicao($requisicao, $conteudoRequisicao), true);
	}

	function getMe() {
		return json_decode(enviarRequisicao(API_BOT . '/getMe'), true);
	}

	/**
	 * @param string $chatID
	 * @param string $text
	 */
	function sendMessage($chatID, $text, $replyMessage = null, $replyMarkup = null, $parseMode = false, $editarMensagem = false) {
		$requisicao = API_BOT;

		$conteudoRequisicao = array(
			'chat_id'	=> $chatID,
				 'text' => $text
		);

		if ($editarMensagem === false) {
			$requisicao = $requisicao . '/sendMessage';

			if (isset($replyMessage)) {
				$conteudoRequisicao['reply_to_message_id'] = $replyMessage;
			}
		} else if ($editarMensagem === true) {
			$requisicao = $requisicao . '/editMessageText';

			$conteudoRequisicao['message_id'] = $replyMessage;
		}

		if (isset($replyMarkup)) {
			$conteudoRequisicao['reply_markup'] = $replyMarkup;
		}

		if ($parseMode === true) {
			$conteudoRequisicao['parse_mode'] = 'HTML';
		}

		$conteudoRequisicao['disable_web_page_preview'] = true;

		return json_decode(enviarRequisicao($requisicao, $conteudoRequisicao), true);
	}

	/**
	 * @param string $chatID
	 * @param string $fromID
	 * @param string $mensagemID
	 */
	function forwardMessage($chatID, $fromID, $mensagemID) {
		$requisicao = API_BOT . '/forwardMessage';

		$conteudoRequisicao = array(
					 'chat_id' => $chatID,
			'from_chat_id' => $fromID,
				'message_id' => $mensagemID
		);

		return json_decode(enviarRequisicao($requisicao, $conteudoRequisicao), true);
	}

	/**
	 * @param string $chatID
	 * @param string $photo
	 */
	function sendPhoto($chatID, $photo, $replyMessage = null, $replyMarkup = null, $caption = '@' . DADOS_BOT['result']['username']) {
		$requisicao = API_BOT . '/sendPhoto';

		$conteudoRequisicao = array(
			'chat_id' => $chatID,
				'photo' => $photo
		);

		if (isset($replyMessage)) {
			$conteudoRequisicao['reply_to_message_id'] = $replyMessage;
		}

		if (isset($replyMarkup)) {
			$conteudoRequisicao['reply_markup'] = $replyMarkup;
		}

		$conteudoRequisicao['caption'] = $caption;

		return json_decode(enviarRequisicao($requisicao, $conteudoRequisicao), true);
	}

	/**
	 * @param string $chatID
	 * @param string $document
	 */
	function sendDocument($chatID, $document, $replyMessage = null, $replyMarkup = null, $caption = '@' . DADOS_BOT['result']['username']) {
		$requisicao = API_BOT . '/sendDocument';

		$conteudoRequisicao = array(
			 'chat_id' => $chatID,
			'document' => $document
		);

		if (isset($replyMessage)) {
			$conteudoRequisicao['reply_to_message_id'] = $replyMessage;
		}

		if (isset($replyMarkup)) {
			$conteudoRequisicao['reply_markup'] = $replyMarkup;
		}

		$conteudoRequisicao['caption'] = $caption;

		return json_decode(enviarRequisicao($requisicao, $conteudoRequisicao), true);
	}

	/**
	 * @param string $chatID
	 * @param string $action
	 */
	function sendChatAction($chatID, $action) {
		$requisicao = API_BOT . '/sendChatAction';

		$conteudoRequisicao = array(
			'chat_id' => $chatID,
			 'action' => $action
		);

		return json_decode(enviarRequisicao($requisicao, $conteudoRequisicao), true);
	}

	/**
	 * @param string $chatID
	 */
	function getChatAdministrators($chatID) {
		$requisicao = API_BOT . '/getChatAdministrators';

		$conteudoRequisicao = array(
			'chat_id' => $chatID
		);

		return json_decode(enviarRequisicao($requisicao, $conteudoRequisicao), true);
	}

	/**
	 * @param string $userID
	 */
	function getUserProfilePhotos($userID) {
		$requisicao = API_BOT . '/getUserProfilePhotos';

		$conteudoRequisicao = array(
			'user_id' => $userID,
				'limit' => 1
		);

		return json_decode(enviarRequisicao($requisicao, $conteudoRequisicao), true);
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
		$conteudoRequisicao = array('allowed_updates' => array('message', 'edited_message', 'callback_query'));
		$resultado = json_decode(enviarRequisicao($requisicao, $conteudoRequisicao), true);

		while (true) {
			if (!empty($resultado['result']) and is_array($resultado['result'])) {
				foreach ($resultado['result'] as $mensagens) {
					if (isset($mensagens['message']['date']) and time() - $mensagens['message']['date']<=20) {
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
			return json_decode(file_get_contents($arquivo, false, CONTEXTO), true);
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

    echo '🐞  ERRO: ' . $erroMensagem . ' no arquivo ' . $erroArquivo . ' (Linha ' . $erroLinha . ')' . "\n\n";

		notificarSudos($mensagem);
  }

	set_error_handler('manipularErros');
