<?php
	if($IDIOMA == 'pt'){
<<<<<<< HEAD
		$mensagem = '/ajuda - Exibir esta mensagem
=======
		$mensagem = "/ajuda - Exibir esta mensagem
>>>>>>> origin/master
/idioma - Trocar idioma

/calc - Calcular expressão matemática
/id - Obter minhas informações
/wiki - Pesquisar na Wikipédia
/ranking - Ranking de mensagens do grupo
/rkgdel - Apagar ranking do grupo

/stop - Parar bot';
	}
	else 	if($IDIOMA == 'en'){
<<<<<<< HEAD
		$mensagem = '/help - Show this message
=======
		$mensagem = "/help - Show this message
>>>>>>> origin/master
/language - Change language

/calc - Calculate mathematical expression
/id - Get my information
/wiki - Searching Wikipedia
/ranking - Ranking group messages
/rkgdel - Delete ranking group

/stop - Stop bot';
		}
	else 	if($IDIOMA == 'es'){
<<<<<<< HEAD
		$mensagem = '/ayuda - Mostrar este mensaje
=======
		$mensagem = "/ayuda - Mostrar este mensaje
>>>>>>> origin/master
/lingua - Cambiar el idioma

/calc - Calcular la expresión matemática
/id - Obtener información de mi
/wiki - Busca Wikipedia
/ranking - Ranking mensajes del grupo
/rkgdel - Eliminar ranking del grupo

/stop - Detener bot';
	}
	else 	if($IDIOMA == 'it'){
<<<<<<< HEAD
		$mensagem = '/aiuto - Visualizza questo messaggio
=======
		$mensagem = "/aiuto - Visualizza questo messaggio
>>>>>>> origin/master
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
