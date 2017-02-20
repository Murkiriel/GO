<?php

// # BEM-VINDO

if (isset($mensagens['message']['new_chat_participant'])) {
	if ($redis->hget('bemvindo:' . $mensagens['message']['chat']['id'], 'ativo') === 'true') {
		$tipo = $redis->hget('bemvindo:' . $mensagens['message']['chat']['id'], 'tipo');
		$mensagem = $redis->hget('bemvindo:' . $mensagens['message']['chat']['id'], 'conteudo');

		if ($tipo == 'documento') {
			sendDocument($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'], null, null);
		} else if ($tipo == 'foto') {
			sendPhoto($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'], null, null);
		} else {
			$replyMarkup = montarTeclado($mensagem);
			$mensagem = removerTeclado($mensagem);

			$mensagem = str_ireplace('$nome', $mensagens['message']['new_chat_participant']['first_name'],
																			 $mensagem);
			$mensagem = str_ireplace('$grupo', $mensagens['message']['chat']['title'],
																			 $mensagem);

			if (isset($mensagens['message']['new_chat_participant']['username'])) {
				$mensagem = str_ireplace('$usuario', '@' . $mensagens['message']['new_chat_participant']['username'], $mensagem);
			} else {
				$mensagem = str_ireplace('$usuario', $mensagens['message']['new_chat_participant']['first_name'], $mensagem);
			}

			sendMessage($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'], $replyMarkup, true);
		}
	}
}

	// # DOCUMENTOS

	foreach ($redis->keys('documentos:*') as $hash) {
		if ($redis->hexists($hash, $mensagens['message']['text']) === true) {
			$teclado['hide_keyboard'] = true;

			$replyMarkup = json_encode($teclado);

			$idDocumento = $redis->hget($hash, $mensagens['message']['text']);

			sendChatAction($mensagens['message']['chat']['id'], 'upload_document');

			if ($mensagens['message']['chat']['type'] == 'group' or $mensagens['message']['chat']['type'] == 'supergroup') {
				sendDocument($mensagens['message']['chat']['id'], $idDocumento,
										 $mensagens['message']['message_id'], $replyMarkup);
			}

			sendDocument($mensagens['message']['from']['id'], $idDocumento,
									 $mensagens['message']['message_id'], $replyMarkup);

			break;
		}
	}

	if ($mensagens['message']['chat']['type'] == 'private' and isset($mensagens['message']['document']['mime_type'])) {
		if (in_array($mensagens['message']['from']['id'], SUDOS)) {
			if (substr($mensagens['message']['document']['file_name'], -4) == '.apk' or
					substr($mensagens['message']['document']['file_name'], -4) == '.obb') {
				$redis->hset('documentos:store', $mensagens['message']['document']['file_name'], $mensagens['message']['document']['file_id']);

				$mensagem = '<b> 📱 APK/OBB ADICIONADO 📱 </b>' . "\n\n" .
										'<b>Nome:</b> ' . $mensagens['message']['document']['file_name'] . "\n" .
										'<b>ID:</b> ' . $mensagens['message']['document']['file_id'];

				notificarSudos($mensagem);
			} else if (substr($mensagens['message']['document']['file_name'], -4) == '.pdf' or
								 substr($mensagens['message']['document']['file_name'], -5) == '.epub' or
								 substr($mensagens['message']['document']['file_name'], -5) == '.mobi') {
				$redis->hset('documentos:livros', $mensagens['message']['document']['file_name'], $mensagens['message']['document']['file_id']);

				$mensagem = '<b> 📱 LIVRO ADICIONADO 📱 </b>' . "\n\n" .
										'<b>Nome:</b> ' . $mensagens['message']['document']['file_name'] . "\n" .
										'<b>ID:</b> ' . $mensagens['message']['document']['file_id'];

				notificarSudos($mensagem);
			} else if (substr($mensagens['message']['document']['file_name'], -4) == '.mkv' or
								 substr($mensagens['message']['document']['file_name'], -4) == '.mp4' or
								 substr($mensagens['message']['document']['file_name'], -4) == '.avi') {
				$redis->hset('documentos:tv', $mensagens['message']['document']['file_name'], $mensagens['message']['document']['file_id']);

				$mensagem = '<b> 📱 VÍDEO ADICIONADO 📱 </b>' . "\n\n" .
										'<b>Nome:</b> ' . $mensagens['message']['document']['file_name'] . "\n" .
										'<b>ID:</b> ' . $mensagens['message']['document']['file_id'];

				notificarSudos($mensagem);
			} else if (substr($mensagens['message']['document']['file_name'], -4) == '.cso') {
				$redis->hset('documentos:psp', $mensagens['message']['document']['file_name'], $mensagens['message']['document']['file_id']);

				$mensagem = '<b> 📱 PSP ADICIONADO 📱 </b>' . "\n\n" .
										'<b>Nome:</b> ' . $mensagens['message']['document']['file_name'] . "\n" .
										'<b>ID:</b> ' . $mensagens['message']['document']['file_id'];

				notificarSudos($mensagem);
			} else if (substr($mensagens['message']['document']['file_name'], -4) == '.smc') {
				$redis->hset('documentos:snes', $mensagens['message']['document']['file_name'], $mensagens['message']['document']['file_id']);

				$mensagem = '<b> 📱 SNES ADICIONADO 📱 </b>' . "\n\n" .
										'<b>Nome:</b> ' . $mensagens['message']['document']['file_name'] . "\n" .
										'<b>ID:</b> ' . $mensagens['message']['document']['file_id'];

				notificarSudos($mensagem);
			}
		}
	}

	// # STATUS

	if ($mensagens['message']['chat']['type'] == 'private' or $mensagens['message']['chat']['type'] == 'group') {
		$redis->set('status_bot:privateorgroup', $mensagens['message']['message_id']);
	} else if ($mensagens['message']['chat']['type'] == 'supergroup') {
		$redis->set('status_bot:supergroup', $mensagens['message']['message_id']);
	}

/*
	// # RSS SERVICOS

	$link = '';

	switch (strtolower($texto[0])) {
		case 'futebolgeral':
			$link = 'https://esportes.yahoo.com/futebol/?format=rss';
			break;
		case 'brasileiroa':
			$link = 'https://esportes.yahoo.com/futebol/campeonato-brasileiro/?format=rss';
			break;
		case 'brasileirob':
			$link = 'https://esportes.yahoo.com/futebol/campeonato-brasileiro/s%C3%A9rie-b/?format=rss';
			break;
		case 'paulista':
			$link = 'https://esportes.yahoo.com/futebol/campeonato-paulista/?format=rss';
			break;
		case 'carioca':
			$link = 'https://esportes.yahoo.com/futebol/estadual-do-rio/?format=rss';
			break;
		case 'mineiro':
			$link = 'https://esportes.yahoo.com/futebol/campeonato-mineiro/?format=rss';
			break;
		case 'gaucho':
			$link = 'https://esportes.yahoo.com/futebol/campeonato-ga%C3%BAcho/?format=rss';
			break;
		case 'copabrasil':
			$link = 'https://esportes.yahoo.com/futebol/copa-brasil/?format=rss';
			break;
		case 'libertadores':
			$link = 'https://esportes.yahoo.com/futebol/copa-libertadores/?format=rss';
			break;
		case 'sulamericana':
			$link = 'https://esportes.yahoo.com/futebol/copa-sul-americana/?format=rss';
			break;
		case 'champions':
			$link = 'https://esportes.yahoo.com/futebol/liga-campe%C3%B5es/?format=rss';
			break;
		case 'ingles':
			$link = 'https://esportes.yahoo.com/futebol/campeonato-ingl%C3%AAs/?format=rss';
			break;
		case 'espanha':
			$link = 'https://esportes.yahoo.com/futebol/campeonato-espanhol/?format=rss';
			break;
		case 'italia':
			$link = 'https://esportes.yahoo.com/futebol/campeonato-italiano/?format=rss';
			break;
		case 'alemao':
			$link = 'https://esportes.yahoo.com/futebol/campeonato-alem%C3%A3o/?format=rss';
			break;
		case 'portugal':
			$link = 'https://esportes.yahoo.com/futebol/campeonato-portugu%C3%AAs/?format=rss';
			break;
		case 'abc':
			$link = 'https://esportes.yahoo.com/futebol/abc/?format=rss';
			break;
		case 'americarn':
			$link = 'https://esportes.yahoo.com/futebol/am%C3%A9rica-rn/?format=rss';
			break;
		case 'atleticomg':
			$link = 'https://esportes.yahoo.com/futebol/atl%C3%A9tico-mineiro/?format=rss';
			break;
		case 'atleticopr':
			$link = 'https://esportes.yahoo.com/futebol/atl%C3%A9tico-paranaense/?format=rss';
			break;
		case 'bahia':
			$link = 'https://esportes.yahoo.com/futebol/bahia/?format=rss';
			break;
		case 'botafogo':
			$link = 'https://esportes.yahoo.com/futebol/botafogo/?format=rss';
			break;
		case 'ceara':
			$link = 'https://esportes.yahoo.com/futebol/cear%C3%A1/?format=rss';
			break;
		case 'corinthians':
			$link = 'https://esportes.yahoo.com/futebol/corinthians/?format=rss';
			break;
		case 'coritiba':
			$link = 'https://esportes.yahoo.com/futebol/coritiba/?format=rss';
			break;
		case 'criciuma':
			$link = 'https://esportes.yahoo.com/futebol/crici%C3%BAma/?format=rss';
			break;
		case 'cruzeiro':
			$link = 'https://esportes.yahoo.com/futebol/cruzeiro/?format=rss';
			break;
		case 'flamengo':
			$link = 'https://esportes.yahoo.com/futebol/flamengo/?format=rss';
			break;
		case 'fluminense':
			$link = 'https://esportes.yahoo.com/futebol/fluminense/?format=rss';
			break;
		case 'fortaleza':
			$link = 'https://esportes.yahoo.com/futebol/fortaleza/?format=rss';
			break;
		case 'goias':
			$link = 'https://esportes.yahoo.com/futebol/goi%C3%A1s/?format=rss';
			break;
		case 'gremio':
			$link = 'https://esportes.yahoo.com/futebol/gr%C3%AAmio/?format=rss';
			break;
		case 'internacional':
			$link = 'https://esportes.yahoo.com/futebol/internacional/?format=rss';
			break;
		case 'nautico':
			$link = 'https://esportes.yahoo.com/futebol/n%C3%A1utico/?format=rss';
			break;
		case 'palmeiras':
			$link = 'https://esportes.yahoo.com/futebol/palmeiras/?format=rss';
			break;
		case 'pontepreta':
			$link = 'https://esportes.yahoo.com/futebol/ponte-preta/?format=rss';
			break;
		case 'portuguesa':
			$link = 'https://esportes.yahoo.com/futebol/portuguesa/?format=rss';
			break;
		case 'santacruz':
			$link = 'https://esportes.yahoo.com/futebol/santa-cruz-fc/?format=rss';
			break;
		case 'santos':
			$link = 'https://esportes.yahoo.com/futebol/santos/?format=rss';
			break;
		case 'saopaulo':
			$link = 'https://esportes.yahoo.com/futebol/s%C3%A3o-paulo/?format=rss';
			break;
		case 'sport':
			$link = 'https://esportes.yahoo.com/futebol/sport/?format=rss';
			break;
		case 'vascodagama':
			$link = 'https://esportes.yahoo.com/futebol/vasco/?format=rss';
			break;
		case 'vitoria':
			$link = 'https://esportes.yahoo.com/futebol/vit%C3%B3ria/?format=rss';
			break;
		case 'arsenal':
			$link = 'https://esportes.yahoo.com/futebol/arsenal/?format=rss';
			break;
		case 'barcelona':
			$link = 'https://esportes.yahoo.com/futebol/barcelona/?format=rss';
			break;
		case 'bayerndemunique':
			$link = 'https://esportes.yahoo.com/futebol/bayern-munique/?format=rss';
			break;
		case 'borussiadortmund':
			$link = 'https://esportes.yahoo.com/futebol/borussia/?format=rss';
			break;
		case 'chelsea':
			$link = 'https://esportes.yahoo.com/futebol/chelsea/?format=rss';
			break;
		case 'interdemilao':
			$link = 'https://esportes.yahoo.com/futebol/inter-mil%C3%A3o/?format=rss';
			break;
		case 'juventus':
			$link = 'https://esportes.yahoo.com/futebol/juventus/?format=rss';
			break;
		case 'liverpool':
			$link = 'https://esportes.yahoo.com/futebol/liverpool/?format=rss';
			break;
		case 'manchestercity':
			$link = 'https://esportes.yahoo.com/futebol/manchester-city/?format=rss';
			break;
		case 'manchesterunited':
			$link = 'https://esportes.yahoo.com/futebol/manchester-united/?format=rss';
			break;
		case 'milan':
			$link = 'https://esportes.yahoo.com/futebol/milan/?format=rss';
			break;
		case 'parissaintgermain':
			$link = 'https://esportes.yahoo.com/futebol/psg/?format=rss';
			break;
		case 'realmadrid':
			$link = 'https://esportes.yahoo.com/futebol/real-madrid/?format=rss';
			break;
		case 'esportesgeral':
			$link = 'https://esportes.yahoo.com/?format=rss';
			break;
		case 'mma':
			$link = 'https://esportes.yahoo.com/mma/?format=rss';
			break;
		case 'tenis':
			$link = 'https://esportes.yahoo.com/t%C3%AAnis/?format=rss';
			break;
		case 'volei':
			$link = 'https://esportes.yahoo.com/v%C3%B4lei/?format=rss';
			break;
		case 'basquete':
			$link = 'https://esportes.yahoo.com/basquete/?format=rss';
			break;
		case 'pplware':
			$link = 'https://pplware.sapo.pt/feed/';
			break;
		case 'canaltech':
			$link = 'https://feeds2.feedburner.com/canaltechbr';
			break;
		case 'androidpit':
			$link = 'http://www.androidpit.com.br/feed/main.xml';
			break;
		case 'folha':
			$link = 'http://feeds.folha.uol.com.br/emcimadahora/rss091.xml';
			break;
		case 'g1':
			$link = 'http://pox.globo.com/rss/g1/';
			break;
		case 'terra':
			$link = 'http://noticias.terra.com.br/rss/Controller?channelid=e3c54b844f65e310VgnVCM3000009acceb0aRCRD&ctName=atomo-noticia';
			break;
	}

	if (!empty($link)) {
		try{
			$rss = new SimpleXmlElement(file_get_contents($link));
		} catch(Exception $e){
			$mensagem = 'Ocorreu um erro! Tente novamente mais tarde.';
		}

		if (isset($rss->channel->item)) {
			$mensagem = mensagemRSS($rss->channel->item);

			try {
				$redis->hset('rss:chats:' . $mensagens['message']['from']['id'], $link, md5($mensagem));
			} catch(Exception $e){
				$redis->hset('rss:chats:' . $mensagens['message']['chat']['id'], $link, md5($mensagem));
			}

			$mensagem = $mensagem . "\n\n" . 'O conteúdo do seu RSS aparecerá assim. Você será notificado à cada atualização.';
		}

		$teclado = [
								'inline_keyboard'	=>	[
																				[
																					['text' => '🔙', 'callback_data' => '/rss']
																				]
																			]
							];

		$replyMarkup = json_encode($teclado);

		sendMessage($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'],
								$replyMarkup, true, $mensagens['edit_message']
		);
	}
*/
