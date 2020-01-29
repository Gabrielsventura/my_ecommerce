<?php


use \Principal\Page;

//a unica coisa que muda, são as configuração dentro rota
$app->get('/', function() { //rota principal
    
    $page = new Page();//instancia da classe Page

    $page->setTpl("index");//direcionando para o index

    });
	
?>