<?php
	function filterPortal ($portals) 
	{ 
		$filteredPortals = array();
		foreach($portals as $portal){
			$index = strpos($portal, ">") + 1; #localiza onde o nome do portal começa
			$filteredPortal = substr($portal, $index, strlen($portal)-$index-4); #pega o nome do portal
			array_push($filteredPortals, $filteredPortal); #adiciona no vetor
		} 
		return $filteredPortals; #retorna o vetor;
	} 

	function filterTitle ($titles) 
	{ 
		$filteredTitles = array();
		foreach($titles as $title){
			$index = strpos($title, ">") + 1; #localiza onde o nome do portal começa
			$filteredTitle = substr($title, $index, strlen($title)-$index); #pega o nome do portal
			array_push($filteredTitles, $filteredTitle); #adiciona no vetor
		} 
		return $filteredTitles; #retorna o vetor;
	} 

	function filterEngagement($engagements) 
	{ 
		$filteredEngagements = array();
		foreach($engagements as $engagement){
			$index = strpos($engagement, ">") + 1; #localiza onde o nome do portal começa
			$filteredEngagement = substr($engagement, $index, strlen($engagement)-$index-7); #pega o nome do portal
			echo "";
			if(strpos($filteredEngagement, "+") !== false){
				$filteredEngagement[strpos($filteredEngagement, "+")] = '^';
			}
			else{
				$filteredEngagement = $filteredEngagement . ".";
			}
			array_push($filteredEngagements, $filteredEngagement); #adiciona no vetor
		} 
		return $filteredEngagements; #retorna o vetor;
	} 

	$content = file_get_contents($argv[1]);
	preg_match_all('/<div class=\"meta\-column\">(.*?)<\/div>/s',$content,$news_portal); #aqui a gente tá pegando o html do portal da noticia
	preg_match_all("'a class=\"title\"(.*?)</a>'si",$content,$news_title); 
		#aqui  gente tá pegando o html do titulo da noticia
	preg_match_all('/<div class=\"engagement\-container\">(.*?)<\/div>/s',$content,$news_engagement); #aqui a gente tá pegando o html dos acessos da noticia
	$portals = filterPortal($news_portal[1]); #retirando as tags html
	$titles = filterTitle($news_title[1]); #retirando as tags html
	$engagements = filterEngagement($news_engagement[1]); #retirando as tags html
	$csvLines = array(); #criando um vetor para armazenar os dados csv
	for ($i=0; $i < sizeof($engagements); $i++) { 
		array_push($csvLines, "\"$portals[$i]\",\"$titles[$i]\",\"$engagements[$i]\"\n") ; #armazenando nossos dados no vetor
	}
	$file = fopen("results.csv", "w+"); #abrindo o arquivo pra armazenar os dados
	fwrite($file, "Portal,Titulo,Acesso\n"); #criando cabeçalho do csv
	$contador = 1;
	foreach ($csvLines as $csv) { #iterando entre as linhas
		fwrite($file, $csv); #para cada linha adicinar ela no arquivo de saída
		echo "$contador - $csv"; #debug
		$contador++; #indicando qual noticia é (para comparação)
	}
	fclose($file); #fechando o arquivo
?>