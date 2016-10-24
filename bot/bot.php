<?php
	include('funcoes.php');
	include('config.php');

	system('clear');
	echo '+------------+' . "\n";
	echo '| CARREGANDO |' . "\n";
	echo '+------------+' . "\n";

	$dadosBot = getMe();

	if($dadosBot['ok'] == true){
		system('clear');
		echo '+------------+' . "\n";
		echo '| EXECUTANDO |' . "\n";
		echo '+------------+' . "\n";
		sleep(1);
		system('clear');
		echo '+----------------------------+' . "\n";
		echo ' 🤖  => ' . $dadosBot['result']['first_name'] . '  (@' . $dadosBot['result']['username'] . ')' . "\n";
		echo '+----------------------------+' . "\n\n";
	}
	else{
		system('clear');
		echo '+------------+' . "\n";
		echo '| ERRO FATAL |' . "\n";
		echo '+------------+' . "\n\n";
	}

			$loop	= true;
	$updateID	= 0;

	$dadosRanking = carregarDados(RAIZ . 'dados/ranking.json');

	$mensagensMinuto = 0;
					 $inicio = microtime(true);

	while($loop == true){
		$resultado = getUpdates($updateID);

		foreach($resultado['result'] as $mensagens){
			$updateID = $mensagens['update_id'] + 1;

			if(isset($mensagens['edited_message'])){
				$mensagens['message'] = $mensagens['edited_message'];
				unset($mensagens['edited_message']);
			}
			else if(empty($mensagens['message']['text'])){
				$mensagens['message']['text'] = '';
			}

			include(RAIZ . 'blocos/ranking_bd.php');

exec('php '.RAIZ.'bot/resposta.php '.escapeshellarg(serialize($mensagens)).' '.escapeshellarg(serialize($dadosBot)).' > /tmp/bot.txt &');

			if(in_array($mensagens['message']['from']['id'], $sudos)){
				if(	strcasecmp($mensagens['message']['text'], '/reiniciar') == 0 OR
						strcasecmp($mensagens['message']['text'], '/reiniciar' . '@' . $dadosBot['result']['username']) == 0){
					$loop = false;

					system('clear');
					echo '+-------------+' . "\n";
					echo '| REINICIANDO |' . "\n";
					echo '+-------------+' . "\n\n";

					$mensagem = '<pre>Reiniciando...</pre>';

					sendMessage($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'], null, true);
				}
			}
		}
	}

	getUpdates($updateID);

	system('clear');
	echo '+-------------+' . "\n";
	echo '| REINICIADO! |' . "\n";
	echo '+-------------+' . "\n\n";

	die();
