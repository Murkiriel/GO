<?php
	Class RastroJS Extends Thread {
		public function run(){
			exec('cd ' . RAIZ . 'rastrojs && npm start');
		}
	}

	Class SinespAPI Extends Thread {
		public function run(){
			exec('cd ' . RAIZ . 'sinesp-api && nodejs index.js');
		}
	}

	Class ServicosThread Extends Thread {
		public function run(){
			include(RAIZ . 'blocos/servicos_thread.php');
		}
	}
