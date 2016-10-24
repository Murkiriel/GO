<?php
	$mensagem = ID[IDIOMA]['NOME'] . ': ' . $mensagens['message']['from']['first_name'];

	if(isset($mensagens['message']['from']['username'])){
		$mensagem = $mensagem . ' (@'. $mensagens['message']['from']['username'].')' ."\n".
							'ID: ' . $mensagens['message']['from']['id'];
	}
	else{
		$mensagem = $mensagem . "\n" . 'ID: ' . $mensagens['message']['from']['id'];
	}

	if($mensagens['message']['chat']['type'] == 'group' OR $mensagens['message']['chat']['type'] == 'supergroup'){
		$mensagem = $mensagem."\n\n".'Chat: '.$mensagens['message']['chat']['title'].' (ID: '.$mensagens['message']['chat']['id'].')';

		$dados = carregarDados(RAIZ . 'dados/ranking.json');

		$mensagem = $mensagem."\n".ID[IDIOMA]['MSGS'].': '.$dados[$mensagens['message']['chat']['id']][$mensagens['message']['from']['id']]['qntd_mensagem'];
	}
	else if($mensagens['message']['chat']['type'] == 'private'){
		$mensagem = $mensagem . "\n\n" . ID[IDIOMA]['PRVD'];
	}

	$resultado = getUserProfilePhotos($mensagens['message']['from']['id']);

	if(isset($resultado['result']['photos'][0][0]['file_id'])){
		sendPhoto($mensagens['message']['chat']['id'],$resultado['result']['photos'][0][0]['file_id'],$mensagens['message']['message_id'],
			null,$mensagem);
	}
	else{
		sendMessage($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id']);
	}

	die();
