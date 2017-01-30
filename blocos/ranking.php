<?php
	if ($mensagens['message']['chat']['type'] == 'group' OR $mensagens['message']['chat']['type'] == 'supergroup') {
		if (strcasecmp($mensagens['message']['text'], '/rkgdel') == 0 OR
				strcasecmp($mensagens['message']['text'], '/rkgdel' . '@' . DADOS_BOT['result']['username']) == 0 ) {
				 $rkgdel = FALSE;
 			$resultado = getChatAdministrators($mensagens['message']['chat']['id']);

 			foreach ($resultado['result'] as $adminsGrupo) {
 				if ($adminsGrupo['user']['id'] == $mensagens['message']['from']['id'] AND $adminsGrupo['status'] == 'creator') {
					foreach ($redis->keys('ranking:' . $mensagens['message']['chat']['id'] . ':*') as $hashs) {
						$redis->del($hashs);
					}

 						$rkgdel = TRUE;
 					$mensagem = 'O.K!';

 					break;
 				}
 			}

 			if ($rkgdel === FALSE) {
 				$mensagem = RANKING[$idioma]['SMT_CRIADOR'];
 			}
		} else {
			$chavesRanking = $redis->keys('ranking:' . $mensagens['message']['chat']['id'] . '*');

			foreach ($chavesRanking as $hash) {
				$dadosRanking[] = $redis->hgetall($hash);
			}

			$cont = 0;

			foreach ($dadosRanking as $rankingGrupo) {
				 $primeiroNome[$cont] = $rankingGrupo['primeiro_nome'];
				$qntdMensagens[$cont] = $rankingGrupo['qntd_mensagem'];

				++$cont;
			}

			array_multisort($qntdMensagens, SORT_DESC, $primeiroNome);

			$mensagem = '🏆 ' . RANKING[$idioma]['TITULO'] . "\n\n";

			for ($i=0;$i<30;$i++) {
				if (isset($qntdMensagens[$i])) {
					$mensagem = $mensagem . ($i+1) .') ' . $qntdMensagens[$i] . ' => ' . $primeiroNome[$i] . "\n";
				}
			}

			$mensagem = $mensagem . "\n" . '/rkgdel - ' . RANKING[$idioma]['SMT_CRIADOR'];
		}

		sendMessage($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id']);
	} else if ($mensagens['message']['chat']['type'] == 'private') {
		sendMessage($mensagens['message']['chat']['id'], ERROS[$idioma]['SMT_GRUPO'], $mensagens['message']['message_id'], null, TRUE);
	}
