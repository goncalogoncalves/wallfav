<?php

use Slim\Slim;

class url  {

	// Devolve informacoes de uma pagina web
	public function get_info_pagina($url) {

		//$url = mysql_real_escape_string($url);
		$url = strip_tags($url);

		// link base
		// TODO: tratar de url localhost
		if(strpos($url, "http://") === false && strpos($url, "https://") === false){ $url = "http://".$url; }
		$result    = parse_url($url);
		$link_base = $result['scheme']."://".$result['host'];
		$link_raiz = $link_base;

		$link_base = $url;

		// titulo e metatags
		$html     = $this->file_get_contents_curl($link_base);
		$doc      = new DOMDocument();
		@$doc->loadHTML($html);
		$nodes    = $doc->getElementsByTagName('title');
		$metas    = $doc->getElementsByTagName('meta');
		$tag_link = $doc->getElementsByTagName('link');

		try {
			$titulo   = $nodes->item(0)->nodeValue;
		} catch (Exception $e) {
			$titulo = '';
		}

		for ($i = 0; $i < $metas->length; $i++) {
			$meta = $metas->item($i);

			if($meta->getAttribute('name') == 'description') { $description = $meta->getAttribute('content'); }
			if($meta->getAttribute('name') == 'keywords') { $keywords = $meta->getAttribute('content'); }
			if($meta->getAttribute('property') == 'og:image') { $og_image = $meta->getAttribute('content'); }
			if($meta->getAttribute('property') == 'og:title') { $og_title = $meta->getAttribute('content'); }
		}

		for ($i = 0; $i < $tag_link->length; $i++) {
			$tag_link_temp = $tag_link->item($i);
			if($tag_link_temp->getAttribute('rel') == 'shortcut icon') {  $icone = $tag_link_temp->getAttribute('href'); }
		}

		// imagem
		$link_raiz2 = str_replace("http://",'',$link_raiz);
		$link_raiz2 = str_replace("https://",'',$link_raiz2);
		$imagem     = "http://www.google.com/s2/favicons?domain=" . $link_raiz2;

		if(strpos($link_base, "http://") === false && strpos($link_base, "https://") === false){ $link_base = "http://".$link_base; }


		if (!isset($link_base) || $link_base == '') { $link_base = $url; }
		if (!isset($link_raiz) || $link_raiz == '') { $link_raiz = $url; }
		if (!isset($imagem)) { $imagem = ''; }
		if (!isset($description)) { $description = ''; }
		if (!isset($keywords)) { $keywords = ''; }
		if (!isset($og_image)) { $og_image = ''; }
		if (!isset($og_title)) { $og_title = ''; }
		if (!isset($icone)) { $icone = ''; }

		$app = Slim::getInstance();
		$app->log->info("titulo 1: ".$titulo);

		/*$titulo      = utf8_decode($titulo);
		$description = utf8_decode($description);
		$keywords    = utf8_decode($keywords);*/

		$titulo      = $this->remove_caracteres_especiais($titulo);
		$description = $this->remove_caracteres_especiais($description);
		$keywords    = $this->remove_caracteres_especiais($keywords);

		return array(
			'link_base'   => $link_base ,
			'link_raiz'   => $link_raiz ,
			'titulo'      => $titulo,
			'imagem'      => $imagem,
			'description' => $description,
			'keywords'    => $keywords,
			'og_image'    => $og_image,
			'icone'       => $icone
			);
	}



	function remove_caracteres_especiais($string) {

		$string = str_replace("<br>", "", $string );
		$string = str_replace("\n", "", $string );
		$string = str_replace("–", "-", $string );


		//$string = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '-', $string);
		$string = preg_replace("/[^\p{L}\p{N}()-|@ _'&]+/u", '', $string);


		return $string;
	}


	// Devolve o conteudo de uma pagina web
	public function file_get_contents_curl($url) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}

}

?>
