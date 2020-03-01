<?php


use \Principal\Page;
use \Principal\Model\Product;
use \Principal\Model\Category;

//a unica coisa que muda, são as configuração dentro rota
$app->get('/', function() { //rota principal

	$products = Product::listAll();
    
    $page = new Page();//instancia da classe Page

    $page->setTpl("index", [
         'products'=>Product::checkList($products)
    ]);//direcionando para o index

    });

$app->get("/categories/:idcategory", function($idcategory){

    $category = new Category();

	$category->get((int)$idcategory);

	$page = new Page();

	$page->setTpl("category", [

       'category'=>$category->getValues(),
       'products'=>Product::checkList($category->getProducts())


	]);
	
});
	
?>