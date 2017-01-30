<?php
	require_once('funcoes.php');
	require_once('config.php');
	require_once('idioma.php');

	system('clear');
	echo '+----------+' . "\n";
	echo '| TESTANDO |' . "\n";
	echo '+----------+' . "\n\n";

	define('DADOS_BOT', getMe());

	if (DADOS_BOT['ok'] === TRUE) {
		system('clear');
		echo '+-------------+' . "\n";
		echo '| ATUALIZANDO |' . "\n";
		echo '+-------------+' . "\n\n";

		$updateID = 0;
		$redis->set('status_bot:loop', 'TRUE');
		firstUpdate();

		$tituloBot = strtoupper(' 🤖 -> ' . DADOS_BOT['result']['first_name'] . '  ( @' . DADOS_BOT['result']['username'] . ' ) ');
			 $hifens = strlen($tituloBot) - 4;

		system('clear');
		echo '+';
		for ($i=0;$i<$hifens;$i++){echo '-'; }
		echo '+' . "\n" . '|' . $tituloBot . '|' . "\n" . '+';
		for ($i=0;$i<$hifens;$i++){echo '-'; }
		echo '+' . "\n\n";
	} else {
		echo "\n\n";
		echo '+------------------+' . "\n";
		echo '| ERRO AO CONECTAR |' . "\n";
		echo '+------------------+' . "\n\n";

		die();
	}

	class botThread extends Thread {
		public function __construct($mensagens) {
			$this->mensagens = $mensagens;
		}

		public function run() {
					$redis = conectarRedis();
			$mensagens = $this->mensagens;
					$texto = [];
					 $exit = false;

			include(RAIZ . 'bot/servicos.php');

			if ($exit === true) { $redis->close() && exit(); }

			switch (strtolower($texto[0])) {
				case '/help':
				case '/start':
				case '/ajuda':
				case '/ayuda':
				case '/aiuto':
		      include(RAIZ . 'blocos/ajuda.php');
		      break;
				case '/calc':
			    include(RAIZ . 'blocos/calc.php');
			    break;
				case '/id':
		      include(RAIZ . 'blocos/id.php');
		      break;
				case '/info':
				  include(RAIZ . 'blocos/info.php');
				  break;
				case '/books':
				case '/libri':
				case '/livros':
				case '/libros':
			  	include(RAIZ . '/blocos/livros.php');
			    break;
				case '/tv':
				  include(RAIZ . 'blocos/tv.php');
				  break;
				case '/ranking':
				case '/rkgdel':
		      include(RAIZ . 'blocos/ranking.php');
			  	break;
				case '/store':
			    include(RAIZ . 'blocos/store.php');
				  break;
				case '/wiki':
			    include(RAIZ . 'blocos/wiki.php');
			    break;
			}

			include(RAIZ . 'blocos/documentos.php');

			if (in_array($mensagens['message']['from']['id'], SUDOS)) {
				include(RAIZ . 'blocos/sudos.php');
			}

			$redis->close() && exit();
		}
	}

	while ($redis->get('status_bot:loop') === 'TRUE') {
		$resultado = getUpdates($updateID);

		if (!empty($resultado['result']) AND is_array($resultado['result'])) {
			$threads = [];

			foreach ($resultado['result'] as $mensagens) {
				$updateID = $mensagens['update_id'] + 1;

				if (!$redis->exists('status_bot:msg_atendidas:' . $updateID)) {
					$redis->setex('status_bot:msg_atendidas:' . $updateID, 60, 'OK');

					$threads[$updateID] = new botThread($updateID);
					$threads[$updateID]->mensagens = $mensagens;
					$threads[$updateID]->start();
				}
			}
		}
	}

	getUpdates($updateID);

	system('clear');
	echo '+-------------+' . "\n";
	echo '| REINICIADO! |' . "\n";
	echo '+-------------+' . "\n\n";

	$redis->close() && die();
