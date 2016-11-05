<?php
	$mensagem = verificarCache($mensagens['message']['text'] . $mensagens['IDIOMA']);

	if($mensagem == null){
		if(isset($texto[1])){
			$nomeArtigo = str_ireplace($texto[0], '', $mensagens['message']['text']);

			$requisicao = 'http://' . $mensagens['IDIOMA'] . '.wikipedia.org/w/api.php?action=query&prop=extracts&format=json&exchars=480&exsectionformat=plain&explaintext=&redirects=&titles=' . urlencode($nomeArtigo);

			$resultados	= json_decode(file_get_contents($requisicao, false, CONTEXTO), true);
				 $paginas = $resultados['query']['pages'];
				$idPagina = array_keys($paginas);

			 if($idPagina[0] != -1){
				 	 $tituloPagina = $paginas[$idPagina[0]]['title'];
				 $conteudoPagina = $paginas[$idPagina[0]]['extract'];
				 			$urlPagina = 'http://' . $mensagens['IDIOMA'] . '.wikipedia.com/wiki/' . str_replace(' ', '_', $tituloPagina);
				 			 $mensagem = '🗄 <a href="' . $urlPagina . '">' . $tituloPagina . '</a>' . "\n\n" . $conteudoPagina;

				salvarCache($mensagens['message']['text'] . $mensagens['IDIOMA'], $mensagem);
			}
			else{
				$mensagem = ERROS[$mensagens['IDIOMA']][SEM_RSULT];
			}
		}
		else{
			$mensagem = '📚: /wiki Brasil';
		}
	}

	sendMessage($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'], null, true);
