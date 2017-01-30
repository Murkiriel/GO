<?php
	function conectarRedis() {
		$redis = new Redis();
	  $redis->connect('127.0.0.1', '6379');
		$redis->select(4);

		return $redis;
	}

	function enviarRequisicao($requisicao, $conteudoRequisicao = NULL) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_URL, $requisicao);

		if ($conteudoRequisicao != NULL) {
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($conteudoRequisicao));
		}

		return curl_exec($ch);
	}

	function getUpdates($updateID) {
		$requisicao = API_BOT . '/getUpdates';

		$conteudoRequisicao = array(
			 'offset' => $updateID,
			'timeout' => 20
		);

		return json_decode(enviarRequisicao($requisicao, $conteudoRequisicao), TRUE);
	}

	function getMe() {
		return json_decode(enviarRequisicao(API_BOT . '/getMe'), TRUE);
	}

	function sendMessage($chatID, $text, $replyMessage = NULL, $replyMarkup = NULL, $parseMode = FALSE, $disablePreview = TRUE, $editarMensagem = FALSE) {
		$requisicao = API_BOT;

		$conteudoRequisicao = array(
			'chat_id'	=> $chatID,
				 'text' => $text
		);

		if ($editarMensagem === FALSE) {
			$requisicao = $requisicao . '/sendMessage';

			if (isset($replyMessage)) {
				$conteudoRequisicao['reply_to_message_id'] = $replyMessage;
			}
		} else {
			$requisicao = $requisicao . '/editMessageText';

			$conteudoRequisicao['message_id'] = $replyMessage;
		}

		if (isset($replyMarkup)) {
			$conteudoRequisicao['reply_markup'] = $replyMarkup;
		}

		if ($parseMode === TRUE) {
			$conteudoRequisicao['parse_mode'] = 'HTML';
		}

		if ($disablePreview === TRUE) {
			$conteudoRequisicao['disable_web_page_preview'] = TRUE;
		}

		return json_decode(enviarRequisicao($requisicao, $conteudoRequisicao), TRUE);
	}

	function forwardMessage($chatID, $fromID, $mensagemID) {
		$requisicao = API_BOT . '/forwardMessage';

		$conteudoRequisicao = array(
					 'chat_id' => $chatID,
			'from_chat_id' => $fromID,
				'message_id' => $mensagemID
		);

		return json_decode(enviarRequisicao($requisicao, $conteudoRequisicao), TRUE);
	}

	function sendPhoto($chatID, $photo, $replyMessage = NULL, $replyMarkup = NULL, $caption = '@' . DADOS_BOT['result']['username']) {
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

		if (isset($caption)) {
			$conteudoRequisicao['caption'] = $caption;
		}

		return json_decode(enviarRequisicao($requisicao, $conteudoRequisicao), TRUE);
	}

	function sendDocument($chatID, $document, $replyMessage = NULL, $replyMarkup = NULL, $caption = '@' . DADOS_BOT['result']['username']) {
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

		if (isset($caption)) {
			$conteudoRequisicao['caption'] = $caption;
		}

		return json_decode(enviarRequisicao($requisicao, $conteudoRequisicao), TRUE);
	}

	function sendChatAction($chatID, $action) {
		$requisicao = API_BOT . '/sendChatAction';

		$conteudoRequisicao = array(
			'chat_id' => $chatID,
			 'action' => $action
		);

		return json_decode(enviarRequisicao($requisicao, $conteudoRequisicao), TRUE);
	}

	function getChatAdministrators($chatID) {
		$requisicao = API_BOT . '/getChatAdministrators';

		$conteudoRequisicao = array(
			'chat_id' => $chatID
		);

		return json_decode(enviarRequisicao($requisicao, $conteudoRequisicao), TRUE);
	}

	function getUserProfilePhotos($userID) {
		$requisicao = API_BOT . '/getUserProfilePhotos';

		$conteudoRequisicao = array(
			'user_id' => $userID,
				'limit' => 1
		);

		return json_decode(enviarRequisicao($requisicao, $conteudoRequisicao), TRUE);
	}

	function notificarSudos($mensagem) {
		foreach (SUDOS as $sudo) {
			sendMessage($sudo, $mensagem, NULL, NULL, TRUE);
		}

		return;
	}

	function firstUpdate() {
		$updateID = 0;

		$requisicao = API_BOT . '/getUpdates';
		$conteudoRequisicao = array('allowed_updates' => array('message', 'edited_message', 'callback_query'));
		$resultado = json_decode(enviarRequisicao($requisicao, $conteudoRequisicao), TRUE);

		while (TRUE) {
			if (!empty($resultado['result']) AND is_array($resultado['result'])) {
				foreach ($resultado['result'] as $mensagens) {
					if (isset($mensagens['message']['date']) AND time() - $mensagens['message']['date']<= 20) {
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

	function carregarDados($arquivo) {
		if (file_exists($arquivo)) {
			return json_decode(file_get_contents($arquivo, FALSE, CONTEXTO), TRUE);
		} else {
			return NULL;
		}
	}

	function salvarDados($arquivo, $dados) {
		return file_put_contents($arquivo, json_encode($dados));
	}

	function manipularErros($erroCodigo = NULL, $erroMensagem = NULL, $erroArquivo = NULL, $erroLinha = NULL) {
		$excecao = NULL;

    if (error_reporting() == 0) {
      return NULL;
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
      E_COMPILE_ERROR => 'COMPILE ERROR',		E_COMPILE_WARNING => 'COMPILE WARNING',		E_CORE_ERROR => 'CORE ERROR',
			 E_CORE_WARNING => 'CORE WARNING',							E_ERROR => 'ERROR',									E_NOTICE => 'NOTICE',
			 				E_PARSE => 'PARSING ERROR',	E_RECOVERABLE_ERROR => 'RECOVERABLE ERROR',			E_STRICT => 'STRICT NOTICE',
				 E_USER_ERROR => 'USER ERROR',					E_USER_NOTICE => 'USER NOTICE',			E_USER_WARNING => 'USER WARNING',
				 		E_WARNING => 'WARNING'
  	);

    if (array_key_exists($erroCodigo, $erroTipo)) {
      $erroEncontrado = $erroTipo[$erroCodigo];
    } else {
      $erroEncontrado = 'CAUGHT EXCEPTION';
    }

    $mensagem  = '<pre>🐞 ERRO ENCONTRADO</pre>'							. "\n\n";	$mensagem .= '<b>Tipo:</b> '				. $erroEncontrado			. "\n";
    $mensagem .= '<b>Arquivo:</b> '			. $erroArquivo				. "\n";		$mensagem .= '<b>Linha:</b> '				. $erroLinha					. "\n";
    $mensagem .= '<b>Descrição:</b> '		. $erroMensagem				. "\n";		$mensagem .= '<b>Data e Hora:</b> ' . date('d/m/Y H:i:s') . "\n";

    echo '🐞  ERRO: ' . $erroMensagem . ' no arquivo ' . $erroArquivo . ' (Linha ' . $erroLinha . ')' . "\n\n";

		notificarSudos($mensagem);
  }

	set_error_handler('manipularErros');
