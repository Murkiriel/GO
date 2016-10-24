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

	function sendMessage($chatID,$text,$replyMessage=null,$replyMarkup=null,$parseMode=false,$disablePreview=true,$disableNotification=false){
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
