<?php
	$mensagem = '<pre>INFO ' . strtoupper(DADOS_BOT['result']['first_name']) . '</pre>' . "\n\n" .
										 '<b>' . INFO[$idioma]['VERSAO'] . ':</b> ' . VERSAO;

	$teclado = [
								'inline_keyboard'	=>	[
																				[
																					['text' => '☕️ GitHub', 'url' => 'https://github.com/Murkiriel/GO'],
																					['text' => '📢 ' . INFO[$idioma]['CANAL'], 'url' => 'https://t.me/Murkiriel']
																				],
																				[
																					['text' => '🔙', 'callback_data' => '/start ajuda']
																				]
																			]
							];

	$replyMarkup = json_encode($teclado);

	sendMessage($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'],
							$replyMarkup, true, $mensagens['edit_message']);
