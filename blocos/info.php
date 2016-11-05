<?php
	$mensagem = '<pre>INFO ' . strtoupper(DADOS_BOT['result']['first_name']) . '</pre>' . "\n\n" .
							'<b>'				 . INFO[$mensagens['IDIOMA']]['VERSAO']											 . ':</b> ' . VERSAO;

	$teclado =	[
								'inline_keyboard'	=>	[
																				[
																					['text' =>  '☕️ GitHub',
																						'url' => 'https://github.com/Murkiriel/GO'],
																					['text' =>  '📢 ' . INFO[$mensagens['IDIOMA']]['CANAL'],
																						'url' => 'https://telegram.me/Murkiriel']
																				],
																				[
																					['text' =>  '🔙'	, 'callback_data' => '/start'	]
																				]
																			]
							];

	$replyMarkup = json_encode($teclado);

	sendMessage($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'], $replyMarkup, true);
