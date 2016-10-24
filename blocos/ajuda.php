<?php
	if(IDIOMA == 'pt'){
		$mensagem = "/ajuda - Exibir esta mensagem
/idioma - Trocar idioma

/id - Obter minhas informações
/ranking - Ranking de mensagens do grupo
/rkgdel - Apagar ranking do grupo

/stop - Parar bot
";
	}
	else 	if(IDIOMA == 'en'){
		$mensagem = "/help - Show this message
/language - Change language

/id - Get my information
/ranking - Ranking group messages
/rkgdel - Delete ranking group

/stop - Stop bot
";
		}
	else 	if(IDIOMA == 'es'){
		$mensagem = "/ayuda - Mostrar este mensaje
/lingua - Cambiar el idioma

/id - Obtener información de mi
/ranking - Ranking mensajes del grupo
/rkgdel - Eliminar ranking del grupo

/stop - Detener bot
";
	}
	else 	if(IDIOMA == 'it'){
		$mensagem = "/aiuto - Visualizza questo messaggio
/lingua - Cambia lingua

/id - Ottenere le mie informazioni
/ranking - Ranking messaggi del gruppo
/rkgdel - Elimina ranking del gruppo

/stop - Fermarsi bot
";
	}

	if($mensagens['message']['chat']['type'] == 'private'){
		sendMessage($mensagens['message']['from']['id'], $mensagem, $mensagens['message']['message_id']);
	}
	else if($mensagens['message']['chat']['type'] == 'group' OR $mensagens['message']['chat']['type'] == 'supergroup'){
		$resultado = sendMessage($mensagens['message']['from']['id'], $mensagem);

		if($resultado['ok'] == false){
			$mensagem = ERROS[IDIOMA]['BOT_BLOCK'];
		}
		else{
			$mensagem = AJUDA[IDIOMA];
		}

		sendMessage($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'], null, true);
	}

	die();
