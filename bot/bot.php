<?php
	include('funcoes.php');
	include('config.php');
	include('idioma_bd.php');

	system('clear');
	echo '+------------+' . "\n";
	echo '| CARREGANDO |' . "\n";
	echo '+------------+' . "\n\n";

	define('DADOS_BOT', getMe());

	if(DADOS_BOT['ok'] == true){
				$dadosIdioma = carregarDados(RAIZ . 'dados/idioma.json');
			 $dadosRanking = carregarDados(RAIZ . 'dados/ranking.json');
							 $loop = true;
					 $updateID = 0;
			$qntdMensagens = 0;
			 		$tituloBot = strtoupper(' 🤖  -> ' . DADOS_BOT['result']['first_name'] . '  ( @' . DADOS_BOT['result']['username'] . ' ) ');
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
		public function __construct($mensagens){
			$this->mensagens = $mensagens;
		}

		public function run(){
			$mensagens = $this->mensagens;

			$GLOBALS['EDT_MSG'] = $mensagens['EDT_MSG'];

			$texto = explode(' ', $mensagens['message']['text']);

			if(empty($texto[1])){
				$texto[0] = str_ireplace('@' . DADOS_BOT['result']['username'], '', $texto[0]);
			}

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

				case '/i':
				case '/info':
				  include(RAIZ . 'blocos/info.php');
				  break;

				case '/books':
				case '/libri':
				case '/livros':
				case '/libros':
			  	include(RAIZ . '/blocos/livros.php');
			    break;

				case '/r':
				case '/ranking':
		      include(RAIZ . 'blocos/ranking.php');
			  	break;

				case '/s':
				case '/store':
			    include(RAIZ . 'blocos/store.php');
				  break;

				case '/w':
				case '/wiki':
			    include(RAIZ . 'blocos/wiki.php');
			    break;
			}

			include(RAIZ . 'blocos/documentos.php');
		}
	}

	$inicioIntervalo = microtime(true);

	while($loop == true){
		$resultado = getUpdates($updateID);
			$threads = [];

		if(is_array($resultado['result'])){
			foreach($resultado['result'] as $mensagens){
				$updateID = $mensagens['update_id'] + 1;

				$mensagens['EDT_MSG'] = false;

				if(isset($mensagens['callback_query'])){
					$mensagens['callback_query']['message']['text'] = $mensagens['callback_query']['data'];
					$mensagens['message'] = $mensagens['callback_query']['message'];

					if(isset($mensagens['message']['reply_to_message']['from'])){
						$mensagens['message']['from'] = $mensagens['message']['reply_to_message']['from'];
					}

					$mensagens['EDT_MSG'] = true;

					unset($mensagens['callback_query']);
				}
				else if(isset($mensagens['edited_message'])){
					$mensagens['message'] = $mensagens['edited_message'];

					unset($mensagens['edited_message']);
				}
				else if(empty($mensagens['message']['text'])){
					$mensagens['message']['text'] = '';
				}

				$GLOBALS['EDT_MSG'] = $mensagens['EDT_MSG'];

				$texto = explode(' ', $mensagens['message']['text']);

				include(RAIZ . 'bot/idioma.php');

				if($continue == true){continue;}

				include(RAIZ . 'blocos/ranking_bd.php');
				include(RAIZ . 'blocos/sudos.php');

				$threads[$mensagens['update_id']] = new botThread($mensagens['update_id']);
				$threads[$mensagens['update_id']]->mensagens = $mensagens;
				$threads[$mensagens['update_id']]->start();
			}
		}

		if($horaCache != date('H')){
			 $horaCache  = date('H');

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
