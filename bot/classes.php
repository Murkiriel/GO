<?php
	namespace Classes;

	class RastroJS Extends \Thread {
		public function run(){
			exec('cd ' . RAIZ . 'rastrojs && npm start');
		}
	}

	class ServicosThread Extends \Thread {
		public function run(){
			include(RAIZ . 'blocos/servicos_thread.php');
		}
	}
