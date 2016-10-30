<?php
	system('clear');
	echo '+------------+' . "\n";
	echo '| CARREGANDO |' . "\n";
	echo '+------------+' . "\n\n";

	include('funcoes.php');
	include('config.php');

	define('DADOS_BOT', getMe());

	if(DADOS_BOT['ok'] == true){
		include('idioma_bd.php');

							 $loop = true;
					 $updateID = 0;
		$mensagensMinuto = 0;
			 		$tituloBot = strtoupper(' 🤖  -> ' . DADOS_BOT['result']['first_name'] . '  (@' . DADOS_BOT['result']['username'] . ') ');
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
		public function __construct($mensagens) {
			$this->mensagens = $mensagens;
		}

		public function run(){
			$mensagens = $this->mensagens;

			$texto = explode(' ', $mensagens['message']['text']);

			if(empty($texto[1])){
				$texto[0] = str_ireplace('@' . DADOS_BOT['result']['username'], '', $texto[0]);
			}

			include(RAIZ . 'bot/idioma.php');

			switch(strtolower($texto[0])){
				case '/h':
				case '/help':
				case '/start':
				case '/ajuda':
				case '/ayuda':
				case '/aiuto':
		      include(RAIZ . 'blocos/ajuda.php');
		      break;
				case '/c':
				case '/calc':
			    include(RAIZ . 'blocos/calc.php');
			    break;
				case '/id':
		      include(RAIZ . 'blocos/id.php');
		      break;
				case '/r':
				case '/ranking':
		      include(RAIZ . 'blocos/ranking.php');
			  	break;
				case '/w':
				case '/wiki':
				case '/wikipedia':
			    include(RAIZ . 'blocos/wiki.php');
			    break;
			}

			include(RAIZ . 'blocos/ranking_bd.php');
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

			if(in_array($mensagens['message']['from']['id'], SUDOS)){
				include(RAIZ . 'blocos/sudos.php');
			}

			$threads[$mensagens['update_id']] = new botThread($mensagens['update_id']);
			$threads[$mensagens['update_id']]->mensagens = $mensagens;
			$threads[$mensagens['update_id']]->start();

			$mensagensMinuto = $mensagensMinuto + 1;
			$updateID = $mensagens['update_id'] + 1;
		}

		if($horaCache != date('H')){
				$horaCache = date('H');

			system('rm -rf ' . CACHE_PASTA . '*');

			echo '🔥  -> CACHE LIMPO!' . "\n\n";
		}
	}

	getUpdates($updateID);

	system('clear');
	echo '+-------------+' . "\n";
	echo '| REINICIADO! |' . "\n";
	echo '+-------------+' . "\n\n";

	die();
