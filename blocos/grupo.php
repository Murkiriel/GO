<?php
	$mensagem = BEMVINDO[$idioma]['AJUDA'] . "\n\n" . REGRAS[$idioma]['AJUDA'];

	$teclado = [
								'inline_keyboard'	=>	[
																				[
																					['text' => '🔙', 'callback_data' => '/start']
																				]
																			]
							];

	$replyMarkup = json_encode($teclado);

	sendMessage($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'],
							$replyMarkup, true, $mensagens['edit_message']
	);
