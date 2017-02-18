<?php
	$chave = md5($idioma . $mensagens['message']['text']);

	if ($redis->exists('wiki:' . $chave) === true) {
		$mensagem = $redis->get('wiki:' . $chave);
	} else if (isset($texto[1])) {
		$artigo = str_ireplace($texto[0] . ' ', '', $mensagens['message']['text']);

		$requisicao = 'https://' . $idioma . '.wikipedia.org/w/api.php?action=query&prop=extracts&format=json&exchars=480&exsectionformat=plain&explaintext=&redirects=&titles=' . urlencode($artigo);
		 $resultado = json_decode(enviarRequisicao($requisicao), true);

		 $idPagina = array_keys($resultado['query']['pages']);

		 if ($idPagina[0] != -1) {
			 $urlPagina = 'https://' . $idioma . '.wikipedia.com/wiki/' . str_replace(' ', '_', $resultado['query']['pages'][$idPagina[0]]['title']);
			 	$mensagem = '🗄 <a href="' . $urlPagina . '">' . $resultado['query']['pages'][$idPagina[0]]['title'] . '</a>' . "\n\n" . $resultado['query']['pages'][$idPagina[0]]['extract'];
		} else {
			$mensagem = ERROS[$idioma][SEM_RSULT];
		}

		$redis->setex('wiki:' . $chave, 3600, $mensagem);
	} else {
		$mensagem = '📚: /wiki Brasil';
	}

	sendMessage($mensagens['message']['chat']['id'], $mensagem, $mensagens['message']['message_id'], null, true);
