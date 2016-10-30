<?php
	define('SET_IDIOMA', array(
			'pt' => 'Entendido! Agora eu vou falar em <b>🇧🇷 Português</b> :]'."\n\n".'Use /ajuda para continuarmos',
			'en' => 'Okay! Now I will speak in <b>🇬🇧 English</b> :]'."\n\n".'Use /help to continue',
			'es' => '¡Está bien! Ahora voy a hablar en <b>🇪🇸 Español</b> :]'."\n\n".'Uso /ayuda para continuar',
			'it' => 'Va bene! Ora voglio parlare in <b>🇮🇹 Italiano</b> :]'."\n\n".'Usa /aiuto per continuare'
		));

		define('TECLADO', array(
			'pt' => 'Escolha entre as opções do <b>teclado</b>',
			'en' => 'Choose between <b>keyboard</b> options',
			'es' => 'Elija entre opciones de <b>teclado</b>',
			'it' => 'Scegliere tra le opzioni della <b>tastiera</b>'
		));

		define('AJUDA', array(
			'pt' => 'Eu enviei a mensagem para o seu <a href="telegram.me/' . DADOS_BOT['result']['username'] . '">privado</a>!',
			'en' => 'I sent you the message via <a href="telegram.me/' . DADOS_BOT['result']['username'] . '">private</a> message!',
			'es' => 'Te envié el mensaje a través de mensaje <a href="telegram.me/' . DADOS_BOT['result']['username'] . '">privado</a>!',
			'it' => 'Ti ho mandato il messaggio via messaggio <a href="telegram.me/' . DADOS_BOT['result']['username'] . '">privato</a>!'
		));

		define('ERROS', array(
			'pt' => array(
				'BOT_BLOCK' => 'Me envie uma mensagem no <a href="telegram.me/' . DADOS_BOT['result']['username'] . '">privado</a>!',
				'SMT_GRUPO' => 'Somente em <b>grupos</b>!',
				'SEM_RSULT' => 'Eu não encontrei resultados sobre isso :['
			),
			'en' => array(
				'BOT_BLOCK' => 'Send me a message in <a href="telegram.me/' . DADOS_BOT['result']['username'] . '">private</a>!',
				'SMT_GRUPO' => 'Only <b>groups</b>!',
				'SEM_RSULT' => 'I have not found results about it :['
			),
			'es' => array(
				'BOT_BLOCK' => 'Envíame un mensaje en <a href="telegram.me/' . DADOS_BOT['result']['username'] . '">privado!</a>',
				'SMT_GRUPO' => 'Sólo los <b>grupos</b>!',
				'SEM_RSULT' => 'No he encontrado resultados al respecto :['
			),
			'it' => array(
				'BOT_BLOCK' => 'Inviami un messaggio in <a href="telegram.me/' . DADOS_BOT['result']['username'] . '">privato</a>!',
				'SMT_GRUPO' => 'Solo i <b>gruppi</b>!',
				'SEM_RSULT' => 'Non ho trovato i risultati su di esso :['
			)
		));

		define('ID', array(
			'pt' => array(
				'NOME' => 'Nome',
				'MSGS' => 'Mensagens',
				'PRVD' => 'Você está no privado!'
			),
			'en' => array(
				'NOME' => 'Name',
				'MSGS' => 'Messages',
				'PRVD' => 'You are in private!'
			),
			'es' => array(
				'NOME' => 'Nombre',
				'MSGS' => 'Mensajes',
				'PRVD' => 'Estás en privado!'
			),
			'it' => array(
				'NOME' => 'Nome',
				'MSGS' => 'Messaggi',
				'PRVD' => 'Sei in privato!'
			)
		));

		define('RANKING', array(
			'pt' => array(
				'TITULO' => 'Ranking de Mensagens',
				'SMT_CRIADOR' => 'Apenas o criador do grupo pode excluir o ranking!'
			),
			'en' => array(
				'TITULO' => 'Ranking of Messages',
				'SMT_CRIADOR' => 'Only the creator of the group can delete the ranking!'
			),
			'es' => array(
				'TITULO' => 'Ranking de Mensajes',
				'SMT_CRIADOR' => 'Sólo el creador del grupo puede eliminar el ranking!'
			),
			'it' => array(
				'TITULO' => 'Ranking del Messaggio',
				'SMT_CRIADOR' => 'Solo il creatore del gruppo può eliminare la ranking!'
			)
		));
