<?php
	Class RastroJS Extends Thread {
		public function run(){
			exec('cd ' . RAIZ . 'rastrojs && npm start');
		}
	}

	Class ServicosThread Extends Thread {
		public function run(){
			include(RAIZ . 'blocos/servicos_thread.php');
		}
	}
