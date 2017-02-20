<?php
	require('classes.php');
	require('funcoes.php');
	require('metodos.php');
	require('config.php');
	require('idioma.php');
	require('comandos.php');

	require(RAIZ . 'lib/sinesp.php');

	use Classes\RastroJS;
	use Classes\ServicosThread;
	use Comandos\BotThread;

	system('clear');

	echo '+------------+', "\n";
	echo '| CONECTANDO |', "\n";
	echo '+------------+', "\n\n";

	define('DADOS_BOT', getMe());

	if (DADOS_BOT['ok'] === true) {
		system('clear');

		echo '+-------------+', "\n";
		echo '| ATUALIZANDO |', "\n";
		echo '+-------------+', "\n\n";

		if (pingServidor('127.0.0.1', '3000') == -1) {
			$rastroJS = new RastroJS();
			$rastroJS->start();
		}

		$redis = conectarRedis();
		$loop = 'true';
		$redis->set('status_bot:loop', $loop);

		firstUpdate();
		$updateID = 0;

		$tituloBot = strtoupper(' 🤖 -> ' . DADOS_BOT['result']['first_name'] . '  ( @' . DADOS_BOT['result']['username'] . ' ) ');
		$hifens = strlen($tituloBot) - 4;

		system('clear');

		echo '+';

		for ($i = 0; $i<$hifens; $i++) {
			echo '-';
		}

		echo '+', "\n", '|', $tituloBot, '|', "\n", '+';

		for ($i = 0; $i<$hifens; $i++) {
			echo '-';
		}

		echo '+', "\n\n";
	} else {
		system('clear');

		echo '+------------------+', "\n";
		echo '| ERRO AO CONECTAR |', "\n";
		echo '+------------------+', "\n\n";

		die();
	}

	$closure = function($argumentos) {
		return $argumentos;
	};

	while ($loop === 'true') {
		$resultado = getUpdates($updateID);

		if (!empty($resultado['result'])) {
			$threads = [];

			foreach ($resultado['result'] as $mensagens) {
				$updateID = $mensagens['update_id'] + 1;

				if ($redis->exists('status_bot:msg_atendidas:' . $updateID) === false) {
					$redis->setex('status_bot:msg_atendidas:' . $updateID, 60, 'OK');

					$threads[$updateID] = BotThread::processo($closure, [0 => $mensagens]);
				}
			}
		}

		$servicos = [];
		$servicos[$updateID] = new ServicosThread();
		$servicos[$updateID]->start();

		$loop = $redis->get('status_bot:loop');
	}

	getUpdates($updateID);

	system('clear');

	echo '+-------------+', "\n";
	echo '| REINICIADO! |', "\n";
	echo '+-------------+', "\n\n";

	$redis->close() && die();
