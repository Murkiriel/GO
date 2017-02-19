<?php
  namespace Comandos;

  Class BotThread Extends \Thread {
    public function __construct($mensagens) {
      $this->mensagens = $mensagens;
    }

    public function run() {
      $redis = conectarRedis();
      $texto = [];
       $exit = false;

      include(RAIZ . 'bot/checagem.php');

      if ($exit === false) {
        switch (strtolower($texto[0])) {
          case '/start':  case '/inicio': case '/inizio':
          case '/help':   case '/ajuda':  case '/ayuda':  case '/guida':
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
          case '/dicio':
            include(RAIZ . 'blocos/dicio.php');
            break;
          case '/dm':
            include(RAIZ . 'blocos/dm.php');
            break;
          case '/store':  case 'tv':      case '/psp':    case '/snes':
          case '/books':  case '/libri':  case '/livros': case '/libros':
          include(RAIZ . 'blocos/documentos.php');
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
          case '/placa':
            include(RAIZ . 'blocos/placa.php');
            break;
          case '/rastro':
            include(RAIZ . 'blocos/rastro.php');
            break;
          case '/regras': case '/rules': case '/reglas': case '/regole':
            include(RAIZ . 'blocos/regras.php');
            break;
          case '/sha512':
            include(RAIZ . 'blocos/sha512.php');
            break;
          case '/ranking': case '/rkgdel':
          include(RAIZ . 'blocos/ranking.php');
            break;
          case '/apoyo': case '/suporte': case '/support': case '/supporto':
            include(RAIZ . 'blocos/suporte.php');
            break;
          case '/wiki':
            include(RAIZ . 'blocos/wiki.php');
          break;
          case '/sudos':      case '/promover':         case '/postagem':
          case '/reiniciar':  case '/removerdocumento': case '/status':
            include(RAIZ . 'blocos/sudos.php');
          break;
        }

        include(RAIZ . 'blocos/servicos.php');
      }

      $redis->close();
    }
  }
