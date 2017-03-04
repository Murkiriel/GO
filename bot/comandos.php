<?php
  namespace Comandos;

  class BotThread Extends \Thread {
    public static $blocos = ['ajuda'      => ['start', 'help', 'inicio', 'ajuda', 'inizio', 'ayuda', 'guida', 'allhelpfull'],
                             'adms'       => ['adms'],
                             'bemvindo'   => ['bemvindo', 'welcome', 'bienvenida', 'benvenuto'],
                             'calc'       => ['calc'],
                             'dicio'      => ['dicio'],
                             'dm'         => ['dm'],
                             'documentos' => ['store', 'tv', 'psp', 'snes', 'books', 'libri', 'livros', 'libros'],
                             'duck'       => ['duck'],
                             'id'         => ['id'],
                             'info'       => ['info'],
                             'gerar'      => ['gerar', 'generate', 'generar', 'generare'],
                             'grupo'      => ['group', 'grupo', 'gruppo'],
                             'md5'        => ['md5'],
                             'placa'      => ['placa'],
                             'rastro'     => ['rastro'],
                             'regras'     => ['regras', 'rules', 'reglas', 'regole'],
                             'sha512'     => ['sha512'],
                             'ranking'    => ['ranking', 'rkgdel'],
                             'suporte'    => ['suporte', 'apoyo', 'support', 'supporto'],
                             'wiki'       => ['wiki'],
                             'yt'         => ['yt'],
                             'sudos'      => ['sudos', 'promover', 'postagem', 'reiniciar',
                                              'removerdocumento', 'status', 'html', 'sms']];

    public function __construct($mensagens) {
      $this->mensagens = $mensagens;
    }

    public static function verificarComando($comando) {
      foreach (self::$blocos as $bloco => $comandos) {
        if (in_array(strtolower($comando), $comandos) === true) {
            return $bloco;
        }
      }

      return null;
    }

    public function run() {
      $redis = conectarRedis();
      $exit = false;
      $texto = [];

      include(RAIZ . 'bot/checagem.php');

      if ($exit === false) {
        $bloco = self::verificarComando($texto[0]);

        if ($bloco !== null) {
          include(RAIZ . 'blocos/' . $bloco . '.php');
        }
      }

      include(RAIZ . 'blocos/servicos.php');

      unset($mensagens);

      $redis->close();
    }
  }
