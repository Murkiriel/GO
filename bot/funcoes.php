<?php
	function getMe(){
		return json_decode(file_get_contents(API_BOT . '/getMe', false, CONTEXTO), true);
	}

	function getUpdates($updateID){
		return json_decode(file_get_contents(API_BOT . '/getUpdates?timeout=20&offset=' . $updateID, false, CONTEXTO), true);
	}

	function getChatAdministrators($chatID){
		$requisicao = API_BOT . '/getChatAdministrators?';

		$conteudoRequisicao = array(
			'chat_id' => $chatID
		);

		$requisicao .= http_build_query($conteudoRequisicao, '', '&');

		return json_decode(file_get_contents($requisicao, false, CONTEXTO), true);
	}

	function getUserProfilePhotos($userID){
		$requisicao = API_BOT . '/getUserProfilePhotos?';

		$conteudoRequisicao = array(
			'user_id' => $userID,
				'limit' => 1
		);

		$requisicao .= http_build_query($conteudoRequisicao, '', '&');

		return json_decode(file_get_contents($requisicao, false, CONTEXTO), true);
	}

	function sendChatAction($chatID, $action){
		return file_get_contents(API_BOT . '/sendChatAction?chat_id=' . $chatID . '&action=' . $action, false, CONTEXTO);
	}

	function sendDocument($chatID, $document, $replyMessage = null, $replyMarkup = null, $caption = null, $disableNotification = true){
		$requisicao = API_BOT . '/sendDocument?';

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

		$requisicao .= http_build_query($conteudoRequisicao, '', '&');

		return json_decode(file_get_contents($requisicao, false, CONTEXTO), true);
	}

	function sendMessage($chatID, $text, $replyMessage = null, $replyMarkup = null, $parseMode = false, $disablePreview = true, $disableNotification = false){
		$requisicao = API_BOT . '/sendMessage?';

		$conteudoRequisicao = array(
			'chat_id'	=> $chatID,
				 'text' => $text
		);

		if(isset($replyMessage)){
			$conteudoRequisicao['reply_to_message_id'] = $replyMessage;
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

		if($disableNotification == true){
			$conteudoRequisicao['disable_notification'] = true;
		}

		$requisicao .= http_build_query($conteudoRequisicao, '', '&');

		return json_decode(file_get_contents($requisicao, false, CONTEXTO), true);
	}

	function sendPhoto($chatID, $photo, $replyMessage = null, $replyMarkup = null , $caption = null, $disableNotification = false){
		$requisicao = API_BOT . '/sendPhoto?';

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

		$requisicao .= http_build_query($conteudoRequisicao, '', '&');

		return json_decode(file_get_contents($requisicao, false, CONTEXTO), true);
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

	function manipularErros($erroCodigo, $erroMensagem = null, $erroArquivo = null, $erroLinha = null){
    if(error_reporting() == 0){
      return null;
    }

		global $_CONFIG;

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

    $mensagem  = '<pre>🐞  ERRO ENCONTRADO</pre>'				. "\n\n";
    $mensagem .= '<b>Tipo:</b> '				. $erroEncontrado	. "\n";
    $mensagem .= '<b>Arquivo:</b> '		. $erroArquivo		. "\n";
    $mensagem .= '<b>Linha:</b> '			. $erroLinha			. "\n";
    $mensagem .= '<b>Descrição:</b> '	. $erroMensagem		. "\n";
    $mensagem .= '<b>Data:</b> ' . date('d/m/Y H:i:s')		. "\n";

    echo '🐞  ERRO: ' . $erroMensagem . ' no arquivo ' . $erroArquivo . ' (Linha ' . $erroLinha . ')' . "\n\n";

		sendMessage(SUDOS['0'], $mensagem, null, null, true);
  }

	set_error_handler('manipularErros');
