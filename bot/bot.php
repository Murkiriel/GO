<?php
	system('clear');
	echo '+------------+' . "\n";
	echo '| CARREGANDO |' . "\n";
	echo '+------------+' . "\n\n";

	include('funcoes.php');
	include('config.php');

	$dadosBot = getMe();

	if($dadosBot['ok'] == true){
		system('clear');
		echo '+------------+' . "\n";
		echo '| EXECUTANDO |' . "\n";
		echo '+------------+' . "\n\n";
		sleep(1);

		include('idioma_bd.php');

							 $loop = true;
					 $updateID = 0;
		$mensagensMinuto = 0;
			 $dadosRanking = carregarDados(RAIZ . 'dados/ranking.json');
			 		$tituloBot = strtoupper(' 🤖  -> ' . $dadosBot['result']['first_name'] . '  (@' . $dadosBot['result']['username'] . ') ');
						 $linhas = strlen($tituloBot) - 6;

		system('clear');
		echo '+';
		for($i=0;$i<$linhas;$i++){echo '-';}
		echo '+' . "\n" . '|' . $tituloBot . '|' . "\n" . '+';
		for($i=0;$i<$linhas;$i++){echo '-';}
		echo '+' . "\n\n";
	}
	else{
		echo "\n\n";
		echo '+------------+' . "\n";
		echo '| ERRO FATAL |' . "\n";
		echo '+------------+' . "\n\n";

		die();
	}

	class botThread extends Thread{
		public $dadosBot;
		public $mensagens;
		public $IDIOMA;

		public function run(){
			 $dadosBot = $this->dadosBot;
			$mensagens = $this->mensagens;
				 $IDIOMA = $this->IDIOMA;

			$texto = explode(' ', $mensagens['message']['text']);

			if(empty($texto[1])){
				$texto[0] = str_ireplace('@' . $dadosBot['result']['username'], '', $texto[0]);
			}

			switch(strtolower($texto[0])){
				case '/start':
				case '/ajuda':
				case '/help':
				case '/ayuda':
				case '/aiuto':
		      include(RAIZ . '/blocos/ajuda.php');
		      break;
				case '/id':
		      include(RAIZ . '/blocos/id.php');
		      break;
				case '/ranking':
		      include(RAIZ . '/blocos/ranking.php');
		      break;
			}

			if(in_array($mensagens['message']['from']['id'], SUDOS)){
				include(RAIZ . 'blocos/sudos.php');
			}
		}
	}

	$inicioIntervalo = microtime(true);

	while($loop == true){
		$resultado = getUpdates($updateID);
			$threads = [];

		foreach($resultado['result'] as $mensagens){
			if(isset($mensagens['edited_message'])){
				$mensagens['message'] = $mensagens['edited_message'];
				unset($mensagens['edited_message']);
			}
			else if(empty($mensagens['message']['text'])){
				$mensagens['message']['text'] = '';
			}

			include(RAIZ . 'bot/idioma.php');

			if($continue == true){
				$updateID = $mensagens['update_id'] + 1;

				continue;
			}

			include(RAIZ . 'blocos/ranking_bd.php');

			$threads[$mensagens['update_id']] = new botThread($mensagens['update_id']);
			$threads[$mensagens['update_id']]->dadosBot	 = $dadosBot;
			$threads[$mensagens['update_id']]->mensagens = $mensagens;
			$threads[$mensagens['update_id']]->IDIOMA	 = $IDIOMA;
			$threads[$mensagens['update_id']]->start();

			if(in_array($mensagens['message']['from']['id'], SUDOS)){
				if(	strcasecmp($mensagens['message']['text'], '/reiniciar') == 0 OR
						strcasecmp($mensagens['message']['text'], '/reiniciar' . '@' . $dadosBot['result']['username']) == 0){
					system('clear');
					echo '+-------------+' . "\n";
					echo '| REINICIANDO |' . "\n";
					echo '+-------------+' . "\n\n";

					$loop = false;

					$mensagem = '<pre>Reiniciando...</pre>';

					sendMessage($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'], null, true);
				}
			}

			$updateID = $mensagens['update_id'] + 1;
		}
	}

	getUpdates($updateID);

	system('clear');
	echo '+-------------+' . "\n";
	echo '| REINICIADO! |' . "\n";
	echo '+-------------+' . "\n\n";

	die();
