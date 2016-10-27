<?php
	if($IDIOMA == 'pt'){
		$mensagem = '/ajuda - Exibir esta mensagem
/idioma - Trocar idioma

/calc - Calcular expressão matemática
/id - Obter minhas informações
/wiki - Pesquisar na Wikipédia
/ranking - Ranking de mensagens do grupo
/rkgdel - Apagar ranking do grupo

/stop - Parar bot';
	}
	else 	if($IDIOMA == 'en'){
		$mensagem = '/help - Show this message
/language - Change language

/calc - Calculate mathematical expression
/id - Get my information
/wiki - Searching Wikipedia
/ranking - Ranking group messages
/rkgdel - Delete ranking group

/stop - Stop bot';
		}
	else 	if($IDIOMA == 'es'){
		$mensagem = '/ayuda - Mostrar este mensaje
/lingua - Cambiar el idioma

/calc - Calcular la expresión matemática
/id - Obtener información de mi
/wiki - Busca Wikipedia
/ranking - Ranking mensajes del grupo
/rkgdel - Eliminar ranking del grupo

/stop - Detener bot';
	}
	else 	if($IDIOMA == 'it'){
		$mensagem = '/aiuto - Visualizza questo messaggio
/lingua - Cambia lingua

/calc - Calcola espressione matematica
/id - Ottenere le mie informazioni
/wiki - Ricerca Wikipedia
/ranking - Ranking messaggi del gruppo
/rkgdel - Elimina ranking del gruppo

/stop - Fermarsi bot';
	}

	if($mensagens['message']['chat']['type'] == 'private'){
		sendMessage($mensagens['message']['from']['id'], $mensagem, $mensagens['message']['message_id']);
	}
	else if($mensagens['message']['chat']['type'] == 'group' OR $mensagens['message']['chat']['type'] == 'supergroup'){
		$resultado = sendMessage($mensagens['message']['from']['id'], $mensagem);

		if($resultado['ok'] == false){
			$mensagem = ERROS[$IDIOMA]['BOT_BLOCK'];
		}
		else{
			$mensagem = AJUDA[$IDIOMA];
		}

		sendMessage($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'], null, true);
	}
