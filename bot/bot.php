<?php
	require_once('classes.php');
	require_once('funcoes.php');
	require_once('metodos.php');
	require_once('config.php');
	require_once('idioma.php');

	require_once('lib/sinesp.php');

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

		$rastroJS = new RastroJS();
		$rastroJS->start();

		$redis = conectarRedis();
		$redis->set('status_bot:loop', 'true');

		firstUpdate();
		$updateID = 0;

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

	require_once('comandos.php');

	use Comandos\BotThread;

	while ($redis->get('status_bot:loop') === 'true') {
		$resultado = getUpdates($updateID);

		if (!empty($resultado['result']) and is_array($resultado['result'])) {
			$threads = [];

			foreach ($resultado['result'] as $mensagens) {
				$updateID = $mensagens['update_id'] + 1;

				if ($redis->exists('status_bot:msg_atendidas:' . $updateID) === false) {
					$redis->setex('status_bot:msg_atendidas:' . $updateID, 60, 'OK');

					$threads[$updateID] = new BotThread($updateID);
					$threads[$updateID]->mensagens = $mensagens;
					$threads[$updateID]->start();
				}
			}
		}

		$servicosThread = new ServicosThread();
		$servicosThread->start();
	}

	getUpdates($updateID);

	system('clear');
	echo '+-------------+' . "\n";
	echo '| REINICIADO! |' . "\n";
	echo '+-------------+' . "\n\n";

	$redis->close() && die();
