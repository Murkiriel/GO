<?php
	/**
	 * @param integer $updateID
	 */
	function getUpdates($updateID) {
		$requisicao = API_BOT . '/getUpdates';

		$conteudoRequisicao = [
			'offset'	=> $updateID,
			'timeout'	=> 20
		];

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
		$conteudoRequisicao = array(
			'chat_id'	=> $chatID,
				 'text' => $text
		);

		if ($editarMensagem === false) {
			$requisicao = API_BOT . '/sendMessage';

			if (isset($replyMessage)) {
				$conteudoRequisicao['reply_to_message_id'] = $replyMessage;
			}
		} else {
			$requisicao = API_BOT . '/editMessageText';

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
