<?php
	require_once('funcoes.php');
	require_once('config.php');
	require_once('idioma.php');

	system('clear');
	echo '+----------+' . "\n";
	echo '| TESTANDO |' . "\n";
	echo '+----------+' . "\n\n";

	define('DADOS_BOT', getMe());

	if (DADOS_BOT['ok'] === true) {
		system('clear');
		echo '+-------------+' . "\n";
		echo '| ATUALIZANDO |' . "\n";
		echo '+-------------+' . "\n\n";

		$updateID = 0;
		$redis = conectarRedis();
		$redis->set('status_bot:loop', 'true');
		firstUpdate();

		$tituloBot = strtoupper(' 🤖 -> ' . DADOS_BOT['result']['first_name'] . '  ( @' . DADOS_BOT['result']['username'] . ' ) ');
			 $hifens = strlen($tituloBot) - 4;

		system('clear');
		echo '+';
		for ($i = 0; $i<$hifens; $i++) {echo '-'; }
		echo '+' . "\n" . '|' . $tituloBot . '|' . "\n" . '+';
		for ($i = 0; $i<$hifens; $i++) {echo '-'; }
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
			$texto = [];
			 $exit = false;

			include(RAIZ . 'bot/servicos.php');

			if ($exit === false) {
				switch (strtolower($texto[0])) {
					case '/start': case '/help':
						include(RAIZ . 'blocos/ajuda.php');
						break;
					case '/adms':
						include(RAIZ . 'blocos/adms.php');
						break;
					case '/bemvindo': case '/welcome': case '/bienvenida': case '/benvenuto':
						include(RAIZ . 'blocos/bemvindo.php');
						break;
					case '/calc':
						include(RAIZ . 'blocos/calc.php');
						break;
					case '/dm':
						include(RAIZ . 'blocos/dm.php');
						break;
					case '/duck':
						include(RAIZ . 'blocos/duck.php');
						break;
					case '/id':
				  	include(RAIZ . 'blocos/id.php');
				  	break;
					case '/info':
					  include(RAIZ . 'blocos/info.php');
					  break;
					case '/gerar': case '/generate': case '/generar': case '/generare':
					  include(RAIZ . 'blocos/gerar.php');
						break;
					case '/grupo': case '/group': case '/grupo': case '/gruppo':
					  include(RAIZ . 'blocos/grupo.php');
						break;
					case '/md5':
					  include(RAIZ . 'blocos/md5.php');
						break;
					case '/regras': case '/rules': case '/reglas': case '/regole':
						include(RAIZ . 'blocos/regras.php');
						break;
					case '/sha512':
					  include(RAIZ . 'blocos/sha512.php');
						break;
					case '/books': case '/libri': case '/livros': case '/libros':
				  	include(RAIZ . '/blocos/livros.php');
					break;
					case '/tv':
					  include(RAIZ . 'blocos/tv.php');
					  break;
					case '/psp':
						include(RAIZ . 'blocos/psp.php');
						break;
					case '/ranking': case '/rkgdel':
				  include(RAIZ . 'blocos/ranking.php');
				  	break;
					case '/snes':
					  include(RAIZ . 'blocos/snes.php');
					  break;
					case '/store':
					include(RAIZ . 'blocos/store.php');
					  break;
					case '/apoyo': case '/suporte': case '/support': case '/supporto':
						include(RAIZ . 'blocos/suporte.php');
						break;
					case '/wiki':
						include(RAIZ . 'blocos/wiki.php');
					break;
				}

				include(RAIZ . 'blocos/documentos.php');
				include(RAIZ . 'blocos/sudos.php');
			}

			$redis->close();
		}
	}

	while ($redis->get('status_bot:loop') === 'true') {
		$resultado = getUpdates($updateID);

		if (!empty($resultado['result']) and is_array($resultado['result'])) {
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

	die($redis->close());
