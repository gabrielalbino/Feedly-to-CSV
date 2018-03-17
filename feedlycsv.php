<?php
	function filterPortal ($portal) 
	{ 
        $count = 0;
        $endIndex;
        while($count != 2){
            if($portal[$endIndex] == '>'){
                $index = $endIndex;
            }
            if($portal[$endIndex] == '<'){
                $count++;
            }
            $endIndex++;
        }
        $endIndex -= 1;
        $index += 1;
        $filteredPortal = substr($portal, $index, $endIndex - $index); #pega o nome do portal
		return $filteredPortal; #retorna o vetor;
    } 
    
    function mac_filterPortal ($portal) 
	{         
        $aux = strpos($portal, "a class=\"source\"");
        $count;
        $endIndex;
        while($count != 1){
            if($portal[$aux] == '>'){
                $index = $aux+1;
            }
            if($portal[$aux] == '<'){
                $endIndex = $aux;
                $count++;
            }
            $aux++;
        }
        $filteredPortal = substr($portal, $index, $endIndex - $index); #pega o nome do portal
        return $filteredPortal; #retorna o vetor;
	} 

	function filterTitle ($title) 
	{ 
        $aux = strlen($title)-1;
        $count;
        $endIndex;
        while($count != 2){
            if($title[$aux] == '<'){
                $endIndex = $aux;
            }
            if($title[$aux] == '>'){
                $count++;
            }
            $aux--;
        }
        $index = $aux + 2;
        $filteredTitle = substr($title, $index, $endIndex - $index); #pega o nome do portal
		return $filteredTitle; #retorna o vetor;
	} 

    
	function mac_filterTitle ($title) 
	{         
        $aux = strpos($title, "a class=\"title\"");
        echo "aux: $aux\n";
        $count;
        $endIndex;
        while($count != 1){
            if($title[$aux] == '>'){
                $index = $aux+1;
            }
            if($title[$aux] == '<'){
                $endIndex = $aux;
                $count++;
            }
            $aux++;
        }
        $filteredTitle = substr($title, $index, $endIndex - $index); #pega o nome do portal
        return $filteredTitle; #retorna o vetor;
    } 
    
	function filterEngagement($engagement) 
	{ 
        $aux = strpos($engagement, "div class=\"engagement-container\"");
        if($aux === false){
            return 0;
        }
        $count;
        $endIndex;
        while($count != 2){
            if($engagement[$aux] == '>'){
                $count++;
            }
            $aux++;
        }
        $index = $aux;
        $endIndex = $index;
        while($engagement[$endIndex] != '<'){
            $endIndex++;
        }
        $endindex--;
        $filteredEngagement = substr($engagement, $index, $endIndex - $index); #pega o nome do portal
		return $filteredEngagement; #retorna o vetor;
    } 

    function mac_filterEngagement($engagement) 
	{ 
        $aux = strpos($engagement, "data-dot=\"engagement-count\"");
        echo "EOQ";
        sleep(1000);
        if($aux === false){
            return 0;
        }
        $count;
        $endIndex;
        $index;
        while($count != 1){
            if($engagement[$aux] == '>'){
                $index = $aux+1;
            }
            if($engagement[$aux] == '<'){
                $endIndex = $aux;
                $count++;
            }
            $aux++;
        }
        $filteredEngagement = substr($engagement, $index, $endIndex - $index); #pega o nome do portal
        return $filteredEngagement; #retorna o vetor;
    } 

    $isMac = false;
    $content = file_get_contents($argv[1]);
	preg_match_all('/<div class=\"meta\-column\">(.*?)<div class=\"summary\">/s',$content,$news_content); #aqui a gente tá pegando o html da noticia
    print(sizeof($news_content[1]));
    if(sizeof($news_content[1]) == 0){
        preg_match_all('/<div class=\"content\">(.*?)<div class=\"visual\"/s',$content,$news_content); #aqui a gente tá pegando o html da noticia
        $isMac = true;
    }
    $csvLines = array(); #criando um vetor para armazenar os dados csv
    foreach($news_content[1] as $news){
        if($isMac == false){
            $title = filterTitle($news); #retirando as tags html
            $portal = filterPortal($news); #retirando as tags html
            $engagement = filterEngagement($news); #retirando as tags html
        }
        else{
            $title = mac_filterTitle($news);
            $portal = mac_filterPortal($news); #retirando as tags html
            $engagement = mac_filterEngagement($news); #retirando as tags html
        }
		array_push($csvLines, "\"$portal\",\"$title\",\"$engagement\"\n") ; #armazenando nossos dados no vetor
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
