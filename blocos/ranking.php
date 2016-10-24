<?php
	if(	$mensagens['message']['chat']['type'] == 'group' 			OR
			$mensagens['message']['chat']['type'] == 'supergroup'	){
		$dadosRanking = carregarDados(RAIZ . 'dados/ranking.json');

		$chatID = (string)$mensagens['message']['chat']['id'];
		$cont = 0;

		foreach($dadosRanking[$chatID] as $rkgUsuarios){
			$rkgNome[$cont] = $rkgUsuarios['primeiro_nome'];
			$rkgMsgs[$cont] = $rkgUsuarios['qntd_mensagem'];

			++$cont;
		}

		array_multisort($rkgMsgs, SORT_DESC, $rkgNome);

		$mensagem = '🏆 ' . RANKING[IDIOMA]['TITULO'] . "\n\n";

		for($cont=0;$cont<30;$cont++){
			if(isset($rkgMsgs[$cont])){
				$i = $cont + 1;
				$mensagem = $mensagem . $i .') ' . $rkgMsgs[$cont] . ' => ' . $rkgNome[$cont] . "\n";
			}
		}

		$mensagem = $mensagem . "\n" . '/rkgdel - ' . RANKING[IDIOMA]['SMT_CRIADOR'];

		sendMessage($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id']);
	}
	else if($mensagens['message']['chat']['type'] == 'private'){
		$mensagem = ERROS[IDIOMA]['SMT_GRUPO'];

		sendMessage($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'], null, true);
	}

	die();
