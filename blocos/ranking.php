<?php
	if ($mensagens['message']['chat']['type'] == 'group' or $mensagens['message']['chat']['type'] == 'supergroup') {
		if (strtolower($texto[0]) == 'rkgdel') {
 				 $resultado = getChatAdministrators($mensagens['message']['chat']['id']);
			$usuarioAdmin = false;

 			foreach ($resultado['result'] as $adminsGrupo) {
 				if ($adminsGrupo['user']['id'] == $mensagens['message']['from']['id'] and $adminsGrupo['status'] == 'creator') {
					foreach ($redis->keys('ranking:' . $mensagens['message']['chat']['id'] . ':*') as $hash) {
						$redis->del($hash);
					}

 					$usuarioAdmin = true;
 							$mensagem = 'O.K!';

 					break;
 				}
 			}

 			if ($usuarioAdmin === false) {
 				$mensagem = RANKING[$idioma]['SMT_CRIADOR'];
 			}
		} else {
			 $dadosRanking = [];

			foreach ($redis->keys('ranking:' . $mensagens['message']['chat']['id'] . '*') as $hash) {
				$dadosRanking[] = $redis->hgetall($hash);
			}

						$cont = 0;
			$totalGrupo = 0;

			foreach ($dadosRanking as $rankingGrupo) {
				 $primeiroNome[$cont] = $rankingGrupo['primeiro_nome'];
				$qntdMensagens[$cont] = $rankingGrupo['qntd_mensagem'];
				$totalGrupo = $totalGrupo + $qntdMensagens[$cont];

				++$cont;
			}

			array_multisort($qntdMensagens, SORT_DESC, $primeiroNome);

			$mensagem = '🏆 ' . RANKING[$idioma]['TITULO'] . "\n\n";

			for ($i=0;$i<30;$i++) {
				if (isset($qntdMensagens[$i])) {
					$mensagem = $mensagem . ($i+1) .') ' . $qntdMensagens[$i] . ' => ' . $primeiroNome[$i] . "\n";
				} else {
					break;
				}
			}

			$mensagem = $mensagem . "\n" . RANKING[$idioma]['TOTAL'] . $totalGrupo . "\n\n" . '/rkgdel - ' . RANKING[$idioma]['SMT_CRIADOR'];
		}
	} else if ($mensagens['message']['chat']['type'] == 'private') {
		$mensagem = strip_tags(ERROS[$idioma]['SMT_GRUPO']);
	}

	sendMessage($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'], null, true);
