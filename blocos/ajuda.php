<?php
	$olaFulano = '';

	if ($mensagens['message']['chat']['type'] == 'group' OR $mensagens['message']['chat']['type'] == 'supergroup') {
		$olaFulano = ' <b>' . $mensagens['message']['chat']['title'] . '</b>';
	}

	if ($idioma == 'PT') {
		$mensagem = 'Olá' . $olaFulano . '! Veja abaixo os comandos disponíveis:

/calc - Calcular expressão matemática
/id - Obter minhas informações
/livros - Pesquisar livros
/tv - Pesquisar filmes e séries
/store - Pesquisar apps
/wiki - Pesquisar na Wikipédia

/ranking - Ranking de mensagens do grupo
/rkgdel - Apagar ranking do grupo';
	} else if ($idioma == 'EN') {
		$mensagem = 'Hi' . $olaFulano . '! See below the available commands:

/books - Search books
/calc - Calculate mathematical expression
/id - Get my information
/tv - Search movies and series
/store - Search apps
/wiki - Search Wikipedia

/ranking - Ranking group messages
/rkgdel - Delete ranking group';
		} else if ($idioma == 'ES') {
		$mensagem = '¡Hola' . $olaFulano . '! Vea a continuación los comandos disponibles:

/calc - Calcular la expresión matemática
/id - Obtener información de mi
/libros - Buscar libros
/tv - Buscar películas y series
/store - Buscar apps
/wiki - Busca Wikipedia

/ranking - Ranking mensajes del grupo
/rkgdel - Eliminar ranking del grupo';
	} else if ($idioma == 'IT') {
		$mensagem = 'Ciao' . $olaFulano . '! Vedi sotto i comandi disponibili:

/calc - Calcola espressione matematica
/id - Ottenere le mie informazioni
/libri - Ricerca libri
/tv - Ricerca film e serie
/store - Ricerca apps
/wiki - Ricerca Wikipedia

/ranking - Ranking messaggi del gruppo
/rkgdel - Elimina ranking del gruppo';
	}

	$teclado = [
								'inline_keyboard'	=>	[
																				[
																					['text' => '⭐️ ' . AJUDA[$idioma]['TCD_AVALR'] . ' ' . DADOS_BOT['result']['first_name'],
																						'url' => 'https://telegram.me/storebot?start=' . DADOS_BOT['result']['username']],
																					['text' => '👥 ' . AJUDA[$idioma]['TCD_GRUPO'],
																						'url' => 'https://telegram.me/' . DADOS_BOT['result']['username'] . '?startgroup=new']
																				],
																				[
																					['text' => '🌎 ' . $idioma, 'callback_data' => '/idioma'],
																					['text' => '📖 Info', 'callback_data' => '/info' ]
																				]
																			]
							];

	$replyMarkup = json_encode($teclado);

	sendMessage($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'], $replyMarkup, TRUE, $mensagens['edit_message']);
