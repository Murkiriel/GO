<?php
	namespace Classes;

	class ColetorLixo extends \Worker {
		public function run(){
			gc_enable();
			ini_set("memory_limit", -1);
		}
	}

	class RastroJS Extends \Thread {
		public function run() {
			exec('cd ' . RAIZ . 'rastrojs && npm start');
		}
	}

	class ServicosThread Extends \Thread {
		public function run() {
			include(RAIZ . 'blocos/servicos_thread.php');
		}
	}
