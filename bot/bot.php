<?php
	require('classes.php');
	require('funcoes.php');
	require('metodos.php');
	require('config.php');
	require('idioma.php');
	require('comandos.php');

	use Classes\ColetorLixo;
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

		ini_set("memory_limit", -1);

		$worker = new ColetorLixo();
		$worker->start();

		if (pingServidor('127.0.0.1', '3000') == -1) {
			$rastroJS = new RastroJS();
			$rastroJS->start();

			$servicos = new ServicosThread();
			$servicos->start();
		}

		$redis = conectarRedis();
		$loop = 'true';
		$redis->setex('status_bot:loop', 43200, $loop);

		firstUpdate();
		$updateID = 0;

		$tituloBot = strtoupper(' 🤖 -> ' . DADOS_BOT['result']['first_name'] . '  ( @' . DADOS_BOT['result']['username'] . ' ) ');
		$hifens = strlen($tituloBot) - 2;

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

	while ($loop === 'true') {
		$resultado = getUpdates($updateID);

		if (!empty($resultado['result'])) {
			foreach ($resultado['result'] as $mensagens) {
				$updateID = $mensagens['update_id'] + 1;

				if ($redis->exists('status_bot:msg_atendidas:' . $updateID) === false) {
					$redis->setex('status_bot:msg_atendidas:' . $updateID, 60, 'OK');

					$worker->stack(new BotThread($mensagens));
				}
			}

			while ($worker->collect()) {
				continue;
			}
		}

		if ($redis->exists('status_bot:loop') === false) {
			system('clear');

			echo '+-------------+' , "\n";
			echo '| REINICIANDO |' , "\n";
			echo '+-------------+' , "\n\n";

			notificarSudos('<pre>Reiniciando e liberando memória...</pre>');

			getUpdates($updateID);

			$redis->close();

			$worker->shutdown();

			exec('ps -ef | grep bot.php | grep -v grep | awk \'{print $2}\' | xargs kill -9');
		}
	}
