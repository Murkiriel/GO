<?php
	$mensagem = verificarCache($mensagens['message']['text'] . $IDIOMA);

	if($mensagem == null){
		if(isset($texto[1])){
			if(isset($texto[3])){
				$requisicao = 'http://' . $IDIOMA . '.wikipedia.org/w/api.php?action=query&prop=extracts&format=json&exchars=480&exsectionformat=plain&explaintext=&redirects=&titles=' . urlencode($texto[1] . ' ' . $texto[2] . ' ' . $texto[3]);
			}
			else if(isset($texto[2])){
				$requisicao = 'http://' . $IDIOMA . '.wikipedia.org/w/api.php?action=query&prop=extracts&format=json&exchars=480&exsectionformat=plain&explaintext=&redirects=&titles=' . urlencode($texto[1] . ' ' . $texto[2]);
			}
			else{
				$requisicao = 'http://' . $IDIOMA . '.wikipedia.org/w/api.php?action=query&prop=extracts&format=json&exchars=480&exsectionformat=plain&explaintext=&redirects=&titles=' . urlencode($texto[1]);
			}

			$resultados	= json_decode(file_get_contents($requisicao, false, CONTEXTO), true);
				 $paginas = $resultados['query']['pages'];
				$idPagina = array_keys($paginas);

			 if($idPagina[0] != -1){
				 	 $tituloPagina = $paginas[$idPagina[0]]['title'];
				 $conteudoPagina = $paginas[$idPagina[0]]['extract'];
				 			$urlPagina = 'http://' . $IDIOMA . '.wikipedia.com/wiki/' . str_replace(' ', '_', $tituloPagina);
				 			 $mensagem = '🗄 <a href="' . $urlPagina . '">' . $tituloPagina . '</a>' . "\n\n" . $conteudoPagina;

				salvarCache($mensagens['message']['text'] . $IDIOMA, $mensagem);
			}
			else{
				$mensagem = ERROS[$IDIOMA][SEM_RSULT];
			}
		}
		else{
			$mensagem = '<b>Ex.:</b> /wiki Brasil';
		}
	}

	sendMessage($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'], null, true);
