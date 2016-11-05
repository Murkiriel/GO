<?php
	function enviarRequisicao($requisicao, $conteudoRequisicao = null){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL, $requisicao);

		if($conteudoRequisicao != null){
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($conteudoRequisicao));
			echo $requisicao . '?' . http_build_query($conteudoRequisicao) . "\n\n";
		}

		return curl_exec($ch);
	}

	function getMe(){
		$requisicao = API_BOT . '/getMe';

		return json_decode(enviarRequisicao($requisicao), true);
	}

	function getUpdates($updateID){
		$requisicao = API_BOT . '/getUpdates';

		$conteudoRequisicao = array(
			 'offset' => $updateID,
			'timeout' => 20
		);

		return json_decode(enviarRequisicao($requisicao, $conteudoRequisicao), true);
	}

	function getChatAdministrators($chatID){
		$requisicao = API_BOT . '/getChatAdministrators';

		$conteudoRequisicao = array(
			'chat_id' => $chatID
		);

		return json_decode(enviarRequisicao($requisicao, $conteudoRequisicao), true);
	}

	function getUserProfilePhotos($userID){
		$requisicao = API_BOT . '/getUserProfilePhotos';

		$conteudoRequisicao = array(
			'user_id' => $userID,
				'limit' => 1
		);

		return json_decode(enviarRequisicao($requisicao, $conteudoRequisicao), true);
	}

	function sendChatAction($chatID, $action){
		$requisicao = API_BOT . '/sendChatAction';

		$conteudoRequisicao = array(
			'chat_id' => $chatID,
			 'action' => $action
		);

		return json_decode(enviarRequisicao($requisicao, $conteudoRequisicao), true);
	}

	function sendDocument($chatID, $document, $replyMessage = null, $replyMarkup = null, $caption = null, $disableNotification = false){
		$requisicao = API_BOT . '/sendDocument';

		$conteudoRequisicao = array(
			 'chat_id' => $chatID,
			'document' => $document
		);

		if(isset($replyMessage)){
			$conteudoRequisicao['reply_to_message_id'] = $replyMessage;
		}

		if(isset($replyMarkup)){
			$conteudoRequisicao['reply_markup'] = $replyMarkup;
		}

		if(isset($caption)){
			$conteudoRequisicao['caption'] = $caption;
		}

		if($disableNotification == true){
			$conteudoRequisicao['disable_notification'] = true;
		}

		return json_decode(enviarRequisicao($requisicao, $conteudoRequisicao), true);
	}

	function sendMessage($chatID, $text, $replyMessage = null, $replyMarkup = null, $parseMode = false, $disablePreview = true, $disableNotification = false){
		$requisicao = API_BOT;

		$conteudoRequisicao = array(
			'chat_id'	=> $chatID,
				 'text' => $text
		);

		if($GLOBALS['EDT_MSG'] == false){
			$requisicao = $requisicao . '/sendMessage';

			if(isset($replyMessage)){
				$conteudoRequisicao['reply_to_message_id'] = $replyMessage;
			}

			if($disableNotification == true){
				$conteudoRequisicao['disable_notification'] = true;
			}
		}
		else{
			$requisicao = $requisicao . '/editMessageText';

			if(isset($replyMessage)){
				$conteudoRequisicao['message_id'] = $replyMessage;
			}
		}

		if(isset($replyMarkup)){
			$conteudoRequisicao['reply_markup'] = $replyMarkup;
		}

		if($parseMode == true){
			$conteudoRequisicao['parse_mode'] = 'HTML';
		}

		if($disablePreview == true){
			$conteudoRequisicao['disable_web_page_preview'] = true;
		}

		return json_decode(enviarRequisicao($requisicao, $conteudoRequisicao), true);
	}

	function sendPhoto($chatID, $photo, $replyMessage = null, $replyMarkup = null, $caption = null, $disableNotification = false){
		$requisicao = API_BOT . '/sendPhoto';

		$conteudoRequisicao = array(
			'chat_id' => $chatID,
				'photo' => $photo
		);

		if(isset($replyMessage)){
			$conteudoRequisicao['reply_to_message_id'] = $replyMessage;
		}

		if(isset($replyMarkup)){
			$conteudoRequisicao['reply_markup'] = $replyMarkup;
		}

		if($caption == true){
			$conteudoRequisicao['caption'] = $caption;
		}

		if($disableNotification == true){
			$conteudoRequisicao['disable_notification'] = true;
		}

		return json_decode(enviarRequisicao($requisicao, $conteudoRequisicao), true);
	}

	function forwardMessage($chatID, $fromID, $mensagemID, $disableNotification = false){
		$requisicao = API_BOT . '/forwardMessage';

		$conteudoRequisicao = array(
					 'chat_id' => $chatID,
			'from_chat_id' => $fromID,
				'message_id' => $mensagemID
		);

		if($disableNotification == true){
			$conteudoRequisicao['disable_notification'] = true;
		}

		return json_decode(enviarRequisicao($requisicao, $conteudoRequisicao), true);
	}

	function carregarDados($arquivo){
		if(file_exists($arquivo)){
			return json_decode(file_get_contents($arquivo, false, CONTEXTO), true);
		}
		else{
			return null;
		}
	}

	function salvarDados($arquivo, $dados){
		return file_put_contents($arquivo, json_encode($dados));
	}

	function verificarCache($nomeCache){
		$chave = md5(strtolower($nomeCache));

		if(file_exists(CACHE_PASTA . $chave . '.json')){
			$mensagem = carregarDados(CACHE_PASTA . $chave . '.json');

			return $mensagem[0];
		}
		else{
			return null;
		}
	}

	function salvarCache($nomeCache, $mensagem){
		$chave = md5(strtolower($nomeCache));

		$dados = array(
			0 => $mensagem
		);

		salvarDados(CACHE_PASTA . $chave . '.json', $dados);
	}

	function manipularErros($erroCodigo, $erroMensagem = null, $erroArquivo = null, $erroLinha = null){
    if(error_reporting() == 0){
      return null;
    }

    if(func_num_args() == 5){
      $excecao = null;

      list($erroCodigo, $erroMensagem, $erroArquivo, $erroLinha) = func_get_args();
    }
    else{
            $excecao = func_get_arg(0);
         $erroCodigo = $excecao->getCode();
       $erroMensagem = $excecao->getMessage();
        $erroArquivo = $excecao->getFile();
          $erroLinha = $excecao->getLine();
    }

    $erroTipo = array(
          E_COMPILE_ERROR => 'COMPILE ERROR',
        E_COMPILE_WARNING => 'COMPILE WARNING',
             E_CORE_ERROR => 'CORE ERROR',
           E_CORE_WARNING => 'CORE WARNING',
                  E_ERROR => 'ERROR',
                 E_NOTICE => 'NOTICE',
                  E_PARSE => 'PARSING ERROR',
      E_RECOVERABLE_ERROR => 'RECOVERABLE ERROR',
                 E_STRICT => 'STRICT NOTICE',
             E_USER_ERROR => 'USER ERROR',
            E_USER_NOTICE => 'USER NOTICE',
           E_USER_WARNING => 'USER WARNING',
                E_WARNING => 'WARNING'
  	);

    if(array_key_exists($erroCodigo, $erroTipo)){
      $erroEncontrado = $erroTipo[$erroCodigo];
    }
    else{
      $erroEncontrado = 'CAUGHT EXCEPTION';
    }

    $mensagem  = '<pre>🐞 ERRO ENCONTRADO</pre>'							. "\n\n";
    $mensagem .= '<b>Tipo:</b> '				. $erroEncontrado			. "\n";
    $mensagem .= '<b>Arquivo:</b> '			. $erroArquivo				. "\n";
    $mensagem .= '<b>Linha:</b> '				. $erroLinha					. "\n";
    $mensagem .= '<b>Descrição:</b> '		. $erroMensagem				. "\n";
    $mensagem .= '<b>Data e Hora:</b> ' . date('d/m/Y H:i:s') . "\n";

    echo '🐞  ERRO: ' . $erroMensagem . ' no arquivo ' . $erroArquivo . ' (Linha ' . $erroLinha . ')' . "\n\n";

		foreach(SUDOS as $sudo){
			sendMessage($sudo, $mensagem, null, null, true);
		}
  }

	set_error_handler('manipularErros');
