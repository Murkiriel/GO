<?php
	$olaFulano = '';

	if ($mensagens['message']['chat']['type'] == 'group' OR $mensagens['message']['chat']['type'] == 'supergroup') {
		$olaFulano = ' ' . $mensagens['message']['chat']['title'];
	}

	if ($idioma == 'PT') {
		$mensagem = 'Olá' . $olaFulano . '! Veja abaixo os comandos disponíveis:

#Pesquisas
/dm - Pesquisar no Dailymotion
/duck - Pesquisar no DuckDuckGO
/wiki - Pesquisar na Wikipédia

#Arquivos
/livros - Pesquisar livros
/tv - Pesquisar filmes e séries
/psp - Pesquisar PSP
/snes - Pesquisar SNES
/store - Pesquisar apps

#Utilidades
/calc - Calcular expressão matemática
/id - Obter minhas informações
/gerar - Gerar número aleatório
/md5 - Gerar MD5 de algo
/sha512 - Gerar SHA512 de algo

#Grupo
/adms - Ver administradores
/ranking - Ranking de mensagens do grupo

/suporte - Enviar mensagem para o suporte';
	} else if ($idioma == 'EN') {
		$mensagem = 'Hi' . $olaFulano . '! See below the available commands:

#Searchs
/dm - Search Dailymotion
/duck - Search DuckDuckGO
/wiki - Search Wikipedia

#Files
/books - Search books
/tv - Search movies and series
/psp - Search PSP
/snes - Search SNES
/store - Search apps

#Utilities
/calc - Calculate mathematical expression
/id - Get my information
/generate - Generate random number
/md5 - Generate MD5 of something
/sha512 - Generate SHA512 of something

#Group
/adms - See administrators
/ranking - Ranking group messages

/support - Send message to support';
		} else if ($idioma == 'ES') {
		$mensagem = '¡Hola' . $olaFulano . '! Vea a continuación los comandos disponibles:

#Busquedas
/dm - Busca Dailymotion
/duck - Busca DuckDuckGO
/wiki - Busca Wikipedia

#Archivos
/libros - Buscar libros
/tv - Buscar películas y series
/psp - Buscar PSP
/snes - Buscar SNES
/store - Buscar apps

#Utilidades
/calc - Calcular la expresión matemática
/id - Obtener información de mi
/generar - Generar número aleatorio
/md5 -Generar el MD5 de algo
/sha512 - Generar el SHA512 de algo

#Grupo
/adms - Ver administradores
/ranking - Ranking mensajes del grupo

/apoyo - enviar mensaje de apoyo';
	} else if ($idioma == 'IT') {
		$mensagem = 'Ciao' . $olaFulano . '! Vedi sotto i comandi disponibili:

#Ricerche
/dm - Ricerca Dailymotion
/duck - Ricerca DuckDuckGO
/wiki - Ricerca Wikipedia

#Archivos
/libri - Ricerca libri
/tv - Ricerca film e serie
/psp - Ricerca PSP
/snes - Ricerca SNES
/store - Ricerca apps

#Utilita
/calc - Calcola espressione matematica
/id - Ottenere le mie informazioni
/generare - Generare il numero casuale
/md5 -Generare il MD5 di qualcosa
/sha512 - Generare il SHA512 di qualcosa

#Gruppo
/adms - Vedere gli amministratori
/ranking - Ranking messaggi del gruppo

/supporto - Invia messaggio a supporto';
	}

	$teclado = [
								'inline_keyboard'	=>	[
																				[
																					['text' => '⭐️ ' . AJUDA[$idioma]['TCD_AVALR'] . ' ' . DADOS_BOT['result']['first_name'],
																						'url' => 'https://telegram.me/storebot?start=' . DADOS_BOT['result']['username']],
																					['text' => '↗️ ' . AJUDA[$idioma]['TCD_GRUPO'],
																						'url' => 'https://telegram.me/' . DADOS_BOT['result']['username'] . '?startgroup=new']
																				],
																				[
																					['text' => '🌎 ' . $idioma, 'callback_data' => '/idioma'],
																					['text' => '👥 ' . AJUDA[$idioma]['GRUPO'], 'callback_data' => '/grupo' ]
																				],
																				[
																					['text' => '📖 Info', 'callback_data' => '/info' ]
																				]
																			]
							];

	$replyMarkup = json_encode($teclado);

	sendMessage($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'], $replyMarkup, FALSE, $mensagens['edit_message']);
