<?php
	 $qntdMensagens = $qntdMensagens + 1;
	$totalIntervalo = microtime(true) - $inicioIntervalo;

	if($totalIntervalo > 59){
		$dadosRanking['QM'] = $qntdMensagens;

		$qntdMensagens = 0;
		$inicioIntervalo = microtime(true);
	}

	if($mensagens['message']['chat']['type'] == 'group' OR $mensagens['message']['chat']['type'] == 'supergroup'){
		if(isset($dadosRanking[$mensagens['message']['chat']['id']])){
			if(empty($dadosRanking[$mensagens['message']['chat']['id']][$mensagens['message']['from']['id']]['qntd_mensagem'])){
							 $dadosRanking[$mensagens['message']['chat']['id']][$mensagens['message']['from']['id']]['qntd_mensagem'] = 0;
			}

			$dadosRanking[$mensagens['message']['chat']['id']][$mensagens['message']['from']['id']]['primeiro_nome'] = $mensagens['message']['from']['first_name'];
			$dadosRanking[$mensagens['message']['chat']['id']][$mensagens['message']['from']['id']]['qntd_mensagem'] = $dadosRanking[$mensagens['message']['chat']['id']][$mensagens['message']['from']['id']]['qntd_mensagem'] + 1;
		}
		else{
			$dadosRanking[$mensagens['message']['chat']['id']][$mensagens['message']['from']['id']]['primeiro_nome'] = $mensagens['message']['from']['first_name'];
			$dadosRanking[$mensagens['message']['chat']['id']][$mensagens['message']['from']['id']]['qntd_mensagem'] = 1;
		}
	}

	if(	strcasecmp($mensagens['message']['text'], '/rkgdel')																				 == 0 OR
			strcasecmp($mensagens['message']['text'], '/rkgdel' . '@' . DADOS_BOT['result']['username']) == 0 ){

			if($mensagens['message']['chat']['type'] == 'group' OR $mensagens['message']['chat']['type'] == 'supergroup'){
				$resultado = getChatAdministrators($mensagens['message']['chat']['id']);
					 $rkgdel = false;

				foreach($resultado['result'] as $admins){
					if($admins['user']['id'] == $mensagens['message']['from']['id'] AND $admins['status'] == 'creator'){
						unset($dadosRanking[$mensagens['message']['chat']['id']]);

							$rkgdel = true;
						$mensagem	= '<b>O.K!</b>';

						break;
					}
				}

				if($rkgdel == false){
					$mensagem = RANKING[$mensagens['IDIOMA']]['SMT_CRIADOR'];
				}
			}
			else if($mensagens['message']['chat']['type'] == 'private'){
				$mensagem = ERROS[$mensagens['IDIOMA']]['SMT_GRUPO'];
			}

			sendMessage($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'], null, true);
	}

	salvarDados(RAIZ . 'dados/ranking.json', $dadosRanking);
