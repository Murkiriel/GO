<?php
	if($mensagens['message']['chat']['type'] == 'private'){
		$olaFulano = $mensagens['message']['from']['first_name'];
	}
	else if($mensagens['message']['chat']['type'] == 'group' OR $mensagens['message']['chat']['type'] == 'supergroup'){
		$olaFulano = $mensagens['message']['chat']['title'];
	}

	if($mensagens['IDIOMA'] == 'PT'){
		$mensagem = 'Olá <b>' . $olaFulano . '</b>! Veja abaixo os comandos disponíveis:

/calc - Calcular expressão matemática
/id - Obter minhas informações
/livros - Pesquisar livros
/store - Pesquisar apps
/wiki - Pesquisar na Wikipédia

/ranking - Ranking de mensagens do grupo
/rkgdel - Apagar ranking do grupo

/stop - Parar bot';
	}
	else 	if($mensagens['IDIOMA'] == 'EN'){
		$mensagem = 'Hi <b>' . $olaFulano . '</b>! See below the available commands:

/books - Search books
/calc - Calculate mathematical expression
/id - Get my information
/store - Search apps
/wiki - Search Wikipedia

/ranking - Ranking group messages
/rkgdel - Delete ranking group

/stop - Stop bot';
		}
	else 	if($mensagens['IDIOMA'] == 'ES'){
		$mensagem = '¡Hola <b>' . $olaFulano . '</b>! Vea a continuación los comandos disponibles:

/calc - Calcular la expresión matemática
/id - Obtener información de mi
/libros - Buscar libros
/store - Buscar apps
/wiki - Busca Wikipedia

/ranking - Ranking mensajes del grupo
/rkgdel - Eliminar ranking del grupo

/stop - Detener bot';
	}
	else 	if($mensagens['IDIOMA'] == 'IT'){
		$mensagem = 'Ciao <b>' . $olaFulano . '</b>! Vedi sotto i comandi disponibili:

/calc - Calcola espressione matematica
/id - Ottenere le mie informazioni
/libri - Ricerca libri
/store - Ricerca apps
/wiki - Ricerca Wikipedia

/ranking - Ranking messaggi del gruppo
/rkgdel - Elimina ranking del gruppo

/stop - Fermarsi bot';
	}

	$teclado =	[
								'inline_keyboard'	=>	[
																				[
																					['text' =>  '⭐️ ' . AJUDA[$mensagens['IDIOMA']]['TCD_AVALR'] . ' ' . DADOS_BOT['result']['first_name'],
																						'url' => 'https://telegram.me/storebot?start=' 		 . DADOS_BOT['result']['username'] ],
																					['text' =>  '👥 ' . AJUDA[$mensagens['IDIOMA']]['TCD_GRUPO'																					],
																						'url' => 'https://telegram.me/' . DADOS_BOT['result']['username'] . '?startgroup=new']
																				],
																				[
																					['text' =>  '🌎 '	. $mensagens['IDIOMA']	, 'callback_data' => '/idioma'],
																					['text' =>  '📖 Info'				, 'callback_data' => '/info'	]
																				]
																			]
							];

	$replyMarkup = json_encode($teclado);

	sendMessage($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'], $replyMarkup, true);
