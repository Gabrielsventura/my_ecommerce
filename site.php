<?php


use \Principal\Page;
use \Principal\Model\Product;

//a unica coisa que muda, são as configuração dentro rota
$app->get('/', function() { //rota principal

	$products = Product::listAll();
    
    $page = new Page();//instancia da classe Page

    $page->setTpl("index", [
         'products'=>Product::checkList($products)
    ]);//direcionando para o index

    });
	
?>